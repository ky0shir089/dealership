<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$cancel_date = $_REQUEST['cancel_date'];
$cancel_spuk_id = $_REQUEST['cancel_spuk_id'];
$cancel_reason = strtoupper($_REQUEST['cancel_reason']);
$spuk_outlet = $_REQUEST['spuk_outlet'];
$data = json_decode($_POST['data']);

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select * from seq_cancel where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
$hasil = mysqli_query($con,$query);
$data2 = mysqli_fetch_array($hasil);
$month = $data2['month'];
if($month < month){
	$seq = '00001';
}
elseif($month > month and $year < year){
	$seq = '00001';
} 
else {
	$seq = sprintf("%'.05d",$data2['seq']+1);
}
$cancel_id = $_SESSION['outlet'].date('ym').'CNCL'.$seq;
$year = date('y');
$month = date('m');
$month_name = strtoupper(date('M'));

$sql = "insert into seq_cancel(outlet,year,month,seq) values('$_SESSION[outlet]','$year','$month','$seq')";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

foreach($data as $dt){
	$sql2 = "insert into cancel_unit(
				cancel_id,
				cancel_date,
				cancel_spuk_id,
				cancel_utj,
				cancel_utj_scheme,
				cancel_hbm,
				cancel_reason,
				cancel_status,
				cancel_create_by,
				cancel_created) 
			values(
				'$cancel_id',
				'$cancel_date',
				'$cancel_spuk_id',
				'$dt->spuk_dtl_utj',
				'$dt->spuk_dtl_scheme',
				'$dt->utj_hutang_konsumen',
				'$cancel_reason',
				'R',
				'$_SESSION[uid]',
				current_date())";
	$result2 = mysqli_query($con,$sql2);
	if($result2 == false){
		array_push($error,mysqli_error($con));
	} 
}

$query3 = "select wf_id from mst_workflow where wf_form='FRM044'";
$hasil3 = mysqli_query($con,$query3);
$data3 = mysqli_fetch_array($hasil3);
$wf_id = $data3['wf_id'];

$query4 = "select wf_dtl_dept,wf_dtl_job from mst_wf_detail where wf_dtl_id='$wf_id'";
$hasil4 = mysqli_query($con,$query4);
$wf_hist_seq = 0;
while($data4 = mysqli_fetch_array($hasil4)){
	$wf_dtl_dept = $data4['wf_dtl_dept'];
	$wf_dtl_job = $data4['wf_dtl_job'];
	$wf_hist_seq++;
	
	$sql3 = "insert into wf_history(wf_hist_id,wf_hist_seq,no_proses,wf_hist_date_create,outlet_proses,wf_hist_dept,wf_hist_job) values('$wf_id','$wf_hist_seq','$cancel_id',now(),'$_SESSION[outlet]','$wf_dtl_dept','$wf_dtl_job')";
	$result3 = mysqli_query($con,$sql3);
	if($result3 == false){
		array_push($error,mysqli_error($con));
	} 
}

$sql4 = "update wf_history set wf_hist_executor='$_SESSION[uid]',wf_hist_status='A',wf_hist_date_process=now() where no_proses='$cancel_id' and wf_hist_seq=1";
$result4 = mysqli_query($con,$sql4);
if($result4 == false){
	array_push($error,mysqli_error($con));
} 

$sql5 = "insert into wf_process values('$cancel_id',1)";
$result5 = mysqli_query($con,$sql5);
if($result5 == false){
	array_push($error,mysqli_error($con));
}

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else{
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Requested','cancel_id'=>$cancel_id));
}

mysqli_close($con);
?>