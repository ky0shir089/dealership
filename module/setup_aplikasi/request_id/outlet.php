<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "select kode_cabang from infinity.titik where kode_titik='$_SESSION[outlet]'";
$result = mysqli_query($con,$sql);
$data = mysqli_fetch_array($result);

$rs = mysqli_query($con,"select kode_titik,nama_titik from infinity.titik where (id_biz=1 or id_biz=2 or id_biz='') and kode_cabang='$data[kode_cabang]' and status='Y' order by nama_titik asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>