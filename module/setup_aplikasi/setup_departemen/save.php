<?php

$dept_id = htmlspecialchars(strtoupper($_REQUEST['dept_id']));
$dept_name = htmlspecialchars(strtoupper($_REQUEST['dept_name']));
$dept_parent_id = htmlspecialchars($_REQUEST['dept_parent_id'] == "" ? 'NUL' : "'".$_REQUEST['dept_parent_id']."'");

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into hr_dept_all(dept_id,dept_name,dept_parent_id,dept_create_by,dept_created) values('$dept_id','$dept_name',$dept_parent_id,'$_SESSION[uid]',now())";
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