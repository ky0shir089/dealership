<?php

$id = json_decode($_POST['id']);

include '../../../conn2.php';

mysqli_autocommit($con,FALSE);

$error = array();

foreach($id as $dt){
	$sql = "delete from unit_titip_jual where utj_id='$dt->utj_id'";
	$result = mysqli_query($con,$sql);
	if($result == false){
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
	echo json_encode(array('success'=>'Data Deleted'));
}
?>