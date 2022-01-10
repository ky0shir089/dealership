<?php

include '../../../conn2.php';

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

$rs = mysqli_query($con,"select count(*) from mst_customers a
	join mst_suppliers b on a.cust_id=b.supl_id
	join mst_regencies c on a.cust_regency=c.regency_id
	join mst_provinces d on c.regency_province=d.province_id
	$where order by cust_id desc limit $offset,$rows");
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0];

$rs = mysqli_query($con,"select 
		cust_id,
		supl_name,
		cust_ktp,
		cust_owner,
		cust_address,
		province_id,
		province_name,
		regency_id,
		regency_name,
		cust_hp,
		cust_hp2,
		supl_type,
		(case
			when supl_type = 'I' then 'INDIVIDU'
			when supl_type = 'C' then 'COMPANY'
			else 'PEDAGANG'
		end) as type,
		(case
			when supl_status = 'Y' then 'ACTIVE'
			else 'INACTIVE'
		end) as supl_status
	from mst_customers a
	join mst_suppliers b on a.cust_id=b.supl_id
	join mst_regencies c on a.cust_regency=c.regency_id
	join mst_provinces d on c.regency_province=d.province_id
	$where order by cust_id desc limit $offset,$rows");
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

echo json_encode($result);
