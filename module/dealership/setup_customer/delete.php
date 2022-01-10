<?php
include '../../../pdo.php';

$id = $_REQUEST['id'];

try
{
	$pdo->beginTransaction();
	
	$query = "select spuk_cust from spuk_hdr where spuk_cust = ?";
	$stmt3 = $pdo->prepare($query);
	$stmt3->execute([
		$id
	]);
	$data = $stmt3->rowCount();
	if($data > 0){
		echo json_encode(array('errorMsg'=>'Customer sudah terpakai'));
	} else {
		$sql = "delete from mst_customers where cust_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			$id
		]);
		
		$sql2 = "delete from mst_suppliers where supl_id = ?";
		$stmt2 = $pdo->prepare($sql2);
		$stmt2->execute([
			$id
		]);

		$pdo->commit();
		echo json_encode(array('success'=>'Data Deleted'));
	}
}

catch(PDOException $e)
{
	$pdo->rollback();
	echo json_encode(array('errorMsg' => "Error: " . $e->getMessage()));
}

$pdo = null;
?>