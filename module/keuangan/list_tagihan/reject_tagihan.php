<?php

$invhdr_no = htmlspecialchars($_REQUEST['invhdr_no']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "update fin_trn_inv_hdr set
			invhdr_status='J'
		where invhdr_no='$invhdr_no'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Rejected'));
}

mysqli_close($con);
?>