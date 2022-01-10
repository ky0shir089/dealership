<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select province_id,province_name from mst_provinces");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>