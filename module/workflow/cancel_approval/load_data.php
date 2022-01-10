<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select count(*) from wf_history a
			join mst_workflow b on a.wf_hist_id=b.wf_id
			join infinity.titik c on a.outlet_proses=c.kode_titik
		where no_proses not in (select spuk_id from spuk_hdr where spuk_status='P' or spuk_status='J')
		group by no_proses");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			wf_hist_no,
			wf_name,
			no_proses,
			nama_titik,
			spuk_subtotal,
			wf_hist_date_create
		from wf_history a
			join mst_workflow b on a.wf_hist_id=b.wf_id
			join infinity.titik c on a.outlet_proses=c.kode_titik
			join spuk_hdr d on a.no_proses=d.spuk_id
		where no_proses not in (select spuk_id from spuk_hdr where spuk_status='P' or spuk_status='J')
		group by no_proses");
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>