<?php

$user_id = htmlspecialchars($_REQUEST['user_id']);
$user_name = htmlspecialchars(strtoupper($_REQUEST['user_name']));
$user_password = md5('12345678');
$user_description = htmlspecialchars($_REQUEST['user_description'] == "" ? 'NULL' : strtoupper("'".$_REQUEST['user_description']."'"));
$user_personid = htmlspecialchars($_REQUEST['user_personid'] == "" ? 'NULL' : strtoupper("'".$_REQUEST['user_personid']."'"));
$user_outlet = htmlspecialchars($_REQUEST['user_outlet']);
//$role_id = htmlspecialchars($_REQUEST['role_id']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,false);

$sql = "insert into users(user_id,user_name,user_password,user_description,user_personid,user_outlet,user_create_by,user_created) values('$user_id','$user_name','$user_password',$user_description,$user_personid,'$user_outlet','$_SESSION[uid]',current_date());";
$result = mysqli_query($con,$sql);
//$sql .= "insert into user_roles(user_id,role_id,user_role_create_by,user_role_created) values('$user_id','$role_id','$_SESSION[uid]',current_date())";

// Execute multi query
/* if(mysqli_multi_query($con,$sql)){
	echo json_encode(array('success'=>'Data Saved'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
} */

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved','uid'=>$user_id));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>