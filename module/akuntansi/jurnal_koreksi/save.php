<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$gl_date = $_REQUEST['gl_date'];
$gl_desc = strtoupper($_REQUEST['gl_desc']);
$gl_month = strtoupper(date('M',strtotime($gl_date)));
$gl_year = date('y',strtotime($gl_date));
/* $kode_titik = $_REQUEST['kode_titik'];
$coa_code = $_REQUEST['coa_code'];
$invhdr_reff_no = $_REQUEST['invhdr_reff_no'];
$invhdr_reff_amount = @$_REQUEST['invhdr_reff_amount'];
$gl_dr = $_REQUEST['gl_dr'];
$gl_cr = $_REQUEST['gl_cr'];
$kode_titik2 = $_REQUEST['kode_titik2'];
$coa_code2 = $_REQUEST['coa_code2'];
$invhdr_reff_no2 = $_REQUEST['invhdr_reff_no2'];
$invhdr_reff_amount2 = @$_REQUEST['invhdr_reff_amount2'];
$gl_dr2 = $_REQUEST['gl_dr2'];
$gl_cr2 = $_REQUEST['gl_cr2'];
$jc_type = $_REQUEST['jc_type'];
$jc_type2 = $_REQUEST['jc_type2']; */
$data = json_decode($_POST['data']);

mysqli_autocommit($con,FALSE);

$error = array();

//sequence
$query = "select * from seq_jc where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
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
	$jc_no = $_SESSION['outlet'].date('y').'JCD'.$seq;
	$year = date('y');
	$month = strtoupper(date('M'));
}
$sql = "insert into seq_jc(outlet,year,seq) values('$_SESSION[outlet]','$year','$seq')";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

foreach($data as $dt){
	$sql3 = "insert into gl_journal(
				gl_no,
				gl_period_month,
				gl_period_year,
				gl_date,
				gl_type,
				gl_desc,
				gl_segment1,
				gl_segment2,
				gl_dr,
				gl_cr,
				gl_create_by,
				gl_created) 
			values(
				'$jc_no',
				'$gl_month',
				'$gl_year',
				'$gl_date',
				'JC',
				'$gl_desc',
				'$dt->kode_titik',
				'$dt->coa_code',
				'$dt->gl_dr',
				'$dt->gl_cr',
				'$_SESSION[uid]',
				now())";
	$result3 = mysqli_query($con,$sql3);
	if($result3 == false){
		array_push($error,mysqli_error($con));
	}
	
	if($dt->jc_type == 'BANK'){
		$coa = $dt->coa_code; 
		$rv_amount = $dt->gl_dr;
		$pv_amount = $dt->gl_cr;
	
		$query4 = "select 
						rekout_no
					from mst_rekening_outlet
					where rekout_segment='$coa'";
		$hasil4 = mysqli_query($con,$query4);
		if($hasil4 == false){
			array_push($error,mysqli_error($con));
		} else {	
			$data4 = mysqli_fetch_array($hasil4);
			
			$query2 = "select bankbal_date from fin_trnbank_balance where bankbal_date='$gl_date' and bankbal_acctno='$data4[rekout_no]'";
			$hasil2 = mysqli_query($con,$query2);
			if($hasil2 == false){
				array_push($error,mysqli_error($con));
			} else {
				$data2 = mysqli_num_rows($hasil2);
				
				if($data2 == 0){
						
					$query3 = "select bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$data4[rekout_no]' order by bankbal_date desc limit 0,1";
					$hasil3 = mysqli_query($con,$query3);
					if($hasil3 == false){
						array_push($error,mysqli_error($con));
					} else {
						$data3 = mysqli_fetch_array($hasil3);
						$bankbal_saldo_awal = $data3['bankbal_saldo_akhir'];
						$bankbal_saldo_akhir = $bankbal_saldo_awal+$rv_amount-$pv_amount;
						
						$sql5 = "insert into fin_trnbank_balance(bankbal_date,bankbal_acctno,bankbal_saldo_awal,bankbal_gl_dr,bankbal_gl_cr,bankbal_saldo_akhir,bankbal_create_by,bankbal_created) values('$gl_date','$data4[rekout_no]','$bankbal_saldo_awal','$rv_amount','$pv_amount','$bankbal_saldo_akhir','$_SESSION[uid]',current_date())";
						$result5 = mysqli_query($con,$sql5);
						if($result5 == false){
							array_push($error,mysqli_error($con));
						}
					}
				} else {
					$sql6 = "update fin_trnbank_balance set 
						 bankbal_gl_dr=bankbal_gl_dr+'$rv_amount',
						 bankbal_gl_cr=bankbal_gl_cr+'$pv_amount',
						 bankbal_saldo_akhir=bankbal_saldo_awal+bankbal_gl_dr-bankbal_gl_cr
						 where bankbal_date='$gl_date' and bankbal_acctno='$data4[rekout_no]'";
					$result6 = mysqli_query($con,$sql6);
					if($result6 == false){
						array_push($error,mysqli_error($con));
					}
					
					$query5 = "select bankbal_date,bankbal_gl_dr,bankbal_gl_cr,bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$data4[rekout_no]' order by bankbal_date asc";
					$hasil5 = mysqli_query($con,$query5);
					while($data5 = mysqli_fetch_array($hasil5)){
						$query6 = "select bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$data4[rekout_no]' and bankbal_date < '$data5[bankbal_date]' ORDER BY bankbal_date desc limit 0,1";
						$hasil6 = mysqli_query($con,$query6);
						$data6 = mysqli_fetch_array($hasil6);
						$found = mysqli_num_rows($hasil6);
						
						if($found == 0){
							$saldo_awal = $data5['bankbal_saldo_akhir'];
						} else {
							$saldo_awal = $data6['bankbal_saldo_akhir'];
						}
						
						$saldo_akhir = $saldo_awal+$data5['bankbal_gl_dr']-$data5['bankbal_gl_cr'];
						
						$sql7 = "update fin_trnbank_balance set
							 bankbal_saldo_awal='$saldo_awal',
							 bankbal_saldo_akhir='$saldo_awal'+$data5[bankbal_gl_dr]-$data5[bankbal_gl_cr]
							 where bankbal_date='$data5[bankbal_date]' and bankbal_acctno='$data4[rekout_no]'";
						$result7 = mysqli_query($con,$sql7);
						if($result7 == false){
							array_push($error,mysqli_error($con));
						}
					}
				}
			}
		}
	}
	
	if($dt->invhdr_reff_no != ''){
		$query7 = "select rv_start,rv_amount from fin_trn_rv where rv_no='$dt->invhdr_reff_no'";
		$hasil7 = mysqli_query($con,$query7);
		$data7 = mysqli_fetch_array($hasil7);
		$check1 = $data7['rv_start'];
		$check2 = $data7['rv_amount'];
		
		$sql8 = "insert into fin_trn_correct(corr_no,corr_reff_no,corr_reff_amount) values('$jc_no','$dt->invhdr_reff_no','$data7[rv_amount]')";
		$result8 = mysqli_query($con,$sql8);
		if($result8 == false){
			array_push($error,mysqli_error($con));
		}
		
		$sql9 = "update fin_trn_rv set
				rv_used=rv_used+$dt->gl_dr+$dt->gl_cr,
				rv_amount=rv_amount-rv_used
				where rv_no='$dt->invhdr_reff_no'";
		$result9 = mysqli_query($con,$sql9);
		if($result9 == false){
			array_push($error,mysqli_error($con));
		}
		
		if($check2 == $check1){
			$sql10 = "update fin_trn_rv set
					rv_status='C'
					where rv_no='$dt->invhdr_reff_no'";
			$result10 = mysqli_query($con,$sql10);
			if($result10 == false){
				array_push($error,mysqli_error($con));
			}
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
	echo json_encode(array('success'=>'Data Saved','jc_no'=>$jc_no,'create_by'=>$_SESSION['uid']));
}

mysqli_close($con);
?>