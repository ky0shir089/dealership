<?php

	include '../../../conn2.php';
	
	$rs = mysqli_query($con,"select count(*) from mst_workflow");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			wf_id,
			wf_name,
			menu_name,
			wf_status,
			case
				when wf_status='Y' then 'ACTIVE'
				else 'INACTIVE'
			end as status
		from mst_workflow a
		join mst_menus b on a.wf_form=b.menu_id");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>