<?php

$id = $_REQUEST['id'];
$module_name = htmlspecialchars(strtoupper($_REQUEST['module_name']));

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_modules set module_name='$module_name',module_update_by='$_SESSION[uid]',module_updated=now() where module_id='$id'";
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