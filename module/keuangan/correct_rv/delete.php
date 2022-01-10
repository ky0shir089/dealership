<?php

$today = date('Y-m-d');
$data = json_decode($_POST['data']);
foreach($data as $dt){
	$rv_no = $dt->rv_no;
	$rv_received_date = $dt->rv_received_date;
	$rv_bank_rek = $dt->rv_bank_rek;
	$rv_amount = $dt->rv_amount;
}

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "delete from fin_trn_rv where rv_no='$rv_no'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql2 = "delete from gl_journal where gl_no='$rv_no'";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

$query = "select * from fin_trnbank_balance where bankbal_date='$rv_received_date' and bankbal_acctno='$rv_bank_rek'";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);

$sql3 = "update fin_trnbank_balance set
			bankbal_gl_dr=bankbal_gl_dr-'$rv_amount',
			bankbal_saldo_akhir=$data[bankbal_saldo_awal]+bankbal_gl_dr-$data[bankbal_gl_cr]
		where 
			bankbal_date='$data[bankbal_date]' and
			bankbal_acctno='$data[bankbal_acctno]'";
$result3 = mysqli_query($con,$sql3);
if($result3 == false){
	array_push($error,mysqli_error($con));
}

/* if($data['bankbal_date'] < $today){
	$query2 = "select * from fin_trnbank_balance where bankbal_acctno='$rv_bank_rek' and bankbal_date > '$data[bankbal_date]' order by bankbal_date asc";
	$hasil2 = mysqli_query($con,$query2);
	while($data2 = mysqli_fetch_array($hasil2)){
		$query3 = "select bankbal_saldo_akhir from fin_trnbank_balance where bankbal_acctno='$rv_bank_rek' and bankbal_date < '$data2[bankbal_date]' ORDER BY bankbal_date desc limit 0,1";
		$hasil3 = mysqli_query($con,$query3);
		$data3 = mysqli_fetch_array($hasil3);
		
		$sql4 = "update fin_trnbank_balance set
					bankbal_saldo_awal=$data3[bankbal_saldo_akhir],
					bankbal_saldo_akhir=bankbal_saldo_awal+$data2[bankbal_gl_dr]-$data2[bankbal_gl_cr]
				where 
					bankbal_date='$data2[bankbal_date]' and
					bankbal_acctno='$data2[bankbal_acctno]'";
		$result4 = mysqli_query($con,$sql4);
		if($result4 == false){
			array_push($error,mysqli_error($con));
		}
	}
} */

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'RV Deleted'));
}

mysqli_close($con);
?>