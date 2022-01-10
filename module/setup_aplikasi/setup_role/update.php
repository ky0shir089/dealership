<?php

$id = $_REQUEST['id'];
$role_name = htmlspecialchars(strtoupper($_REQUEST['role_name']));

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_roles set role_name='$role_name',role_update_by='$_SESSION[uid]',role_updated=now() where role_id='$id'";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Updated'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>