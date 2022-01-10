<?php

$rv_no = htmlspecialchars($_REQUEST['rv_no']);
$rv_classification = htmlspecialchars($_REQUEST['rv_classification']);

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "update fin_trn_rv set
	rv_classification='$rv_classification',
	rv_update_by='$_SESSION[uid]',
	rv_updated=now()
	where rv_no='$rv_no'";
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