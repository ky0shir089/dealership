<?php

$rvmst_code = htmlspecialchars($_REQUEST['rvmst_code']);
$rvmst_status = htmlspecialchars($_REQUEST['rvmst_status']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "update fin_mst_rvhdr set
			rvmst_status='$rvmst_status',
			rvmst_update_by='$_SESSION[uid]',
			rvmst_updated=current_date()
		where rvmst_code='$rvmst_code'";
//die($sql);
$result = mysqli_query($con,$sql);

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Updated'));
}

mysqli_close($con);
?>