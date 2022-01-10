<?php

$wf_dtl_no = $_REQUEST['wf_dtl_no'];
$wf_dtl_dept = $_REQUEST['wf_dtl_dept'];
$wf_dtl_job = $_REQUEST['wf_dtl_job'];

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_wf_detail set 
		wf_dtl_dept='$wf_dtl_dept',
		wf_dtl_job='$wf_dtl_job',
		wf_dtl_update_by='$_SESSION[uid]',
		wf_dtl_updated=now() 
		where wf_dtl_no='$wf_dtl_no'";
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