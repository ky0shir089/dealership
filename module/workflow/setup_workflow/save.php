<?php

$wf_id = htmlspecialchars($_REQUEST['wf_id']);
$wf_name = htmlspecialchars(strtoupper($_REQUEST['wf_name']));
$wf_form = htmlspecialchars($_REQUEST['wf_form']);
//$wf_table = htmlspecialchars($_REQUEST['wf_table']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into mst_workflow(wf_id,wf_name,wf_form,wf_status,wf_create_by,wf_created) values('$wf_id','$wf_name','$wf_form','Y','$_SESSION[uid]',now())";
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