<?php

$role_id = htmlspecialchars(strtoupper($_REQUEST['role_id']));
$role_name = htmlspecialchars(strtoupper($_REQUEST['role_name']));

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into mst_roles(role_id,role_name,role_create_by,role_created) values('$role_id','$role_name','$_SESSION[uid]',now())";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>