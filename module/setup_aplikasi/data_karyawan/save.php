<?php

$person_id = htmlspecialchars($_REQUEST['person_id']);
$person_name = htmlspecialchars(strtoupper($_REQUEST['person_name']));
$person_dept = htmlspecialchars($_REQUEST['person_dept']);
$person_job = htmlspecialchars($_REQUEST['person_job']);
$person_outlet = htmlspecialchars($_REQUEST['person_outlet']);

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "insert into hr_people_all(person_id,person_name,person_dept,person_job,person_outlet,person_create_by,person_created) values('$person_id','$person_name','$person_dept','$person_job','$person_outlet','$_SESSION[uid]',now())";
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