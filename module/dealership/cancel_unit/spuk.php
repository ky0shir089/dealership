<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select count(*) from spuk_hdr a
		left join mst_suppliers b on a.spuk_cust=b.supl_id
	where 
		spuk_outlet='$_SESSION[outlet]' and
		spuk_status='P' and
		spuk_jml_unit > 0");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select 
		spuk_id,
		spuk_outlet,
		spuk_date,
		supl_name,
		case
			when spuk_status='P' then 'PAID'
		end as spuk_status
	from spuk_hdr a
		left join mst_suppliers b on a.spuk_cust=b.supl_id
	where 
		spuk_outlet='$_SESSION[outlet]' and
		spuk_status='P' and
		spuk_jml_unit > 0
	order by spuk_id desc");

	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>