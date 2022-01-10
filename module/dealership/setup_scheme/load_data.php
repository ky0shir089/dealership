<?php

	include '../../../conn2.php';
	
	$rs = mysqli_query($con,"select count(*) from mst_scheme");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			scheme_id,
			scheme_amount,
			scheme_status,
			case
				when scheme_status='Y' then 'ACTIVE'
				else 'INACTIVE'
			end as status
		from mst_scheme");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>