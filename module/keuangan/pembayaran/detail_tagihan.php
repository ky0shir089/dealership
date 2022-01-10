<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$offset = ($page-1)*$rows;

$id = $_REQUEST['id'];
$id2 = $_REQUEST['id2'];

$count = "select count(*) from fin_trn_payment a
		left join spuk_dtl b on a.pv_proses_id=b.spuk_dtl_id
		left join unit_titip_jual c on b.spuk_dtl_utj=c.utj_id
		where 
			pv_outlet='$id' and
			pv_no is null and 
			pv_paid_status='N' and
			pv_paid_rek='$id2'
		group by pv_proses_id
		limit $offset,$rows";
$rs = mysqli_query($con,$count);
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0];

$query = "select
			pv_proses_id,
			type_trx,
			utj_no_paket,
			pv_amount,
			pv_scheme,
			(select time(wf_hist_date_process) from wf_history where no_proses=pv_proses_id and wf_hist_seq=4) as waktu
		from fin_trn_payment a
		left join spuk_dtl b on a.pv_proses_id=b.spuk_dtl_id
		left join unit_titip_jual c on b.spuk_dtl_utj=c.utj_id
		where 
			pv_outlet='$id' and
			pv_no is null and 
			pv_paid_status='N' and
			pv_paid_rek='$id2'
		group by pv_proses_id
		limit $offset,$rows";
$rs = mysqli_query($con,$query);
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

$query2 = "select
				sum(pv_amount) as pv_amount
			from fin_trn_payment
			where 
				pv_outlet='$id' and
				pv_no is null and 
				pv_paid_status='N'";
$rs = mysqli_query($con,$query2);
$row = mysqli_fetch_array($rs);
$entry = array("type_trx"=>"Total","pv_amount" => $row["pv_amount"]);
$jsonData[] = $entry;
$result["footer"] = $jsonData;

echo json_encode($result);
?>