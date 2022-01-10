<?php

$id = $_REQUEST['id'];
$scheme_status = htmlspecialchars($_REQUEST['scheme_status']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_scheme set scheme_status='$scheme_status',scheme_update_by='$_SESSION[uid]',scheme_updated=now() where scheme_id='$id'";
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