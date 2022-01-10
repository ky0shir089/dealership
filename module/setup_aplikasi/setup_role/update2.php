<?php

$menu_id = htmlspecialchars($_REQUEST['menu_id']);
$module_id = htmlspecialchars($_REQUEST['module_id']);
$menu_icon = htmlspecialchars($_REQUEST['menu_icon']);
$menu_name = htmlspecialchars(ucwords($_REQUEST['menu_name']));
$menu_page = $_REQUEST['menu_page'];
$is_aktif = htmlspecialchars($_REQUEST['is_aktif']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_menus set 
		module_id='$module_id',
		menu_icon='$menu_icon',
		menu_name='$menu_name',
		menu_page='$menu_page',
		is_aktif='$is_aktif',
		menu_update_by='$_SESSION[uid]',
		menu_updated=now()
		where menu_id='$menu_id'";
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