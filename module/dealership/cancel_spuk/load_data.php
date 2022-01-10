<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select count(*) from spuk_hdr where spuk_outlet='$_SESSION[outlet]' and (spuk_status='N' or spuk_status='S')");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			spuk_id,
			spuk_date,
			supl_name,
			case
				when spuk_status='N' then 'NEW'
				when spuk_status='S' then 'SAVED'
			end as spuk_status,
			spuk_create_by
		from spuk_hdr a
		join mst_suppliers b on a.spuk_cust=b.supl_id
		where 
			spuk_outlet='$_SESSION[outlet]' and
			(spuk_status='N' or spuk_status='S')
		order by spuk_id desc");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>