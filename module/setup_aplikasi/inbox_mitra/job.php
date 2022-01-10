<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select job_id,job_name from hr_mst_job order by job_name asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>