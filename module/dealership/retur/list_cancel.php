<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		cancel_utj as spuk_dtl_utj,
		utj_no_paket,
		utj_no_contract,
		utj_nopol,
		utj_nosin,
		utj_type,
		utj_tahun,
		utj_hutang_konsumen,
		cancel_utj_scheme as spuk_dtl_scheme,
		cancel_hbm as spuk_dtl_total
	from cancel_unit a
		left join unit_titip_jual b on a.cancel_utj=b.utj_id 
	where 
		cancel_id='$id'");
		
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

$rs = mysqli_query($con,"select 
		sum(utj_hutang_konsumen) as total,
		sum(cancel_utj_scheme) as scheme,
		sum(cancel_hbm) as subtotal 
	from cancel_unit a
		left join unit_titip_jual b on a.cancel_utj=b.utj_id
	where 
		cancel_id='$id'");
$row = mysqli_fetch_array($rs);
$entry = array("utj_tahun"=>"Total","utj_hutang_konsumen" => $row["total"],"spuk_dtl_scheme" => $row["scheme"],"spuk_dtl_total" => $row["subtotal"]);
$jsonData[] = $entry;
$result["footer"] = $jsonData;
	
echo json_encode($result);

?>