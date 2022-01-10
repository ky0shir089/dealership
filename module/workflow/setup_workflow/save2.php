<?php

$id = $_REQUEST['id'];
$wf_dtl_dept = $_REQUEST['wf_dtl_dept'];
$wf_dtl_job = $_REQUEST['wf_dtl_job'];

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$query = "select count(wf_dtl_urutan) as urutan from mst_wf_detail where wf_dtl_id='$id'";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$urutan = $data['urutan']+1;

$sql = "insert into mst_wf_detail(wf_dtl_id,wf_dtl_urutan,wf_dtl_dept,wf_dtl_job,wf_dtl_create_by,wf_dtl_created) values('$id','$urutan','$wf_dtl_dept','$wf_dtl_job','$_SESSION[uid]',now())";
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