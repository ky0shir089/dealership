<?php

$id = intval($_REQUEST['id']);

include '../../../conn2.php';
session_start();

mysqli_autocommit($con,false);

$sql = "delete from user_roles where user_role_id='$id'";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Deleted'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}
?>