<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select count(*) from gl_period");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select * from gl_period
		order by period_created desc");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>