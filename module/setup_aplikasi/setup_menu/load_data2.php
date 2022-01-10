<?php

	include '../../../conn2.php';
	
	$id = $_REQUEST['id'];
	
	$rs = mysqli_query($con,"select count(*) from mst_menus where module_id='$id'");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select *,module_name 
		from mst_menus a,mst_modules b 
		where a.module_id=b.module_id and 
		a.module_id='$id'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>