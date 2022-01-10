<?php

$id = $_REQUEST['id'];
$wf_name = htmlspecialchars(strtoupper($_REQUEST['wf_name']));
$wf_form = htmlspecialchars($_REQUEST['wf_form']);
$wf_table = htmlspecialchars($_REQUEST['wf_table']);
$wf_status = htmlspecialchars(@$_REQUEST['wf_status'] == '' ? 'N' : 'Y');

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_workflow set 
		wf_name='$wf_name',
		wf_form='$wf_form',
		wf_table='$wf_table',
		wf_status='$wf_status',
		wf_update_by='$_SESSION[uid]',
		wf_updated=now() 
		where wf_id='$id'";
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