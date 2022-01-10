<?php

$invmst_code = htmlspecialchars($_REQUEST['invmst_code']);
$data = json_decode($_POST['data']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

foreach($data as $dt){
	$invdtl_code = $dt->coa_code;
}

$query = "select invdtl_code from fin_mst_invdtl where invdtl_code='$invdtl_code' and invmst_code='$invmst_code'";
$hasil = mysqli_query($con,$query);
$found = mysqli_num_rows($hasil);
if($found == 1){
	echo json_encode(array('found'=>'Code Trx sudah ada'));
} else {
	$sql = "insert into fin_mst_invdtl(invmst_code,invdtl_code,invdtl_create_by,invdtl_created) values('$invmst_code','$invdtl_code','$_SESSION[uid]',current_date())";
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