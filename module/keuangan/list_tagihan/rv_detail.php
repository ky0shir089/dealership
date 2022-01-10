<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$id = $_REQUEST['id'];
	
	$rs = mysqli_query($con,"select
		used_rv_no,
		used_rv_amount 
	from used_rv_promo
	where used_invoice_no='$id'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	$rs = mysqli_query($con,"select sum(used_rv_amount) as total from used_rv_promo where used_invoice_no='$id'");
	$row = mysqli_fetch_array($rs);
	$entry = array("used_rv_no"=>"Total","used_rv_amount" => $row["total"]);
	$jsonData[] = $entry;
	$result["footer"] = $jsonData;

	echo json_encode($result);

?>