<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select role_id,role_name from mst_roles order by role_id desc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>