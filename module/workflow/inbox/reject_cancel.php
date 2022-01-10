<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$cancel_id = $_REQUEST['cancel_id'];

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select wf_hist_id,count(no_proses) as seq from wf_history where no_proses='$cancel_id' and wf_hist_status='A'";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
	$wf_id = $data['wf_hist_id'];
	$seq = $data['seq']+1;
}

$query2 = "select count(wf_dtl_urutan) as urutan from mst_wf_detail where wf_dtl_id='$wf_id'";
$hasil2 = mysqli_query($con,$query2);
if($hasil2 == false){
	array_push($error,mysqli_error($con));
} else {
	$data2 = mysqli_fetch_array($hasil2);
	$urutan = $data2['urutan'];
}

$sql = "update wf_history set wf_hist_executor='$_SESSION[uid]',wf_hist_status='R',wf_hist_date_process=now() where no_proses='$cancel_id' and wf_hist_seq >= '$seq'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql2 = "update cancel_unit set cancel_status='J' where cancel_id='$cancel_id'";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
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