<?php

$invhdr_mst_code = htmlspecialchars($_REQUEST['invhdr_mst_code']);
$invhdr_created = htmlspecialchars($_REQUEST['invhdr_created']);
$invhdr_supplier = htmlspecialchars(strtoupper($_REQUEST['invhdr_supplier']));
$invhdr_rek_no = htmlspecialchars(strtoupper($_REQUEST['invhdr_rek_no']));
$invhdr_segment2 = htmlspecialchars($_REQUEST['invhdr_segment2']);
$invhdr_reff_no = htmlspecialchars($_REQUEST['invhdr_reff_no'] == "" ? 'NULL' : "'".$_REQUEST['invhdr_reff_no']."'");
$invhdr_desc = htmlspecialchars(strtoupper($_REQUEST['invhdr_desc']));
$invhdr_reff_amount = htmlspecialchars($_REQUEST['rv_amount']);
$invhdr_amount = htmlspecialchars($_REQUEST['invhdr_amount']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

//sequence
$query = "select * from seq_inv where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$tahun = $data['year'];
if($tahun < year){
	$seq = '00000001';
} else {
	$seq = sprintf("%'.08d",$data['seq']+1);
}
$inv_no = $_SESSION['outlet'].date('y').'INV'.$seq;
$year = date('y');

$sql2 = "insert into seq_inv(outlet,year,seq) values('$_SESSION[outlet]','$year','$seq')";
mysqli_query($con,$sql2) or die(mysqli_error($con));

$sql = "insert into fin_trn_inv_hdr(invhdr_no,invhdr_reff_no,invhdr_reff_amount,invhdr_supplier,invhdr_rek_no,invhdr_desc,invhdr_amount,invhdr_segment1,invhdr_segment2,invhdr_mst_code,invhdr_create_by,invhdr_created) values('$inv_no',$invhdr_reff_no,'$invhdr_reff_amount','$invhdr_supplier','$invhdr_rek_no','$invhdr_desc','$invhdr_amount','$_SESSION[outlet]','$invhdr_segment2','$invhdr_mst_code','$_SESSION[uid]',now())";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql3 = "update fin_trn_rv set rv_status='U' where rv_no=$invhdr_reff_no";
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
	echo json_encode(array('success'=>'Data Saved','inv_no'=>$inv_no));
}

mysqli_close($con);
?>