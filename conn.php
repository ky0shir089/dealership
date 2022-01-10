<?php
$conn = @mysql_connect('127.0.0.1','root','');

if(!$conn){
	die('Could not connect: ' . mysql_error());
}

mysql_select_db('dealership', $conn);

//date sequence
$year = date('y');
//define('year',$year);
?>