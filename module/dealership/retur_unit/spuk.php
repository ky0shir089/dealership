<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select 
		spuk_id,
		spuk_date,
		supl_type,
		case
			when supl_type='I' then 'INDIVIDU'
			when supl_type='C' then 'COMPANY'
			else 'PEDAGANG'
		end as type,
		spuk_cust,
		supl_name,
		scheme_id,
		scheme_amount,
		case
			when spuk_status='N' then 'NEW'
			when spuk_status='S' then 'SAVED'
			when spuk_status='A' then 'APPROVED'
			when spuk_status='C' then 'CANCEL'
			when spuk_status='J' then 'REJECT'
			when spuk_status='R' then 'REQUEST'
			else 'PAID'
		end as spuk_status,
		spuk_jml_unit,
		spuk_total_scheme,
		spuk_subtotal
	from spuk_hdr a
		left join mst_suppliers b on a.spuk_cust=b.supl_id
		left join mst_scheme c on a.spuk_scheme=c.scheme_id
	where spuk_outlet='$_SESSION[outlet]'
	order by spuk_id desc");

	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>