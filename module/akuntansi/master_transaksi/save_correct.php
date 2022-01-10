<?php

$data = json_decode($_POST['data']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

foreach($data as $dt){
	$code = substr($dt->coa_code,0,3);
	if($code == 112){
		$type = 'B';
	} else {
		$type = 'L';
	}
	$sql = "insert into fin_mst_correct(correct_code,correct_type,correct_create_by,correct_created) values('$dt->coa_code','$type','$_SESSION[uid]',current_date())";
	$result = mysqli_query($con,$sql);
	if($result == false){
		array_push($error,mysqli_error($con));
	}
	
	if(count($error) > 0){
		$errors = join("<br>", $error);
		echo json_encode(array('errorMsg'=>$errors));
		mysqli_rollback($con);
	}
	else {
		mysqli_commit($con);
		echo json_encode(array('success'=>'Data Saved'));
	}
}

mysqli_close($con);
?>