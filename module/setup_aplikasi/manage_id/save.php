<?php

$user_id = htmlspecialchars($_REQUEST['hidden']);
$user_name = htmlspecialchars(strtoupper($_REQUEST['user_name']));
$user_description = htmlspecialchars($_REQUEST['user_description'] == "" ? 'NULL' : strtoupper("'".$_REQUEST['user_description']."'"));
$chpass = @$_REQUEST['chpass'] != 'Y' ? 'N' : 'Y';
$user_enable_sts = $_REQUEST['user_enable_sts'];

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,false);

$sql = "update users set 
	user_name='$user_name',
	user_description=$user_description,
	user_chpass='$chpass',
	user_enable_sts='$user_enable_sts',
	user_update_by='$_SESSION[uid]',
	user_updated=now()
	where user_id='$user_id'";
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