<?php

$id = intval($_REQUEST['rek_id']);
$rek_bank = htmlspecialchars($_REQUEST['rek_bank']);
$rek_no = htmlspecialchars($_REQUEST['rek_no']);
$rek_name = htmlspecialchars(strtoupper($_REQUEST['rek_name']));

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_rekening set
	rek_bank='$rek_bank',
	rek_no='$rek_no',
	rek_name='$rek_name',
	rek_update_by='$_SESSION[uid]',
	rek_updated=now()
	where rek_id='$id'";
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