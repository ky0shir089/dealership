<?php

$rekout_no = htmlspecialchars($_REQUEST['rekout_no']);
$rekout_id = htmlspecialchars($_REQUEST['rekout_id']);
$rekout_name = htmlspecialchars(strtoupper($_REQUEST['rekout_name']));
$rekout_outlet = htmlspecialchars($_REQUEST['rekout_outlet']);
$rekout_segment = htmlspecialchars($_REQUEST['rekout_segment']);
$rekout_status = htmlspecialchars(@$_REQUEST['rekout_status'] == 'Y' ? 'Y' : 'N');

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "update mst_rekening_outlet set
		rekout_id='$rekout_id',
		rekout_name='$rekout_name',
		rekout_outlet='$rekout_outlet',
		rekout_segment='$rekout_segment',
		rekout_status='$rekout_status',
		rekout_update_by='$_SESSION[uid]',
		rekout_updated=now()
	where rekout_no='$rekout_no'";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Updated','rekout_no'=>$rekout_no));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>