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
		spuk_dtl_amount,
		(spuk_dtl_amount*0)+225000 as scheme,
		spuk_dtl_amount + (spuk_dtl_amount*0)+225000 as subtotal
	from spuk_dtl a
	left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
	where 
		spuk_dtl_id='$id' and
		spuk_dtl_status='R'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	$rs = mysqli_query($con,"select sum(spuk_dtl_amount) as total,sum((spuk_dtl_amount*0)+225000) as ttlscheme,sum(spuk_dtl_amount + (spuk_dtl_amount*0)+225000) as subtotal from spuk_dtl where spuk_dtl_id='$id' and spuk_dtl_status='R'");
	$row = mysqli_fetch_array($rs);
	$entry = array("utj_tahun"=>"Total","spuk_dtl_amount" => $row["total"],"scheme" => $row["ttlscheme"],"subtotal" => $row["subtotal"]);
	$jsonData[] = $entry;
	$result["footer"] = $jsonData;

	echo json_encode($result);

?>