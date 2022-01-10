<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select module_id,module_name from mst_modules");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>