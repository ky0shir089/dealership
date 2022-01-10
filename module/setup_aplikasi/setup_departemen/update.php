<?php

$id = $_REQUEST['id'];
$dept_name = htmlspecialchars(strtoupper($_REQUEST['dept_name']));
$dept_parent_id = htmlspecialchars($_REQUEST['dept_parent_id'] == "" ? 'NUL' : "'".$_REQUEST['dept_parent_id']."'");

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update hr_dept_all set 
	dept_name='$dept_name',
	dept_parent_id=$dept_parent_id,
	dept_update_by='$_SESSION[uid]',
	dept_updated=now() 
	where dept_id='$id'";
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