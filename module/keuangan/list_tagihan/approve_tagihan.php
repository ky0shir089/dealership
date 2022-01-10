<?php

$invhdr_mst_code = htmlspecialchars($_REQUEST['invhdr_mst_code']);
$supl_id = htmlspecialchars($_REQUEST['supl_id']);
$invhdr_rek_no = htmlspecialchars($_REQUEST['invhdr_rek_no']);
$invhdr_no = htmlspecialchars($_REQUEST['invhdr_no']);
$outlet = substr($invhdr_no,0,5);
$invhdr_amount = htmlspecialchars($_REQUEST['invhdr_amount']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "update fin_trn_inv_hdr set
			invhdr_status='A'
		where invhdr_no='$invhdr_no'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql2 = "insert into fin_trn_payment(pv_paid_to,pv_paid_rek,pv_proses_id,pv_outlet,pv_amount,pv_calculate,type_trx) values('$supl_id','$invhdr_rek_no','$invhdr_no','$outlet','$invhdr_amount','$invhdr_amount','$invhdr_mst_code')";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Approved'));
}

mysqli_close($con);
?>