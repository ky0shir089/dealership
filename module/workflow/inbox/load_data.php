<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$rs = mysqli_query($con,"select count(*) from wf_history a
			join mst_workflow b on a.wf_hist_id=b.wf_id
			join infinity.titik c on a.outlet_proses=c.kode_titik
			join wf_process d on a.no_proses=d.wf_process_no
        where 
			wf_hist_dept='$_SESSION[dept]' and 
			wf_hist_job='$_SESSION[job]' and
			wf_hist_status is null and
			wf_hist_seq=jml_approve+1");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	
	$rs = mysqli_query($con,"select
			a.wf_hist_no,
			wf_id,
			wf_name,
			no_proses,
			nama_titik,
			(select wf_hist_executor from wf_history where wf_hist_no=a.wf_hist_no-1) as wf_hist_executor,
			(select
				case
					when wf_hist_status='A' then 'APPROVE'
					when wf_hist_status='R' then 'REJECT'
					when wf_hist_status='V' then 'REVISE'
				end
			from wf_history where wf_hist_no=a.wf_hist_no-1) as status,
			(select wf_hist_date_process from wf_history where wf_hist_no=a.wf_hist_no-1) as wf_hist_date_process
		from wf_history a
			join mst_workflow b on a.wf_hist_id=b.wf_id
			join infinity.titik c on a.outlet_proses=c.kode_titik
			join wf_process d on a.no_proses=d.wf_process_no
        where 
			wf_hist_dept='$_SESSION[dept]' and 
			wf_hist_job='$_SESSION[job]' and
			wf_hist_status is null and
			wf_hist_seq=jml_approve+1
		order by no_proses desc");
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>