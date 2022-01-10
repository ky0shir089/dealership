<?php

$id = $_REQUEST['id'];
$menu_id = htmlspecialchars(strtoupper($_REQUEST['menu_id']));
$status = htmlspecialchars($_REQUEST['status']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_rolemenus set rolemenu_sts=$status,rolemenu_update_by='$_SESSION[uid]',rolemenu_updated=now() where role_id='$id' and menu_id='$menu_id'";
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