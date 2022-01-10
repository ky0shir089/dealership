<?php

$id = htmlspecialchars($_REQUEST['hidden2']);
$supl_name = htmlspecialchars(strtoupper($_REQUEST['supl_name2']));
$supl_type = htmlspecialchars($_REQUEST['supl_type2']);
$supl_status = htmlspecialchars($_REQUEST['supl_status2']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "update mst_suppliers set
	supl_name='$supl_name',
	supl_type='$supl_type',
	supl_status='$supl_status',
	supl_update_by='$_SESSION[uid]',
	supl_updated=now()
	where supl_id='$id';";

$result = mysqli_query($con,$sql);	
	
if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Updated','id'=>$id));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>