<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$spuk_id = $_REQUEST['spuk_id'];
$data = json_decode($_POST['data']);

mysqli_autocommit($con,FALSE);

$error = array();

foreach($data as $dt){
	$sql = "insert into repayment_rv(rrv_spuk_id,rrv_no_rv,rrv_rv_amount) values('$spuk_id','$dt->rv_no','$dt->rv_amount')";
	$result = mysqli_query($con,$sql);
	if($result == false){
		array_push($error,mysqli_error($con));
	} 
	
	$sql2 = "update fin_trn_rv set rv_status='U' where rv_no='$dt->rv_no'";
	$result2 = mysqli_query($con,$sql2);
	if($result2 == false){
		array_push($error,mysqli_error($con));
	} 
}

$sql3 = "update unit_titip_jual set
		utj_status='R'
		where utj_id in (select spuk_dtl_utj from spuk_dtl where spuk_dtl_id='$spuk_id')";
$result3 = mysqli_query($con,$sql3);
if($result3 == false){
	array_push($error,mysqli_error($con));
}

$sql7 = "update spuk_hdr set spuk_status='R' where spuk_id='$spuk_id'";
$result7 = mysqli_query($con,$sql7);
if($result7 == false){
	array_push($error,mysqli_error($con));
}  
	
$query2 = "select wf_id from mst_workflow where wf_form='FRM012'";
$hasil2 = mysqli_query($con,$query2);
if($hasil2 == false){
	array_push($error,mysqli_error($con));
} else {
	$data2 = mysqli_fetch_array($hasil2);
	$wf_id = $data2['wf_id'];
}

$query3 = "select wf_dtl_dept,wf_dtl_job from mst_wf_detail where wf_dtl_id='$wf_id'";
$hasil3 = mysqli_query($con,$query3);
if($hasil3 == false){
	array_push($error,mysqli_error($con));
} else {
	$wf_hist_seq = 0;
	while($data3 = mysqli_fetch_array($hasil3)){
		$wf_dtl_dept = $data3['wf_dtl_dept'];
		$wf_dtl_job = $data3['wf_dtl_job'];
		$wf_hist_seq++;
		
		$sql4 = "insert into wf_history(
					wf_hist_id,
					wf_hist_seq,
					no_proses,
					wf_hist_date_create,
					outlet_proses,
					wf_hist_dept,
					wf_hist_job) 
				values(
					'$wf_id',
					'$wf_hist_seq',
					'$spuk_id',
					now(),
					'$_SESSION[outlet]',
					'$wf_dtl_dept',
					'$wf_dtl_job')";
		$result4 = mysqli_query($con,$sql4);
		if($result4 == false){
			array_push($error,mysqli_error($con));
		} 
	}
}

$sql5 = "update wf_history set wf_hist_executor='$_SESSION[uid]',wf_hist_status='A',wf_hist_date_process=now() where no_proses='$spuk_id' and wf_hist_seq=1";
$result5 = mysqli_query($con,$sql5);
if($result5 == false){
	array_push($error,mysqli_error($con));
} 

$sql6 = "insert into wf_process values('$spuk_id',1)";
$result6 = mysqli_query($con,$sql6);
if($result6 == false){
	array_push($error,mysqli_error($con));
} 

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved','spuk_id'=>$spuk_id));
}

mysqli_close($con);
?>