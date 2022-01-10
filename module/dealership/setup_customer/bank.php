<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select bank_id,bank_name from mst_bank");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>