<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$id = $_REQUEST['id'];
	
	$rs = mysqli_query($con,"select
		rrv_no_rv,
		rv_amount 
	from repayment_rv a 
	join fin_trn_rv b on a.rrv_no_rv=b.rv_no
	where rrv_pln_id='$id'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	$rs = mysqli_query($con,"select sum(rv_amount) as total from repayment_rv a join fin_trn_rv b on a.rrv_no_rv=b.rv_no where rrv_pln_id='$id'");
	$row = mysqli_fetch_array($rs);
	$entry = array("rrv_no_rv"=>"Total","rv_amount" => $row["total"]);
	$jsonData[] = $entry;
	$result["footer"] = $jsonData;

	echo json_encode($result);

?>