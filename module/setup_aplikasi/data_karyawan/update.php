<?php

$id = htmlspecialchars($_REQUEST['hidden']);
$person_name = htmlspecialchars(strtoupper($_REQUEST['person_name']));
$person_dept = htmlspecialchars($_REQUEST['person_dept']);
$person_job = htmlspecialchars($_REQUEST['person_job']);
$person_outlet = htmlspecialchars($_REQUEST['person_outlet']);

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "update hr_people_all set
	person_name='$person_name',
	person_dept='$person_dept',
	person_job='$person_job',
	person_outlet='$person_outlet',
	person_update_by='$_SESSION[uid]',
	person_updated=now()
	where person_id='$id'";
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