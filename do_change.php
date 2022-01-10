<?php
include 'conn.php';
session_start();

$uid = $_POST['uid'];
$pass = $_POST['pass'];
$pass2 = md5($_POST['pass2']);

$query = "update users set user_password='$pass2',user_chpass='N',user_lastpassword=now() where user_id='$uid'";
mysql_query($query) or die(mysql_error());

$_SESSION['password'] = $pass2;

echo "Password Berhasil diubah <br>";
header( "refresh:2;url=home.php?module=home" );

mysql_close($conn);
?>