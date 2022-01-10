<?php

$id = $_REQUEST['id'];
$coa_description = strtoupper($_REQUEST['coa_description']);
$coa_type = $_REQUEST['coa_type'];
$coa_parent = $_REQUEST['parent_name'] == '' ? 'NULL' : "'".$_REQUEST['coa_parent']."'";

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "update gl_coa set
	coa_description='$coa_description',
	coa_type='$coa_type',
	coa_parent=$coa_parent,
	coa_update_by='$_SESSION[uid]',
	coa_updated=now()
	where coa_code='$id'";
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