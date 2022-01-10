<?php
include '../../../pdo.php';
session_start();

$id = $_GET['id'];
$utj_name = strtoupper($_REQUEST['utj_name']);
$utj_no_paket = $_REQUEST['utj_no_paket'];
$utj_no_contract = $_REQUEST['utj_no_contract'];
$utj_bpkb_name = strtoupper($_REQUEST['utj_bpkb_name']);
$utj_nopol = $_REQUEST['utj_nopol'];
$utj_noka = $_REQUEST['utj_noka'];
$utj_nosin = $_REQUEST['utj_nosin'];
$utj_type = strtoupper($_REQUEST['utj_type']);
$utj_stnk = $_REQUEST['utj_stnk'];
$utj_grade = $_REQUEST['utj_grade'];
$utj_tahun = $_REQUEST['utj_tahun'];
$utj_hutang_konsumen = $_REQUEST['utj_hutang_konsumen'];
$utj_ct_date = $_REQUEST['utj_ct_date'];

try
{
	$pdo->beginTransaction();

	$sql = "update unit_titip_jual set
				utj_name = ?,
				utj_no_paket = ?,
				utj_no_contract = ?,
				utj_bpkb_name = ?,
				utj_nopol = ?,
				utj_noka = ?,
				utj_nosin = ?,
				utj_type = ?,
				utj_stnk = ?,
				utj_grade = ?,
				utj_tahun = ?,
				utj_hutang_konsumen = ?,
				utj_ct_date = ?
			where utj_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([
		$utj_name,
		$utj_no_paket,
		$utj_no_contract,
		$utj_bpkb_name,
		$utj_nopol,
		$utj_noka,
		$utj_nosin,
		$utj_type,
		$utj_stnk,
		$utj_grade,
		$utj_tahun,
		$utj_hutang_konsumen,
		$id
	]);
		
	$pdo->commit();
	echo json_encode(array('success'=>'Data Updated'));
}

catch(PDOException $e)
{
	$pdo->rollback();
	echo json_encode(array('errorMsg' => "Error: " . $e->getMessage()));
}

$pdo = null;
?>