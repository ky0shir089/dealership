<?php
include '../../../conn2.php';
session_start();

$id = $_GET['id'];
$data = json_decode($_POST['data']);
	
foreach($data as $dt){
	$sql = "INSERT INTO user_roles (user_id,role_id,user_role_create_by,user_role_created) VALUES ('$id','$dt->role_id','$_SESSION[uid]',now())";
	mysqli_query($con,$sql) or die(mysqli_error($con));	
}
	
echo "Data Saved";
?>