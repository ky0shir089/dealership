<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select count(*) from cancel_unit a
		left join spuk_hdr b on a.cancel_spuk_id=b.spuk_id
		left join mst_suppliers c on b.spuk_cust=c.supl_id
	where 
		spuk_outlet='$_SESSION[outlet]'");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select 
		cancel_id,
		cancel_date,
		cancel_spuk_id,
		cancel_reason,
		supl_name,
		case
			when cancel_status='R' then 'REQUEST'
			when cancel_status='P' then 'PAID'
			when cancel_status='C' then 'CANCEL'
		end as cancel_status
	from cancel_unit a
		left join spuk_hdr b on a.cancel_spuk_id=b.spuk_id
		left join mst_suppliers c on b.spuk_cust=c.supl_id
	where 
		spuk_outlet='$_SESSION[outlet]'
	group by cancel_id
	order by cancel_id desc");

	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>