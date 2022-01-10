<?php
header("Access-Control-Allow-Origin: *");
include "../../../pdo.php";
include "../../../session.php";

$post = filter_input_array(INPUT_POST);
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
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

try
{
	$pdo->beginTransaction();
	
	$count = "select 
				count(distinct(pv_no)) as total 
			from fin_trn_payment
			where 
				pv_no is not null and
				pv_outlet = ? and
				pv_proses_id like '%SPUK%'
				$where";
	$stmt2 = $pdo->prepare($count);
	$stmt2->execute([
		$_SESSION['outlet']
	]);
	$data = $stmt2->fetch();
	$result["total"] = $data['total'];
		
	$query = "select
				pv_id,
				pv_no,
				pv_paid_date,
				pv_desc,
				concat(bank_name,' - ',rekout_name,' - ',rekout_no) as rekening,
				sum(pv_amount) as pv_amount,
				pv_rv_fif
			from fin_trn_payment a
			join mst_rekening_outlet b on a.pv_bank_rek=b.rekout_no
			join mst_bank c on b.rekout_id=c.bank_id
			where 
				pv_no is not null and
				pv_outlet = ? and
				pv_proses_id like '%SPUK%'
				$where
			group by pv_no
			order by pv_id desc limit $offset,$rows";
	$stmt = $pdo->prepare($query);
	$stmt->execute([
		$_SESSION['outlet']
	]);
	
	$items = array();

	foreach($stmt as $row)
    {
        array_push($items, $row);
	}
	
	$result["rows"] = $items;

	$pdo->commit();
	echo json_encode($result);
}

catch(PDOException $e)
{
	$pdo->rollback();
    echo "Error: " . $e->getMessage();
}

$pdo = null;
?>