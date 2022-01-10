<?php

$pv_paid_date = htmlspecialchars($_REQUEST['pv_paid_date']);
$pv_desc = htmlspecialchars(strtoupper($_REQUEST['pv_desc']));
$pv_bank_rek = htmlspecialchars($_REQUEST['pv_bank_rek']);
$rekout_segment = htmlspecialchars($_REQUEST['rekout_segment']);
$data2 = json_decode($_POST['data2']);

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
	$data = mysqli_fetch_array($hasil);
	$tahun = $data['year'];
	if($tahun < year){
		$seq = '0000001';
	} else {
		$seq = sprintf("%'.07d",$data['seq']+1);
	}
	$pv_no = $_SESSION['outlet'].date('y').'PVD'.$seq;
	$year = date('y');
	$month = strtoupper(date('M'));
}

$sql2 = "insert into seq_pv(outlet,year,seq) values('$_SESSION[outlet]','$year','$seq')";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

foreach($data2 as $dt){
	$pv_proses_id = $dt->pv_proses_id;
	$pv_amount = $dt->pv_amount;
	$type_trx = $dt->type_trx;
	
	$sql = "update fin_trn_payment set
				pv_no='$pv_no',
				pv_desc='$pv_desc',
				pv_bank_rek='$pv_bank_rek',
				pv_paid_status='P',
				pv_paid_by='$_SESSION[uid]',
				pv_paid_date='$pv_paid_date',
				pv_segment1='$_SESSION[outlet]',
				pv_segment2='$rekout_segment'
			where pv_proses_id='$pv_proses_id'";
	$result = mysqli_query($con,$sql);
	if($result == false){
		array_push($error,mysqli_error($con));
	}

	$query4 = "select invhdr_segment2 from fin_trn_inv_hdr where invhdr_no='$pv_proses_id'";
	$hasil4 = mysqli_query($con,$query4);
	if($hasil4 == false){
		array_push($error,mysqli_error($con));
	} else {
		$data4 = mysqli_fetch_array($hasil4);
		$invhdr_segment2 = $data4['invhdr_segment2'];
	}

	if($type_trx!='TRX05'){
		$query2 = "select
					invhdr_reff_no,
					rv_amount
				from fin_trn_inv_hdr a
				join fin_trn_rv b on a.invhdr_reff_no=b.rv_no
				where invhdr_no='$pv_proses_id'";
		$hasil2 = mysqli_query($con,$query2);
		if($hasil2 == false){
			array_push($error,mysqli_error($con));
		} else {
			$data2 = mysqli_fetch_array($hasil2);
			$invhdr_reff_no = $data2['invhdr_reff_no'];
			$rv_amount = $data2['rv_amount'];
		}

		$sql3 = "update fin_trn_inv_hdr set
					invhdr_status='P',
					invhdr_payment_no='$pv_no',
					invhdr_payment_date=now()
				where invhdr_no='$pv_proses_id'";
		$result3 = mysqli_query($con,$sql3);
		if($result3 == false){
			array_push($error,mysqli_error($con));
		}

		$sql4 = "update fin_trn_rv set
					rv_amount=rv_amount-'$pv_amount',
					rv_status='N'
				where rv_no='$invhdr_reff_no'";
		$result4 = mysqli_query($con,$sql4);
		if($result4 == false){
			array_push($error,mysqli_error($con));
		}

		if($rv_amount == $pv_amount){
			$sql5 = "update fin_trn_rv set
						rv_status='C'
					where rv_no='$invhdr_reff_no'";
			$result5 = mysqli_query($con,$sql5);
			if($result5 == false){
				array_push($error,mysqli_error($con));
			}
		}
	} else {
		$query3 = "select
					used_rv_no,
					used_rv_amount,
				from used_rv_promo
				where invhdr_no='$pv_proses_id'
				order by used_rv_amount asc";
		$hasil3 = mysqli_query($con,$query3);
		if($hasil3 == false){
			array_push($error,mysqli_error($con));
		} else {
			while($data3 = mysqli_fetch_array($hasil3)){
				$used_rv_no = $data3['used_rv_no'];
				$used_rv_amount = $data3['used_rv_amount'];
				
				$query4 = "select pv_calculate from fin_trn_payment where pv_proses_id='$pv_proses_id'";
				$hasil4 = mysqli_query($con,$query4);
				if($hasil4 == false){
					array_push($error,mysqli_error($con));
				} else {
					$data4 = mysqli_fetch_array($hasil4);
					$pv_calculate = $data4['pv_calculate'];
					
					if($pv_calculate > $used_rv_amount){
						$sql6 = "update fin_trn_rv set
									rv_scheme=rv_scheme-'$used_rv_amount',
									rv_status='C'
								where rv_no='$used_rv_no'";
						$result6 = mysqli_query($con,$sql6);
						if($result6 == false){
							array_push($error,mysqli_error($con));
						}
						
						$sql7 = "update fin_trn_payment set
									pv_calculate=pv_calculate-'$used_rv_amount'
								where pv_proses_id='$pv_proses_id'";
						$result7 = mysqli_query($con,$sql7);
						if($result7 == false){
							array_push($error,mysqli_error($con));
						}
					}
					if($pv_calculate <= $used_rv_amount){
						$sql8 = "update fin_trn_rv set
									rv_scheme=rv_scheme-'$pv_calculate',
									rv_status='C'
								where rv_no='$used_rv_no'";
						$result8 = mysqli_query($con,$sql8);
						if($result8 == false){
							array_push($error,mysqli_error($con));
						}
						
						$sql9 = "update fin_trn_payment set
									pv_calculate=0
								where pv_proses_id='$pv_proses_id'";
						$result9 = mysqli_query($con,$sql9);
						if($result9 == false){
							array_push($error,mysqli_error($con));
						}
						
						$sql10 = "update fin_trn_inv_hdr set
									invhdr_status='P'
								where invhdr_no='$pv_proses_id'";
						$result10 = mysqli_query($con,$sql10);
						if($result9 == false){
							array_push($error,mysqli_error($con));
						}
					}
				}
			}
			
		}
	}

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
				$bankbal_saldo_akhir = $bankbal_saldo_awal-$pv_amount;
				
				$sql16 = "insert into fin_trnbank_balance(bankbal_date,bankbal_acctno,bankbal_saldo_awal,bankbal_gl_dr,bankbal_gl_cr,bankbal_saldo_akhir,bankbal_create_by,bankbal_created) values('$pv_paid_date','$pv_bank_rek','$bankbal_saldo_awal',0,'$pv_amount','$bankbal_saldo_akhir','$_SESSION[uid]',current_date())";
				$result16 = mysqli_query($con,$sql16);
				if($result16 == false){
					array_push($error,mysqli_error($con));
				}
			}
		} else {
			$sql17 = "update fin_trnbank_balance set 
					 bankbal_gl_cr=bankbal_gl_cr+'$pv_amount',
					 bankbal_saldo_akhir=bankbal_saldo_awal+bankbal_gl_dr-bankbal_gl_cr
					 where bankbal_date='$pv_paid_date' and bankbal_acctno='$pv_bank_rek'";
			$result17 = mysqli_query($con,$sql17);
			if($result17 == false){
				array_push($error,mysqli_error($con));
			}
		}
	}
	
	//jurnal 
	$sql6 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_create_by,gl_created) values('$pv_no','$month','$year',now(),'OUT','$pv_desc','$_SESSION[outlet]','$invhdr_segment2','$pv_amount','$_SESSION[uid]',now())";
	$result6 = mysqli_query($con,$sql6);
	if($result6 == false){
		array_push($error,mysqli_error($con));
	}

	$sql7 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_cr,gl_create_by,gl_created) values('$pv_no','$month','$year',now(),'OUT','$pv_desc','$_SESSION[outlet]','$rekout_segment','$pv_amount','$_SESSION[uid]',now())";
	$result7 = mysqli_query($con,$sql7);
	if($result7 == false){
		array_push($error,mysqli_error($con));
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