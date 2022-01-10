<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
$offset = ($page-1)*$rows;

$id = @$_REQUEST['id'];

/* $count = "select count(*) from fin_trn_payment a
		join infinity.titik b on a.pv_outlet=b.kode_titik
		join mst_suppliers c on a.pv_paid_to=c.supl_id
		left join mst_rekening d on a.pv_paid_rek=d.rek_no
		left join mst_bank e on d.rek_bank=e.bank_id
		where 
			pv_outlet like '%$id%' and
			pv_no is null and 
			pv_paid_status='N'
		group by pv_outlet,bank_name,pv_paid_rek
		limit $offset,$rows";
$rs = mysqli_query($con,$count);
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0]; */

$query = "select
			pv_outlet,
			nama_titik,
			type_trx,
			pv_proses_id,
			(select utj_no_paket from spuk_dtl a
				join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
				where spuk_dtl_id=pv_proses_id
				limit 0,1) as utj_no_paket,
			pv_paid_to,
			supl_id,
			supl_name,
			bank_name,
			pv_paid_rek,
			sum(pv_amount) as pv_amount,
			(select time(wf_hist_date_process) from wf_history where no_proses=pv_proses_id and wf_hist_seq=4) as waktu
		from fin_trn_payment a
		join infinity.titik b on a.pv_outlet=b.kode_titik
		join mst_suppliers c on a.pv_paid_to=c.supl_id
		left join mst_rekening d on a.pv_paid_rek=d.rek_no
		left join mst_bank e on d.rek_bank=e.bank_id
		where 
			pv_outlet like '%$id%' and
			pv_no is null and 
			pv_paid_status='N'
		group by pv_outlet,bank_name,pv_paid_rek
		order by waktu desc
		limit $offset,$rows";
$rs = mysqli_query($con,$query);
$total = mysqli_num_rows($rs);
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["total"] = $total;
$result["rows"] = $items;

$query2 = "select
				sum(pv_amount) as pv_amount
			from fin_trn_payment
			where 
				pv_outlet like '%$id%' and
				pv_no is null and 
				pv_paid_status='N'";
$rs = mysqli_query($con,$query2);
$row = mysqli_fetch_array($rs);
$entry = array("pv_paid_rek"=>"Total","pv_amount" => $row["pv_amount"]);
$jsonData[] = $entry;
$result["footer"] = $jsonData;

echo json_encode($result);
?>