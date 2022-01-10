<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		spuk_dtl_utj,
		utj_no_contract,
		utj_nopol,
		utj_nosin,
		utj_type,
		utj_tahun,
		utj_hutang_konsumen,
		scheme_amount,
		spuk_dtl_total
	from spuk_dtl a
		left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id 
		left join spuk_hdr c on a.spuk_dtl_id=c.spuk_id
		left join mst_scheme d on c.spuk_scheme=d.scheme_id
	where 
		spuk_dtl_id='$id'");
		
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

$rs = mysqli_query($con,"select 
		sum(utj_hutang_konsumen) as total,
		sum(scheme_amount) as scheme,
		sum(spuk_dtl_total) as subtotal 
	from spuk_dtl a
		left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
		left join spuk_hdr c on a.spuk_dtl_id=c.spuk_id
		left join mst_scheme d on c.spuk_scheme=d.scheme_id
	where 
		spuk_dtl_id='$id'");
$row = mysqli_fetch_array($rs);
$entry = array("utj_tahun"=>"Total","utj_hutang_konsumen" => $row["total"],"scheme_amount" => $row["scheme"],"spuk_dtl_total" => $row["subtotal"]);
$jsonData[] = $entry;
$result["footer"] = $jsonData;
	
echo json_encode($result);

?>