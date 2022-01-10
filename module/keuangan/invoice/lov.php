<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$rs = mysqli_query($con,"select
		invhdr_no,
		invhdr_mst_code,
		invmst_desc as type_trx,
		invhdr_created,
		case
			when invhdr_status='R' then 'REQUEST'
			when invhdr_status='A' then 'APPROVE'
			when invhdr_status='J' then 'REJECT'
			when invhdr_status='C' then 'CANCEL'
			else 'PAID'
		end as invhdr_status,
		case
			when supl_type='C' then 'SUPPLIER'
			when supl_type='I' then 'SUPPLIER'
			else 'CUSTOMERS'
		end as supl_type,
		invhdr_supplier,
		supl_name,
		bank_name,
		rek_name,
		invhdr_rek_no,
		invhdr_segment2,
		coa_description,
		invhdr_reff_no,
		invhdr_desc,
		rv_amount,
		invhdr_amount
	from fin_trn_inv_hdr a
	left join mst_suppliers b on a.invhdr_supplier=b.supl_id
	left join mst_rekening c on a.invhdr_rek_no=c.rek_no
    left join mst_bank d on c.rek_bank=d.bank_id
	left join gl_coa e on a.invhdr_segment2=e.coa_code
	left join fin_trn_rv f on a.invhdr_reff_no=f.rv_no
	left join fin_mst_invhdr g on a.invhdr_mst_code=g.invmst_code
	order by invhdr_no desc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>