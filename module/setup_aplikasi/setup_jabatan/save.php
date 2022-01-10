<?php

$job_id = htmlspecialchars(strtoupper($_REQUEST['job_id']));
$job_name = htmlspecialchars(strtoupper($_REQUEST['job_name']));
$job_level = htmlspecialchars($_REQUEST['job_level']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into hr_mst_job(job_id,job_name,job_level,job_create_by,job_created) values('$job_id','$job_name','$job_level','$_SESSION[uid]',now())";
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