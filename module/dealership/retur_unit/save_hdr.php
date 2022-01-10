<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$supl_id = $_REQUEST['supl_id'];
$scheme_id = $_REQUEST['scheme_id'];

$query = "select * from seq_spuk where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
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
$spuk_id = $_SESSION['outlet'].date('ym').'SPUK'.$seq;
$year = date('y');
$month = date('m');

mysqli_autocommit($con,FALSE);

$error = array();

$sql = "insert into spuk_hdr(spuk_id,spuk_date,spuk_outlet,spuk_cust,spuk_scheme,spuk_create_by,spuk_created) values('$spuk_id',current_date(),'$_SESSION[outlet]','$supl_id','$scheme_id','$_SESSION[uid]',current_date())";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
} 

$sql2 = "insert into seq_spuk(outlet,year,month,seq) values('$_SESSION[outlet]','$year','$month','$seq')";
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
	echo json_encode(array('success'=>'Data Saved','spuk_id'=>$spuk_id));
}

mysqli_close($con);
?>