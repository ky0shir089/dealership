<?php

	include '../../../conn2.php';
	
	$id = $_REQUEST['id'];
	
	$rs = mysqli_query($con,"select count(*) from mst_rolemenus where role_id='$id'");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select *,
		menu_name,
		case
			when rolemenu_sts=1 then 'ACTIVE'
			else 'NOT ACTIVE'
		end as status
		from mst_rolemenus a, mst_menus b 
		where a.menu_id=b.menu_id and
		role_id='$id'
		order by a.menu_id asc");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>