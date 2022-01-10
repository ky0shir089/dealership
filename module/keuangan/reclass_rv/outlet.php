<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select kode_titik,nama_titik from infinity.titik where (id_biz=2 or id_biz is null) and status='Y' order by nama_titik asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>