<?php

include 'conn2.php';
include 'cek_session.php';

$rs = mysqli_query($con,"select 
		access_outlet,
		nama_titik
	from outlet_access a
	join infinity.titik b on a.access_outlet=b.kode_titik
	where access_user='$_SESSION[uid]'
	order by nama_titik asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>