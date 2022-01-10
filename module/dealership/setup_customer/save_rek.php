<?php

$rek_cs = htmlspecialchars($_REQUEST['id']);
$rek_bank = htmlspecialchars($_REQUEST['rek_bank']);
$rek_no = htmlspecialchars($_REQUEST['rek_no']);
$rek_name = htmlspecialchars(strtoupper($_REQUEST['rek_name']));

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into mst_rekening(rek_cs,rek_bank,rek_no,rek_name,rek_create_by,rek_created) values('$rek_cs','$rek_bank','$rek_no','$rek_name','$_SESSION[uid]',now())";
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