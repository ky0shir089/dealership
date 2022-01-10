<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$spuk_id = $_REQUEST['spuk_id'];
$scheme_id = $_REQUEST['scheme_id'];

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select scheme_amount from mst_scheme where scheme_id='$scheme_id'";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
	$scheme_amount = $data['scheme_amount'];
}

$sql = "update spuk_dtl set 
		spuk_dtl_total=spuk_dtl_total-spuk_dtl_scheme
		where spuk_dtl_id='$spuk_id'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
} 

$sql2 = "update spuk_hdr set 
		spuk_scheme='$scheme_id'
		where spuk_id='$spuk_id'";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
} 

$sql3 = "update spuk_dtl set 
		spuk_dtl_scheme='$scheme_amount',
		spuk_dtl_total=spuk_dtl_total+'$scheme_amount'
		where spuk_dtl_id='$spuk_id'";
$result3 = mysqli_query($con,$sql3);
if($result3 == false){
	array_push($error,mysqli_error($con));
} 

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Reloaded','spuk_id'=>$spuk_id));
}

mysqli_close($con);
?>