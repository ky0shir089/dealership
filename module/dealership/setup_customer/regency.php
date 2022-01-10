<?php

include '../../../conn2.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select regency_id,regency_name from mst_regencies where regency_province='$id'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>