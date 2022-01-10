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
	$where = "where (" . implode(") and (", $wheres) . ")";
} 

$rs = mysqli_query($con,"select count(*) from fin_trn_rv a
	join mst_rekening_outlet b on a.rv_bank_rek=b.rekout_no
	join mst_bank c on b.rekout_id=c.bank_id
	join gl_coa d on a.rv_segment2=d.coa_code
	join fin_mst_rvhdr e on a.rv_mst_code=e.rvmst_code
	$where order by rv_no asc limit $offset,$rows");
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0];

$rs = mysqli_query($con,"select
		rv_no,
		rv_received_date,
		rv_mst_code,
		rvmst_desc,
		rv_received_from,
		bank_name,
		rekout_name,
		rv_bank_rek,
		rv_segment2,
		coa_description,
		rv_start
	from fin_trn_rv a
	join mst_rekening_outlet b on a.rv_bank_rek=b.rekout_no
	join mst_bank c on b.rekout_id=c.bank_id
	join gl_coa d on a.rv_segment2=d.coa_code
	join fin_mst_rvhdr e on a.rv_mst_code=e.rvmst_code
	$where order by rv_no asc limit $offset,$rows");
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

echo json_encode($result);

?>