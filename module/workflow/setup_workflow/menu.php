<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select * from mst_menus where is_aktif=1");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>