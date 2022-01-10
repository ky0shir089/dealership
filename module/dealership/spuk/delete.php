<?php

$id = $_REQUEST['id'];

include '../../../conn2.php';

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "delete from spuk_dtl where spuk_dtl_utj='$id'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
} 

$sql2 = "update unit_titip_jual set utj_status='N' where utj_id='$id'";
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
	echo json_encode(array('success'=>'Data Deleted'));
}
?>