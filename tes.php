<?php
$db1 = mysqli_connect("localhost","root","","dealership");
//$db2 = mysqli_connect("localhost","root","","infinity",true);

$sql ="select user_outlet,nama_titik from
	dealership.users a,infinity.titik b
	WHERE a.user_outlet = b.kode_titik";
$result = mysqli_query($db1,$sql);
$data = mysqli_fetch_array($result);
echo $data[0].'-'.$data[1];
?>