<?php

$id = $_REQUEST['id'];
$req_reason = htmlspecialchars(strtoupper($_REQUEST['req_reason']));

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "update request_id set 
	req_status='R',
	req_reason='$req_reason',
	req_update_by='$_SESSION[uid]',
	req_updated=now() where req_seq='$id';";
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