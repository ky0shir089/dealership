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

/* $count = "select count(*) from fin_trn_payment a
		join mst_rekening_outlet b on a.pv_bank_rek=b.rekout_no
		join mst_bank c on b.rekout_id=c.bank_id
		where pv_no is not null 
		$where
		group by pv_no";
$rs = mysqli_query($con,$count);
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0]; */

$query = "select
			pv_no,
			pv_paid_date,
			pv_desc,
			bank_name,
			rekout_name,
			rekout_no,
			sum(pv_amount) as pv_amount
		from fin_trn_payment a
		join mst_rekening_outlet b on a.pv_bank_rek=b.rekout_no
		join mst_bank c on b.rekout_id=c.bank_id
		where pv_no is not null 
		$where
		group by pv_no
		order by pv_no desc limit $offset,$rows";
		//die($query);
$rs = mysqli_query($con,$query);
$count = mysqli_num_rows($rs);
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["total"] = $count;
$result["rows"] = $items;

echo json_encode($result);

?>