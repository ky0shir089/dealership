<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select * from fin_trn_rv where rv_segment2='3310201' and rv_classification='$_SESSION[outlet]' and rv_status='N'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	$rs = mysqli_query($con,"select sum(rv_amount) as total from fin_trn_rv where rv_segment2='3310201' and rv_classification='$_SESSION[outlet]' and rv_status='N'");
	$row = mysqli_fetch_array($rs);
	$entry = array("rv_no"=>"Total","rv_amount" => $row["total"]);
	$jsonData[] = $entry;
	$result["footer"] = $jsonData;

	echo json_encode($result);

?>