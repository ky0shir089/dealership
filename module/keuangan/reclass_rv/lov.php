<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$rs = mysqli_query($con,"select
		rv_no,
		rv_received_date,
		rv_mst_code,
		case
			when rv_mst_code='TRX01' then 'LAIN-LAIN'
		end as type_trx,
		rv_received_from,
		bank_name,
		rekout_name,
		rv_bank_rek,
		rv_segment2,
		coa_description,
		rv_amount,
		rv_classification,
		nama_titik
	from fin_trn_rv a
	join mst_rekening_outlet b on a.rv_bank_rek=b.rekout_no
	join mst_bank c on b.rekout_id=c.bank_id
	join gl_coa d on a.rv_segment2=d.coa_code
	left join infinity.titik e on a.rv_classification=e.kode_titik
	where rv_status='N' and rv_classification is not null
	order by rv_no desc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>