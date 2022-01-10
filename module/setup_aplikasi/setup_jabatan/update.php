<?php

$id = $_REQUEST['id'];
$job_name = htmlspecialchars(strtoupper($_REQUEST['job_name']));
$job_level = htmlspecialchars($_REQUEST['job_level']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update hr_mst_job set 
			job_name='$job_name',
			job_level='$job_level',
			job_update_by='$_SESSION[uid]',
			job_updated=now() 
		where job_id='$id'";
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