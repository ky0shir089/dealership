<?php

	$id = $_GET['id'];
	
	include '../../../conn2.php';
	
	$rs = mysqli_query($con,"select user_role_id,a.role_id,role_name 
		from user_roles a,mst_roles b 
		where a.role_id=b.role_id and
		user_id='$id'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>