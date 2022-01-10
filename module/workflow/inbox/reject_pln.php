<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$spuk_id = $_REQUEST['spuk_id'];
$reason = strtoupper($_POST['reason']);

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select wf_hist_id,count(no_proses) as seq from wf_history where no_proses='$spuk_id' and wf_hist_status='A'";
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

$sql = "update wf_history set wf_hist_executor='$_SESSION[uid]',wf_hist_status='R',wf_hist_date_process=now() where no_proses='$spuk_id' and wf_hist_seq >= '$seq'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql2 = "update spuk_hdr set spuk_status='J' where spuk_id='$spuk_id'";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

$sql4 = "update fin_trn_rv set rv_status='N' where rv_no in (select rrv_no_rv from repayment_rv where rrv_spuk_id='$spuk_id')";
$result4 = mysqli_query($con,$sql4);
if($result4 == false){
	array_push($error,mysqli_error($con));
}

$sql8 = "insert into spuk_reject
	select * from spuk_dtl
	where spuk_dtl_id='$spuk_id'";
$result8 = mysqli_query($con,$sql8);
if($result8 == false){
	array_push($error,mysqli_error($con));
} 

$sql5 = "delete from spuk_dtl where spuk_dtl_id='$spuk_id'";
$result5 = mysqli_query($con,$sql5);
if($result5 == false){
	array_push($error,mysqli_error($con));
} 

$sql6 = "update unit_titip_jual set utj_status='N' where utj_id IN (select spuk_dtl_utj from spuk_reject where spuk_dtl_id='$spuk_id')";
$result6 = mysqli_query($con,$sql6);
if($result6 == false){
	array_push($error,mysqli_error($con));
}

$sql7 = "insert into reject_reason values('$spuk_id','$reason')";
$result7 = mysqli_query($con,$sql7);
if($result7 == false){
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