<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select 
			pln_no,
			pln_date,
			case
				when pln_status='R' then 'REQUEST'
				when pln_status='A' then 'APPROVE'
				when pln_status='C' then 'CANCEL'
				else 'PAID'
			end as pln_status,
			pln_spuk_id,
			supl_type,
			case
				when supl_type='I' then 'INDIVIDU'
				when supl_type='C' then 'COMPANY'
				else 'PEDAGANG'
			end as type,
			spuk_cust,
			supl_name,
			pln_scheme,
			scheme_amount,
			count(pln_no) as jml_unit,
			sum(spuk_dtl_amount) as total
		from repayment a
		left join spuk_hdr b on a.pln_spuk_id=b.spuk_id
		left join mst_suppliers c on b.spuk_cust=c.supl_id
		left join mst_scheme d on a.pln_scheme=d.scheme_id
		left join spuk_dtl e on a.pln_spuk_id=e.spuk_dtl_id
		where pln_no='$id' and spuk_dtl_status='R'");

$row = mysqli_fetch_object($rs);

echo json_encode($row);
?>