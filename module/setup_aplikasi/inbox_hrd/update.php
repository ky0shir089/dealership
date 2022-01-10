<?php

$id = $_REQUEST['id'];
$req_nama = htmlspecialchars(strtoupper($_REQUEST['req_nama']));
$req_outlet = htmlspecialchars($_REQUEST['req_outlet']);
$req_id = htmlspecialchars($_REQUEST['req_id']);
$person_dept = htmlspecialchars($_REQUEST['person_dept']);
$person_job = htmlspecialchars($_REQUEST['person_job']);

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "insert into hr_people_all(person_id,person_name,person_dept,person_job,person_outlet,person_create_by,person_created) values('$req_id','$req_nama','$person_dept','$person_job','$req_outlet','$_SESSION[uid]',now());";
$sql .= "update request_id set req_id='$req_id',req_status='A',req_update_by='$_SESSION[uid]',req_updated=now() where req_seq='$id';";
$sql .= "insert into users(user_id,user_name,user_password,user_enable_sts,user_personid,user_outlet,user_create_by,user_created) values('$req_id','$req_nama',md5('12345678'),'N','$req_id','$req_outlet','$_SESSION[uid]',now());";
$sql .= "insert into user_roles(user_id,role_id,user_role_create_by,user_role_created) values('$req_id','ROL003','$_SESSION[uid]',now())";

// Execute multi query
if(mysqli_multi_query($con,$sql)){
	echo json_encode(array('success'=>'Data Saved'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
}

mysqli_close($con);
?>