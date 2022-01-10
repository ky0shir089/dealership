<?php

$rekout_id = htmlspecialchars($_REQUEST['rekout_id']);
$rekout_no = htmlspecialchars($_REQUEST['rekout_no']);
$rekout_name = htmlspecialchars(strtoupper($_REQUEST['rekout_name']));
$rekout_outlet = htmlspecialchars($_REQUEST['rekout_outlet']);
$rekout_segment = htmlspecialchars($_REQUEST['rekout_segment']);

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "insert into mst_rekening_outlet(rekout_id,rekout_no,rekout_name,rekout_outlet,rekout_segment,rekout_create_by,rekout_created) values('$rekout_id','$rekout_no','$rekout_name','$rekout_outlet','$rekout_segment','$_SESSION[uid]',now())";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved','rekout_no'=>$rekout_no));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>