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
			scheme_amount
		from pembayaran_spuk a
		left join spuk_hdr b on a.pln_spuk_id=b.spuk_id
		left join mst_suppliers c on b.spuk_cust=c.supl_id
		left join mst_scheme d on a.pln_scheme=d.scheme_id
		where pln_no='$id'");

	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>