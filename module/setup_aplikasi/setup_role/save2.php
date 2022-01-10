<?php

$role_id = htmlspecialchars(strtoupper($_REQUEST['role_id']));
//$menu_id = htmlspecialchars(strtoupper($_REQUEST['menu_id']));
$data = json_decode($_POST['data']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

foreach($data as $dt){
	$sql = "insert into mst_rolemenus(role_id,menu_id,rolemenu_create_by,rolemenu_created) values('$role_id','$dt->menu_id','$_SESSION[uid]',now())";
	$result = mysqli_query($con,$sql);
}

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>