<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$id = $_REQUEST['id'];
	
	$rs = mysqli_query($con,"select
		rrv_no_rv,
		rrv_rv_amount
	from repayment_rv
	where rrv_spuk_id='$id'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	$rs = mysqli_query($con,"select sum(rrv_rv_amount) as total from repayment_rv where rrv_spuk_id='$id'");
	$row = mysqli_fetch_array($rs);
	$entry = array("rrv_no_rv"=>"Total","rrv_rv_amount" => $row["total"]);
	$jsonData[] = $entry;
	$result["footer"] = $jsonData;

	echo json_encode($result);

?>