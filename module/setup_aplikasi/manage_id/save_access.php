<?php
include '../../../conn2.php';
session_start();

$id = $_GET['id'];
$data = json_decode($_POST['data']);
	
foreach($data as $dt){
	$sql = "INSERT INTO outlet_access (access_user,access_outlet,access_create_by,access_created) VALUES ('$id','$dt->kode_titik','$_SESSION[uid]',now())";
	mysqli_query($con,$sql) or die(mysqli_error($con));	
}
	
echo "Data Saved";
?>