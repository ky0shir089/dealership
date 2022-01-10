<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$spuk_id = $_REQUEST['spuk_id'];
$supl_id = $_REQUEST['supl_id'];

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "update spuk_hdr set 
		spuk_cust='$supl_id'
		where spuk_id='$spuk_id'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
} 

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Reloaded','spuk_id'=>$spuk_id));
}

mysqli_close($con);
?>