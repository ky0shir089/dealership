<?php

	include '../../../conn2.php';
	
	$id = $_REQUEST['id'];
	
	$rs = mysqli_query($con,"select count(*) from mst_wf_detail");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			wf_dtl_no,
			wf_dtl_urutan,
			dept_name as wf_dtl_dept,
			job_name as wf_dtl_job
		from mst_wf_detail a
		join hr_dept_all b on a.wf_dtl_dept=b.dept_id
		join hr_mst_job c on a.wf_dtl_job=c.job_id
		where wf_dtl_id='$id'");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>