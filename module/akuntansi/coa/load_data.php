<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select count(*) from gl_coa");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			coa_code,
			coa_description,
			coa_type,
			case
				when coa_type='A' then 'ASSET'
				when coa_type='L' then 'LIABILITIES'
				when coa_type='O' then 'OWNER EQUITY'
				when coa_type='R' then 'REVENUE'
				when coa_type='E' then 'EXPENSE'
			end as type_name,
			coa_parent
		from gl_coa
		order by coa_code asc");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>