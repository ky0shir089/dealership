<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$spuk_id = $_REQUEST['spuk_id'];
$scheme_amount = $_REQUEST['scheme_amount'];
$data = json_decode($_POST['data']);

mysqli_autocommit($con,FALSE);

$error = array();

foreach($data as $dt){
	$total = $dt->utj_hutang_konsumen + $scheme_amount;
	
	$sql = "insert into spuk_dtl(spuk_dtl_id,spuk_dtl_utj,spuk_dtl_scheme,spuk_dtl_total,spuk_dtl_create_by,spuk_dtl_created) values('$spuk_id','$dt->utj_id','$scheme_amount','$total','$_SESSION[uid]',current_date())";
	$result = mysqli_query($con,$sql);
	if($result == false){
		array_push($error,mysqli_error($con));
	} 
	
	$sql2 = "update unit_titip_jual set utj_status='D' where utj_id='$dt->utj_id'";
	$result2 = mysqli_query($con,$sql2);
	if($result2 == false){
		array_push($error,mysqli_error($con));
	}
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