<?php

$supl_name = htmlspecialchars(strtoupper($_REQUEST['supl_name2']));
$supl_type = htmlspecialchars($_REQUEST['supl_type2']);

include '../../../conn2.php';
include '../../../cek_session.php';

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
$sql .= "insert into seq_cust(seq,year) values('$seq','$year')";

// Execute multi query
if(mysqli_multi_query($con,$sql)){
	echo json_encode(array('success'=>'Data Saved','id'=>$supl_id));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
}

mysqli_close($con);
?>