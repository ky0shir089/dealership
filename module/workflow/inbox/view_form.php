<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

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
			when spuk_status='R' then 'REQUEST'
			when spuk_status='A' then 'APPROVE'
			when spuk_status='C' then 'CANCEL'
			else 'PAID'
		end as spuk_status,
		spuk_jml_unit,
		spuk_total_hutang,
		spuk_total_scheme,
		spuk_subtotal
	from spuk_hdr a
		left join mst_suppliers b on a.spuk_cust=b.supl_id
		left join mst_scheme c on a.spuk_scheme=c.scheme_id
	where 
		spuk_id='$id'");

$row = mysqli_fetch_object($rs);

echo json_encode($row);
?>