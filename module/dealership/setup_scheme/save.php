<?php

$scheme_id = htmlspecialchars($_REQUEST['scheme_id']);
$scheme_amount = htmlspecialchars($_REQUEST['scheme_amount']);
$scheme_status = htmlspecialchars($_REQUEST['scheme_status']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into mst_scheme(scheme_id,scheme_amount,scheme_status,scheme_create_by,scheme_created) values('$scheme_id','$scheme_amount','$scheme_status','$_SESSION[uid]',now())";
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