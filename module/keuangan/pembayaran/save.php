<?php

$pv_paid_date = htmlspecialchars($_REQUEST['pv_paid_date']);
$pv_desc = htmlspecialchars(strtoupper($_REQUEST['pv_desc']));
$pv_bank_rek = htmlspecialchars($_REQUEST['pv_bank_rek']);
$rekout_segment = htmlspecialchars($_REQUEST['rekout_segment']);
$sumAmount = $_REQUEST['sumAmount'];
$sumScheme = $_REQUEST['sumScheme'];
$outlets = json_decode($_POST['data1']);
$data = json_decode($_POST['data2']);
foreach($outlets as $outlet){
	$spuk_outlet = $outlet->pv_outlet;
}

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

//sequence
$query = "select * from seq_pv where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data2 = mysqli_fetch_array($hasil);
	$tahun = $data2['year'];
	if($tahun < year){
		$seq = '0000001';
	} else {
		$seq = sprintf("%'.07d",$data2['seq']+1);
	}
	$pv_no = $_SESSION['outlet'].date('y').'PVD'.$seq;
	$jv_no = str_replace('PVD','JVD',$pv_no);
	$year = date('y');
	$month = strtoupper(date('M'));
}

$query2 = "insert into seq_pv(outlet,year,seq) values('$_SESSION[outlet]','$year','$seq')";
$hasil2 = mysqli_query($con,$query2);
if($hasil2 == false){
	array_push($error,mysqli_error($con));
}

foreach($data as $dt){
	$sql = "update fin_trn_payment set
				pv_no='$pv_no',
				pv_desc='$pv_desc',
				pv_bank_rek='$pv_bank_rek',
				pv_paid_status='P',
				pv_paid_by='$_SESSION[uid]',
				pv_paid_date='$pv_paid_date',
				pv_segment1='$_SESSION[outlet]',
				pv_segment2='$rekout_segment'
			where pv_proses_id='$dt->pv_proses_id'";
	$result = mysqli_query($con,$sql);
	if($result == false){
		array_push($error,mysqli_error($con));
	}
	
	// start pelunasan spuk
	$sql2 = "update spuk_hdr set
				spuk_status='P'
			where spuk_id='$dt->pv_proses_id'";
	$result2 = mysqli_query($con,$sql2);
	if($result2 == false){
		array_push($error,mysqli_error($con));
	}
	
	$query7 = "select spuk_dtl_utj from spuk_dtl where spuk_dtl_id='$dt->pv_proses_id'";
	$hasil7 = mysqli_query($con,$query7);
	while($data7 = mysqli_fetch_array($hasil7)){
		$spuk_dtl_utj = $data7['spuk_dtl_utj'];
		
		$sql15 = "update unit_titip_jual set
					utj_status='P',
					utj_update_by='$_SESSION[uid]',
					utj_updated=now()
				where utj_id='$spuk_dtl_utj'";
		$result15 = mysqli_query($con,$sql15);
		if($result15 == false){
			array_push($error,mysqli_error($con));
		}
	}
	
	$query8 = "select rrv_no_rv from repayment_rv where rrv_spuk_id='$dt->pv_proses_id'";
	$hasil8 = mysqli_query($con,$query8);
	while($data8 = mysqli_fetch_array($hasil8)){
		$no_rv = $data8['rrv_no_rv'];
		
		$sql3 = "update fin_trn_rv set
					rv_status='C'
				where rv_no='$no_rv'";
		$result3 = mysqli_query($con,$sql3);
		if($result3 == false){
			array_push($error,mysqli_error($con));
		}
	}
	
	$query4 = "select 
				rrv_no_rv,
				rv_amount
			from repayment_rv a 
			join fin_trn_rv b on a.rrv_no_rv=b.rv_no
			where rrv_spuk_id='$dt->pv_proses_id'
			order by rv_amount,rrv_no_rv asc";
	$hasil4 = mysqli_query($con,$query4);
	if($hasil4 == false){
		array_push($error,mysqli_error($con));
	} else {
		while($data4 = mysqli_fetch_array($hasil4)){
			$rrv_no_rv = $data4['rrv_no_rv'];
			$rv_amount = $data4['rv_amount'];
			
			$query5 = "select pv_calculate,pv_scheme from fin_trn_payment where pv_proses_id='$dt->pv_proses_id'";
			$hasil5 = mysqli_query($con,$query5);
			if($hasil5 == false){
				array_push($error,mysqli_error($con));
			} else {
				$data5 = mysqli_fetch_array($hasil5);
				$pv_calculate = $data5['pv_calculate'];
				$selisih = $pv_calculate-$rv_amount;
				$pv_scheme = $data5['pv_scheme'];
			}
			
			if($pv_calculate > $rv_amount){
				$sql10 = "update fin_trn_rv set 
							rv_used=rv_used+'$rv_amount',
							rv_amount=rv_amount-rv_used 
						where rv_no='$rrv_no_rv'";
				$result10 = mysqli_query($con,$sql10);
				if($result10 == false){
					array_push($error,mysqli_error($con));
				}
				
				$sql11 = "update fin_trn_payment set 
							pv_calculate='$selisih' 
						where pv_proses_id='$dt->pv_proses_id'";
				$result11 = mysqli_query($con,$sql11);
				if($result11 == false){
					array_push($error,mysqli_error($con));
				}
			}
			if($pv_calculate < $rv_amount){
				$sql12 = "update fin_trn_rv set 
							rv_used=rv_used+'$pv_calculate',
							rv_scheme=rv_scheme+'$pv_scheme',
							rv_amount=rv_amount-'$pv_calculate'-'$pv_scheme' 
						where rv_no='$rrv_no_rv'";
				$result12 = mysqli_query($con,$sql12);
				if($result12 == false){
					array_push($error,mysqli_error($con));
				}
				
				$sql13 = "update fin_trn_payment set 
							pv_calculate=0 
							where pv_proses_id='$dt->pv_proses_id'";
				$result13 = mysqli_query($con,$sql13);
				if($result13 == false){
					array_push($error,mysqli_error($con));
				}
				
				/* $sql18 = "insert into fin_rv_scheme(rv_sch_spuk,rv_sch_no,rv_sch_type,rv_sch_amount) values('$pv_proses_id','$rrv_no_rv','AM','$margin')";
				$result18 = mysqli_query($con,$sql18);
				if($result18 == false){
					array_push($error,mysqli_error($con));
				}
				
				$sql19 = "insert into fin_rv_scheme(rv_sch_spuk,rv_sch_no,rv_sch_type,rv_sch_amount) values('$pv_proses_id','$rrv_no_rv','TO','$titipan_operasional')";
				$result19 = mysqli_query($con,$sql19);
				if($result19 == false){
					array_push($error,mysqli_error($con));
				} */	
					
				$query6 = "select rv_amount,rv_scheme from fin_trn_rv where rv_no='$rrv_no_rv'";
				$hasil6 = mysqli_query($con,$query6);
				if($hasil6 == false){
					array_push($error,mysqli_error($con));
				} else {
					$data6 = mysqli_fetch_array($hasil6);
					$sisa_rv = $data6['rv_amount'];
					$scheme = $data6['rv_scheme'];
					
					if($sisa_rv > 0){
						$sql14 = "update fin_trn_rv set 
									rv_status='N',
									rv_classification=NULL 
								where rv_no='$rrv_no_rv'";
						$result14 = mysqli_query($con,$sql14);
						if($result14 == false){
							array_push($error,mysqli_error($con));
						}
					}
				}
			}
		}
	}
}

//jurnal Pelunasan Unit ke FIF
$sql4 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_create_by,gl_created) values('$pv_no','$month','$year',now(),'OUT','$pv_desc','$_SESSION[outlet]','3310201','$sumAmount','$_SESSION[uid]',now())";
$result4 = mysqli_query($con,$sql4);
if($result4 == false){
	array_push($error,mysqli_error($con));
}

$sql5 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_cr,gl_create_by,gl_created) values('$pv_no','$month','$year',now(),'OUT','$pv_desc','$_SESSION[outlet]','$rekout_segment','$sumAmount','$_SESSION[uid]',now())";
$result5 = mysqli_query($con,$sql5);
if($result5 == false){
	array_push($error,mysqli_error($con));
}

//Jurnal Selisih Margin
$sql6 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_create_by,gl_created) values('$jv_no','$month','$year',now(),'JV','$pv_desc','$_SESSION[outlet]','3310201','$sumScheme','$_SESSION[uid]',now())";
$result6 = mysqli_query($con,$sql6);
if($result6 == false){
	array_push($error,mysqli_error($con));
}

$sql7 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_cr,gl_create_by,gl_created) values('$jv_no','$month','$year',now(),'JV','$pv_desc','$spuk_outlet','3310101','$sumScheme','$_SESSION[uid]',now())";
$result7 = mysqli_query($con,$sql7);
if($result7 == false){
	array_push($error,mysqli_error($con));
}
// perubahan per 2019-03-05
/* $sql8 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_cr,gl_create_by,gl_created) values('$jv_no','$month','$year',now(),'JV','$pv_desc','$spuk_outlet','6210101','$administrasi_mokas','$_SESSION[uid]',now())";
$result8 = mysqli_query($con,$sql8);
if($result8 == false){
	array_push($error,mysqli_error($con));
}

$sql9 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_cr,gl_create_by,gl_created) values('$jv_no','$month','$year',now(),'JV','$pv_desc','$_SESSION[outlet]','3220701','$hutang_ppn','$_SESSION[uid]',now())";
$result9 = mysqli_query($con,$sql9);
if($result9 == false){
	array_push($error,mysqli_error($con));
} */
// end pelunasan

$query5 = "select bankbal_date from fin_trnbank_balance where bankbal_date='$pv_paid_date' and bankbal_acctno='$pv_bank_rek'";
$hasil5 = mysqli_query($con,$query5);
if($hasil5 == false){
	array_push($error,mysqli_error($con));
} else {
	$data5 = mysqli_num_rows($hasil5);
	
	if($data5 == 0){
		$query6 = "select bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$pv_bank_rek' order by bankbal_date desc limit 0,1";
		$hasil6 = mysqli_query($con,$query6);
		if($hasil6 == false){
			array_push($error,mysqli_error($con));
		} else {
			$data6 = mysqli_fetch_array($hasil6);
			$bankbal_saldo_awal = $data6['bankbal_saldo_akhir'];
			$bankbal_saldo_akhir = $bankbal_saldo_awal-$sumAmount;
			
			$sql16 = "insert into fin_trnbank_balance(bankbal_date,bankbal_acctno,bankbal_saldo_awal,bankbal_gl_dr,bankbal_gl_cr,bankbal_saldo_akhir,bankbal_create_by,bankbal_created) values('$pv_paid_date','$pv_bank_rek','$bankbal_saldo_awal',0,'$sumAmount','$bankbal_saldo_akhir','$_SESSION[uid]',current_date())";
			$result16 = mysqli_query($con,$sql16);
			if($result16 == false){
				array_push($error,mysqli_error($con));
			}
		}
	} else {
		$sql17 = "update fin_trnbank_balance set 
				 bankbal_gl_cr=bankbal_gl_cr+'$sumAmount',
				 bankbal_saldo_akhir=bankbal_saldo_awal+bankbal_gl_dr-bankbal_gl_cr
				 where bankbal_date='$pv_paid_date' and bankbal_acctno='$pv_bank_rek'";
		$result17 = mysqli_query($con,$sql17);
		if($result17 == false){
			array_push($error,mysqli_error($con));
		}
	}
}


if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved','pv_no'=>$pv_no));
}

mysqli_close($con);
?>