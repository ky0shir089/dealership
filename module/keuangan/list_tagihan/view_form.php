<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		invhdr_no,
		invhdr_mst_code,
		case
			when invhdr_mst_code='TRX03' then 'PENGEMBALIAN TITIPAN'
			when invhdr_mst_code='TRX04' then 'BUNGA BANK'
			when invhdr_mst_code='TRX05' then 'PENGGUNAAN TITIPAN PROMO'
			when invhdr_mst_code='TRX06' then 'PELUNASAN UNIT OFFLINE'
			when invhdr_mst_code='TRX07' then 'PEMINDAHAN BANK'
		end as type_trx,
		invhdr_created,
		case
			when invhdr_status='R' then 'REQUEST'
			when invhdr_status='A' then 'APPROVE'
			else 'PAID'
		end as invhdr_status,
		case
			when supl_type='C' then 'SUPPLIER'
			else 'CUSTOMERS'
		end as supl_type,
		supl_id,
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
	where 
		invhdr_no='$id'");

$row = mysqli_fetch_object($rs);

echo json_encode($row);
?>