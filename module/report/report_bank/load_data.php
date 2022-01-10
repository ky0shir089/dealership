<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$start = isset($_POST['start']) ? $_POST['start'] : date('Y-m-d');
	$end = isset($_POST['end']) ? $_POST['end'] : date('Y-m-d');
	$offset = ($page-1)*$rows;
	
	$rs = mysqli_query($con,"select count(*) from unit_titip_jual a
			left join infinity.titik b on a.utj_outlet=b.kode_titik
			left join spuk_dtl c on a.utj_id=c.spuk_dtl_utj");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			utj_created,
			nama_titik,
			utj_no_contract,
			utj_type,
			utj_tahun,
			spuk_dtl_total,
			utj_hutang_konsumen,
			spuk_dtl_scheme
		from 
			unit_titip_jual a
			left join infinity.titik b on a.utj_outlet=b.kode_titik
			left join spuk_dtl c on a.utj_id=c.spuk_dtl_utj
		limit $offset,$rows");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>