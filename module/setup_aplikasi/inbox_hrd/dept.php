<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select dept_id,dept_name from hr_dept_all order by dept_name asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>