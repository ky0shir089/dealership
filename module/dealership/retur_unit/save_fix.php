<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$spuk_id = $_REQUEST['spuk_id'];

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select 
		count(spuk_dtl_id) as jml_unit,
		sum(utj_hutang_konsumen) as total_hutang,
		sum(scheme_amount) as total_scheme,
		sum(spuk_dtl_total) as subtotal 
	from spuk_dtl a
		left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id 
		left join spuk_hdr c on a.spuk_dtl_id=c.spuk_id
		left join mst_scheme d on c.spuk_scheme=d.scheme_id
	where spuk_dtl_id='$spuk_id'";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
	$jml_unit = $data['jml_unit'];
	$total_hutang = $data['total_hutang'];
	$total_scheme = $data['total_scheme'];
	$subtotal = $data['subtotal'];
}

$sql = "update spuk_hdr set 
	spuk_status='S',
	spuk_jml_unit='$jml_unit',
	spuk_total_hutang='$total_hutang',
	spuk_total_scheme='$total_scheme',
	spuk_subtotal='$subtotal'	
	where spuk_id='$spuk_id'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
} 

$sql2 = "update spuk_dtl set spuk_dtl_status='A' where spuk_dtl_id='$spuk_id'";
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
	echo json_encode(array('success'=>'Data Saved','subtotal'=>$subtotal));
}

mysqli_close($con);
?>