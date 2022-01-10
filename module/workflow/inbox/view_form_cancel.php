<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		cancel_id,
		cancel_date,
		cancel_spuk_id,
		spuk_outlet,
		cancel_reason,
		supl_name,
		(case
			when cancel_status='R' then 'REQUEST'
			when cancel_status='P' then 'PAID'
			when cancel_status='C' then 'CANCEL'
		end) as cancel_status
	from cancel_unit a
		left join spuk_hdr b on a.cancel_spuk_id=b.spuk_id
		left join mst_suppliers c on b.spuk_cust=c.supl_id
	where 
		cancel_id='$id'");

$row = mysqli_fetch_object($rs);

echo json_encode($row);
?>