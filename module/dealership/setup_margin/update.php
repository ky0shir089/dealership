<?php

$id = $_REQUEST['id'];
$margin_amount = htmlspecialchars($_REQUEST['margin_amount']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_margin set margin_amount='$margin_amount',margin_update_by='$_SESSION[uid]',margin_updated=now() where margin_id='$id'";
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