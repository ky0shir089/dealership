<?php

$rv_received_date = $_REQUEST['rv_received_date'];
$rv_mst_code = htmlspecialchars($_REQUEST['rv_mst_code']);
$rv_received_from = htmlspecialchars(strtoupper($_REQUEST['rv_received_from']));
$rv_bank_rek = htmlspecialchars($_REQUEST['rv_bank_rek']);
$rekout_segment = htmlspecialchars($_REQUEST['rekout_segment']);
$rv_segment2 = htmlspecialchars($_REQUEST['rv_segment2']);
$rv_amount = htmlspecialchars($_REQUEST['rv_start']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

//sequence
$query = "select * from seq_rv where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
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
	$rv_no = $_SESSION['outlet'].date('y').'RVD'.$seq;
	$year = date('y');
	$month = strtoupper(date('M'));
}

$sql2 = "insert into seq_rv(outlet,year,seq) values('$_SESSION[outlet]','$year','$seq')";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

$sql = "insert into fin_trn_rv(rv_no,rv_mst_code,rv_received_from,rv_received_date,rv_pay_method,rv_bank_rek,rv_segment1,rv_segment2,rv_start,
rv_amount,rv_create_by,rv_created) values('$rv_no','$rv_mst_code','$rv_received_from','$rv_received_date','B','$rv_bank_rek','$_SESSION[outlet]','$rv_segment2','$rv_amount','$rv_amount','$_SESSION[uid]',now())";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql3 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_create_by,gl_created) values('$rv_no','$month','$year','$rv_received_date','IN','$rv_received_from','$_SESSION[outlet]','$rekout_segment','$rv_amount','$_SESSION[uid]',now())";
$result3 = mysqli_query($con,$sql3);
if($result3 == false){
	array_push($error,mysqli_error($con));
}

$sql4 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_cr,gl_create_by,gl_created) values('$rv_no','$month','$year','$rv_received_date','IN','$rv_received_from','$_SESSION[outlet]','$rv_segment2','$rv_amount','$_SESSION[uid]',now())";
$result4 = mysqli_query($con,$sql4);
if($result4 == false){
	array_push($error,mysqli_error($con));
}

$query2 = "select bankbal_date from fin_trnbank_balance where bankbal_date='$rv_received_date' and bankbal_acctno='$rv_bank_rek'";
$hasil2 = mysqli_query($con,$query2);
if($hasil2 == false){
	array_push($error,mysqli_error($con));
} else {
	$data2 = mysqli_num_rows($hasil2);
	
	if($data2 == 0){
		$query3 = "select bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$rv_bank_rek' and bankbal_date < '$rv_received_date' order by bankbal_date desc limit 0,1";
		$hasil3 = mysqli_query($con,$query3);
		if($hasil3 == false){
			array_push($error,mysqli_error($con));
		} else {
			$data3 = mysqli_fetch_array($hasil3);
			$bankbal_saldo_awal = $data3['bankbal_saldo_akhir'];
			$bankbal_saldo_akhir = $bankbal_saldo_awal+$rv_amount;
			
			$sql5 = "insert into fin_trnbank_balance(bankbal_date,bankbal_acctno,bankbal_saldo_awal,bankbal_gl_dr,bankbal_gl_cr,bankbal_saldo_akhir,bankbal_create_by,bankbal_created) values('$rv_received_date','$rv_bank_rek','$bankbal_saldo_awal','$rv_amount',0,'$bankbal_saldo_akhir','$_SESSION[uid]',current_date())";
			$result5 = mysqli_query($con,$sql5);
			if($result5 == false){
				array_push($error,mysqli_error($con));
			}
		}
	} else {
		$sql6 = "update fin_trnbank_balance set 
			 bankbal_gl_dr=bankbal_gl_dr+'$rv_amount',
			 bankbal_saldo_akhir=bankbal_saldo_awal+bankbal_gl_dr-bankbal_gl_cr
			 where bankbal_date='$rv_received_date' and bankbal_acctno='$rv_bank_rek'";
		$result6 = mysqli_query($con,$sql6);
		if($result6 == false){
			array_push($error,mysqli_error($con));
		}
		
		$query5 = "select bankbal_date,bankbal_gl_dr,bankbal_gl_cr,bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$rv_bank_rek' and bankbal_date > '$rv_received_date' order by bankbal_date asc";
		$hasil5 = mysqli_query($con,$query5);
		while($data5 = mysqli_fetch_array($hasil5)){
			$query6 = "select bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$rv_bank_rek' and bankbal_date < '$data5[bankbal_date]' ORDER BY bankbal_date desc limit 0,1";
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
				 where bankbal_date='$data5[bankbal_date]' and bankbal_acctno='$rv_bank_rek'";
			$result7 = mysqli_query($con,$sql7);
			if($result7 == false){
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
	echo json_encode(array('success'=>'Data Saved','rv_no'=>$rv_no));
}

mysqli_close($con);
?>