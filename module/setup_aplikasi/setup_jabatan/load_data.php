<?php

	include '../../../conn2.php';
	
	$rs = mysqli_query($con,"select count(*) from hr_mst_job");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select * from hr_mst_job");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>