<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$id = $_REQUEST['id'];
	
	$rs = mysqli_query($con,"select
		spuk_dtl_no,
		spuk_dtl_utj,
		utj_no_contract,
		utj_nopol,
		utj_nosin,
		utj_type,
		utj_tahun,
		spuk_dtl_amount
	from spuk_dtl a
	left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
	where spuk_dtl_id='$id'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	$rs = mysqli_query($con,"select sum(spuk_dtl_amount) as total from spuk_dtl where spuk_dtl_id='$id'");
	$row = mysqli_fetch_array($rs);
	$entry = array("utj_tahun"=>"Total","spuk_dtl_amount" => $row["total"]);
	$jsonData[] = $entry;
	$result["footer"] = $jsonData;

	echo json_encode($result);

?>