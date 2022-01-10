<?php
header("Access-Control-Allow-Origin: *");
include "../../../pdo.php";
include "../../../session.php";

$id = $_REQUEST['id'];

try
{
	$pdo->beginTransaction();

	$query = "select
				spuk_id,
				spuk_date,
				nama_titik,
				spuk_cust,
				supl_name,
				spuk_jml_unit,
				spuk_total_hutang,
				spuk_total_scheme,
				spuk_subtotal
			from spuk_hdr a
			join fin_trn_payment b on a.spuk_id=b.pv_proses_id
			join infinity.titik c on a.spuk_outlet=c.kode_titik
			join mst_suppliers d on a.spuk_cust=d.supl_id
			where 
				pv_no = ?";
	$stmt = $pdo->prepare($query);
	$stmt->execute([
		$id
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