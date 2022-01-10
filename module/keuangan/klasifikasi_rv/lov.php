<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$post = filter_input_array(INPUT_POST);
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
$offset = ($page-1)*$rows;

$wheres = array();
$where = "";
if (isset($post['filterRules']) || !empty($post['filterRules'])) {
	$filterRules = json_decode($post['filterRules'], TRUE);
	foreach ($filterRules as $filter) {
		$field = $filter['field'];
		$value = $filter['value'];
		$wheres[] = "$field like '%$value%'";
	}
}
 
if (count($wheres) > 0) {
	$where = "and ". implode(" and ",$wheres);
}

$rs = mysqli_query($con,"select count(*) from fin_trn_rv a
	join mst_rekening_outlet b on a.rv_bank_rek=b.rekout_no
	join mst_bank c on b.rekout_id=c.bank_id
	join gl_coa d on a.rv_segment2=d.coa_code
	left join infinity.titik e on a.rv_classification=e.kode_titik
	where 
		rv_classification is null and 
		rv_status='N'
		$where");
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0];

$rs = mysqli_query($con,"select
		rv_no,
		rv_received_date,
		rv_mst_code,
		(case
			when rv_mst_code='TRX01' then 'LAIN-LAIN'
		end) as type_trx,
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
	where 
		rv_classification is null and 
		rv_status='N'
		$where
	order by rv_no desc limit $offset,$rows");
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

echo json_encode($result);

?>