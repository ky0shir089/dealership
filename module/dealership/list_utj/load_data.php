<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$post = filter_input_array(INPUT_POST);
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
	$offset = ($page-1)*$rows;
	
	$wheres = array();
	$where = "";
	if (isset($post['filterRules']) || !empty($post['filterRules'])) {
		$filterRules = json_decode($post['filterRules'], TRUE);
		foreach ($filterRules as $filter) {
			$field = $filter['field'];
			$value = $filter['value'];
			$wheres[] = "$field like '%$value%'";
		}
	}
	 
	if (count($wheres) > 0) {
		$where = "where (" . implode(") and (", $wheres) . ") and utj_outlet='$_SESSION[outlet]'";
	} else {
		$where = "where utj_outlet='$_SESSION[outlet]'";
	}
	
	$rs = mysqli_query($con,"select count(*) from unit_titip_jual $where");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			utj_id,
			utj_name,
			utj_no_paket,
			utj_no_contract,
			utj_bpkb_name,
			utj_nopol,
			utj_noka,
			utj_nosin,
			utj_type,
			utj_stnk,
			utj_tgl_stnk,
			utj_grade,
			utj_tahun,
			utj_hutang_konsumen,
			utj_ct_date,
			utj_rv_fif,
			(case
				when utj_status='N' then 'NEW'
				when utj_status='D' then 'DRAFT'
				when utj_status='R' then 'REQUEST'
				when utj_status='S' then 'SAVED'
				when utj_status='A' then 'APPROVED'
				when utj_status='P' then 'PAID'
				else 'CANCEL'
			end) as utj_status
		from unit_titip_jual $where
		limit $offset,$rows");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>