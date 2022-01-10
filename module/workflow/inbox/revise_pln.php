<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$pln_no = $_REQUEST['pln_no'];
$pln_spuk_id = $_REQUEST['pln_spuk_id'];
$pln_no_rv = $_REQUEST['pln_no_rv'];

$query = "select wf_hist_id,count(no_proses) as seq from wf_history where no_proses='$pln_no' and wf_hist_status='A'";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$wf_id = $data['wf_hist_id'];
$seq = $data['seq']+1;

$query2 = "select count(wf_dtl_urutan) as urutan from mst_wf_detail where wf_dtl_id='$wf_id'";
$hasil2 = mysqli_query($con,$query2);
$data2 = mysqli_fetch_array($hasil2);
$urutan = $data2['urutan'];

$sql = "update wf_history set wf_hist_executor='$_SESSION[uid]',wf_hist_status=null,wf_hist_date_process=now() where no_proses='$pln_no' and wf_hist_seq <= '$seq'";
$result = mysqli_query($con,$sql);

$sql2 = "update wf_process set jml_approve=0 where wf_process_no='$pln_no'";
$result2 = mysqli_query($con,$sql2);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Rejected'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>