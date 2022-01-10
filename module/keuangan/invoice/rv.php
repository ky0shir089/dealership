<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select rv_no,rv_scheme,titipan_ops,rv_scheme-titipan_ops as saldo from
	(
		(select 
			rv_no,
			rv_scheme,
			(select sum(rv_sch_amount) from fin_rv_scheme where rv_sch_no=rv_no and rv_sch_type='TO' group by rv_sch_no) as titipan_ops
		from 
			fin_trn_rv 
		where 
			rv_segment2='3310201' and 
			rv_scheme!=0 and 
			rv_status='C') as s1
	)");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	$rs = mysqli_query($con,"select sum(rv_scheme) as total from fin_trn_rv where rv_segment2='3310201' and rv_scheme!=0 and rv_status='C'");
	$row = mysqli_fetch_array($rs);
	$entry = array("rv_no"=>"Total","rv_scheme" => $row["total"]);
	$jsonData[] = $entry;
	$result["footer"] = $jsonData;

	echo json_encode($result);

?>