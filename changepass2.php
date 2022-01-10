<?php
include 'conn.php';
session_start();

$id = $_SESSION['uid'];
$old = md5($_POST['old']);
$new = md5($_POST['new']);

if($_SESSION['password'] == $old){
	$sql = "update users set user_password='$new' where user_id='$id'";
} else {
	echo "Old Password didnt match";
}

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>'Password Berhasil diubah'));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>