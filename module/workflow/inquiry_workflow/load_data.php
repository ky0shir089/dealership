<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	if($_SESSION['outlet'] == 10000){
		$outlet = '';
	} else {
		$outlet = $_SESSION['outlet'];
	}
	
	$rs = mysqli_query($con,"select count(*) from wf_history a
			join mst_workflow b on a.wf_hist_id=b.wf_id
			join infinity.titik c on a.outlet_proses=c.kode_titik
			join hr_mst_job d on a.wf_hist_job=d.job_id
        where 
			wf_hist_status is null and
			outlet_proses like '%$outlet%'
		group by no_proses");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			a.wf_hist_no,
			wf_name,
			no_proses,
			nama_titik,
			wf_hist_dept,
			job_name,
			(case
				when wf_hist_status is null then 'RUNNING'
				else 'DONE'
			end) as wf_hist_status,
			(select wf_hist_date_process from wf_history where wf_hist_no=a.wf_hist_no-1) as wf_hist_date_process
		from wf_history a
			join mst_workflow b on a.wf_hist_id=b.wf_id
			join infinity.titik c on a.outlet_proses=c.kode_titik
			join hr_mst_job d on a.wf_hist_job=d.job_id
        where 
			outlet_proses like '%$outlet%'
		order by wf_hist_date_process,no_proses desc");
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>