<?php

//$menu_id = htmlspecialchars(strtoupper($_REQUEST['menu_id']));
$module_id = htmlspecialchars($_REQUEST['id']);
$menu_icon = htmlspecialchars($_REQUEST['menu_icon']);
$menu_name = htmlspecialchars(ucwords($_REQUEST['menu_name']));
$menu_page = $_REQUEST['menu_page'];
$sequence = $_REQUEST['seq'];

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$query = "select substr(menu_id,4) as menu_id from mst_menus order by menu_id desc limit 0,1";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$seq = sprintf("%'.03d",$data['menu_id']+1);
$menu_id = 'FRM'.$seq;

$sql = "insert into mst_menus(menu_id,module_id,menu_icon,menu_name,menu_page,seq,menu_create_by,menu_created) values('$menu_id','$module_id','$menu_icon','$menu_name','$menu_page','$sequence','$_SESSION[uid]',now())";
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