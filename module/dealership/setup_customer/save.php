<?php

$supl_name = htmlspecialchars(strtoupper($_REQUEST['supl_name']));
$cust_ktp = htmlspecialchars($_REQUEST['cust_ktp']);
$cust_owner = htmlspecialchars(strtoupper($_REQUEST['cust_owner']));
$cust_address = htmlspecialchars(trim(strtoupper($_REQUEST['cust_address'])));
$cust_regency = htmlspecialchars($_REQUEST['regency_id']);
$cust_hp = htmlspecialchars($_REQUEST['cust_hp']);
$cust_hp2 = htmlspecialchars($_REQUEST['cust_hp2'] == "" ? 'NULL' : "'".$_REQUEST['cust_hp2']."'");
$supl_type = htmlspecialchars($_REQUEST['supl_type']);

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select * from seq_cust order by seq_id desc limit 0,1";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$tahun = $data['year'];
if($tahun < year){
	$seq = '00001';
} else {
	$seq = sprintf("%'.05d",$data['seq']+1);
}
$supl_id = date('y').$seq;
$year = date('y');

$sql = "insert into mst_suppliers(supl_id,supl_name,supl_type,supl_create_by,supl_created) values('$supl_id','$supl_name','$supl_type','$_SESSION[uid]',now());";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql2 = "insert into mst_customers(cust_id,cust_ktp,cust_owner,cust_address,cust_regency,cust_hp,cust_hp2,cust_create_by,cust_created) values('$supl_id','$cust_ktp','$cust_owner','$cust_address','$cust_regency','$cust_hp',$cust_hp2,'$_SESSION[uid]',now());";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

$sql3 = "insert into seq_cust(seq,year) values('$seq','$year')";
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
	echo json_encode(array('success'=>'Data Saved','id'=>$supl_id));
}

mysqli_close($con);
?>