<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		pv_outlet,
		nama_titik,
		pv_proses_id,
		pv_paid_to,
		supl_id,
		supl_name,
		bank_name,
		pv_paid_rek,
		pv_amount,
		type_trx,
		(select utj_no_paket from spuk_dtl a
			join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
			where spuk_dtl_id=pv_proses_id
			limit 0,1) as utj_no_paket
	from fin_trn_payment a
	join infinity.titik b on a.pv_outlet=b.kode_titik
	join mst_suppliers c on a.pv_paid_to=c.supl_id
	left join mst_rekening d on a.pv_paid_rek=d.rek_no
	left join mst_bank e on d.rek_bank=e.bank_id
	where pv_no='$id'");
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

$query2 = "select
				sum(pv_amount) as pv_amount
			from fin_trn_payment
			where pv_no='$id'";
$rs = mysqli_query($con,$query2);
$row = mysqli_fetch_array($rs);
$entry = array("pv_paid_rek"=>"Total","pv_amount" => $row["pv_amount"]);
$jsonData[] = $entry;
$result["footer"] = $jsonData;

echo json_encode($result);
?>