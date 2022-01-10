<?php

$coa_code = $_REQUEST['coa_code'];
$coa_description = strtoupper($_REQUEST['coa_description']);
$coa_type = $_REQUEST['coa_type'];
$coa_parent = $_REQUEST['coa_parent'] == '' ? 'NULL' : "'".$_REQUEST['coa_parent']."'";

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "insert into gl_coa(coa_code,coa_description,coa_type,coa_parent,coa_create_by,coa_created) values('$coa_code','$coa_description','$coa_type',$coa_parent,'$_SESSION[uid]',now())";
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