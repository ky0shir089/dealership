<?php

$id = htmlspecialchars($_REQUEST['hidden']);
$supl_name = htmlspecialchars(strtoupper($_REQUEST['supl_name']));
$cust_ktp = htmlspecialchars($_REQUEST['cust_ktp']);
$cust_owner = htmlspecialchars(strtoupper($_REQUEST['cust_owner']));
$cust_address = htmlspecialchars(trim(strtoupper($_REQUEST['cust_address'])));
$cust_regency = htmlspecialchars($_REQUEST['regency_id']);
$cust_hp = htmlspecialchars($_REQUEST['cust_hp']);
$cust_hp2 = htmlspecialchars($_REQUEST['cust_hp2'] == "" ? 'NULL' : "'".$_REQUEST['cust_hp2']."'");
$supl_type = htmlspecialchars($_REQUEST['supl_type']);
$supl_status = htmlspecialchars($_REQUEST['supl_status']);

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "update mst_suppliers set
	supl_name='$supl_name',
	supl_type='$supl_type',
	supl_status='$supl_status',
	supl_update_by='$_SESSION[uid]',
	supl_updated=now()
	where supl_id='$id';";
$sql .= "update mst_customers set
	cust_ktp='$cust_ktp',
	cust_owner='$cust_owner',
	cust_address='$cust_address',
	cust_regency='$cust_regency',
	cust_hp='$cust_hp',
	cust_hp2=$cust_hp2,
	cust_update_by='$_SESSION[uid]',
	cust_updated=now()
	where cust_id='$id'";

// Execute multi query
if(mysqli_multi_query($con,$sql)){
	echo json_encode(array('success'=>'Data Saved','id'=>$id));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
}

mysqli_close($con);
