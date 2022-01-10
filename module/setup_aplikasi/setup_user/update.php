<?php

$user_id = htmlspecialchars($_REQUEST['hidden']);
$user_name = htmlspecialchars(strtoupper($_REQUEST['user_name']));
$chpass = htmlspecialchars(@$_REQUEST['chpass']);
$user_password = htmlspecialchars($_REQUEST['user_password']);
$user_description = htmlspecialchars($_REQUEST['user_description'] == "" ? 'NULL' : strtoupper("'".$_REQUEST['user_description']."'"));
$user_enable_sts = @$_REQUEST['user_enable_sts'] != 'Y' ? 'N' : 'Y';
$user_outlet = htmlspecialchars($_REQUEST['user_outlet']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

if($chpass == 'Y'){
	$user_password = md5('12345678');
	$user_chpass = 'Y';
} else {
	$user_password = $user_password;
	$user_chpass = 'N';
}

$sql = "update users set
	user_name='$user_name',
	user_password='$user_password',
	user_description=$user_description,
	user_enable_sts='$user_enable_sts',
	user_outlet='$user_outlet',
	user_chpass='$user_chpass',
	user_update_by='$_SESSION[uid]',
	user_updated=now()
	where user_id='$user_id'";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Updated','uid'=>$user_id));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>