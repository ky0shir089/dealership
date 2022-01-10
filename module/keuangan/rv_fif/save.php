<?php
include '../../../pdo.php';
include "../../../session.php";

$id = $_REQUEST['pv_no'];
$pv_rv_fif = $_REQUEST['pv_rv_fif'];

try
{
	$pdo->beginTransaction();
	
	$sql = "update fin_trn_payment set
				pv_rv_fif = ?,
				update_by = ?
			where pv_no = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([
		$pv_rv_fif,
		$_SESSION['uid'],
		$id
	]);

	$pdo->commit();
	echo json_encode(array('success'=>'Data Saved'));
}

catch(PDOException $e)
{
	$pdo->rollback();
	echo json_encode(array('errorMsg' => "Error: " . $e->getMessage()));
}

$pdo = null;
?>