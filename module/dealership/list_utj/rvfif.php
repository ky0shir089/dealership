<?php
include '../../../pdo.php';
session_start();

$id = $_GET['id'];
$utj_rv_fif = $_REQUEST['utj_rv_fif'];

try
{
	$pdo->beginTransaction();

	$sql = "update unit_titip_jual set
				utj_rv_fif = ?,
				utj_update_by = ?,
				utj_updated = current_date()
			where utj_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([
		$utj_rv_fif,
		$_SESSION['uid'],
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