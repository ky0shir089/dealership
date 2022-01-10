<?php

$id = $_REQUEST['id'];
$seq = substr($id,13);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "update unit_titip_jual set utj_status='N' where utj_id IN (select spuk_dtl_utj from spuk_dtl where spuk_dtl_id='$id')";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
} 

$sql2 = "delete from spuk_dtl where spuk_dtl_id='$id'";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

$sql3 = "delete from spuk_hdr where spuk_id='$id'";
$result3 = mysqli_query($con,$sql3);
if($result3 == false){
	array_push($error,mysqli_error($con));
} 

$sql4 = "delete from seq_spuk where seq='$seq' and outlet='$_SESSION[outlet]'";
$result4 = mysqli_query($con,$sql4);
if($result4 == false){
	array_push($error,mysqli_error($con));
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