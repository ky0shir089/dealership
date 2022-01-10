<?php

$id = $_REQUEST['id'];
$seq = substr($id,13);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "update fin_trn_inv_hdr set invhdr_status='C' where invhdr_no='$id'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
} 

$query = "select invhdr_mst_code,invhdr_reff_no from fin_trn_inv_hdr where invhdr_no='$id'";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
	$type_trx = $data['invhdr_mst_code'];
	$reff_no = $data['invhdr_reff_no'];
}

if($type_trx == 'TRX03'){
	$sql2 = "update fin_trn_rv set rv_status='N' where rv_no='$reff_no'";
	$result2 = mysqli_query($con,$sql2);
	if($result2 == false){
		array_push($error,mysqli_error($con));
	} 
} 
if($type_trx == 'TRX05'){
	$sql3 = "update fin_trn_rv set rv_status='C' where rv_no in (select used_rv_no from used_rv_promo where used_invoice_no='$id')";
	$result3 = mysqli_query($con,$sql3);
	if($result3 == false){
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
	echo json_encode(array('success'=>'Data Canceled'));
}

mysqli_close($con);
?>