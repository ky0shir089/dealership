<?php

$rvmst_desc = htmlspecialchars(strtoupper($_REQUEST['rvmst_desc']));

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select rvmst_code from fin_mst_rvhdr order by rvmst_code desc limit 0,1";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$code = substr($data['rvmst_code'],3,3);
$seq = sprintf("%'.03d",$code+1);
$rvmst_code = 'TRX'.$seq;

$sql = "insert into fin_mst_rvhdr values('$rvmst_code','$rvmst_desc','Y','$_SESSION[uid]',current_date(),NULL,NULL)";
$result = mysqli_query($con,$sql);

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved','rvmst_code'=>$rvmst_code));
}

mysqli_close($con);
?>