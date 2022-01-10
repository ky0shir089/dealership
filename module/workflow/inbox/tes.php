<?php

	include '../../../conn2.php';
	include '../../../cek_session.php';
	
	$query2 = "select no_proses,count(wf_hist_status) as status from wf_history where wf_hist_status='A' group by no_proses";
	$hasil2 = mysqli_query($con,$query2);
	$data2 = mysqli_fetch_array($hasil2);
	$status = $data2['status'];
	
	$query = "select
			wf_hist_no,
			wf_name,
			no_proses,
			nama_titik,
			wf_hist_executor,
			case
				when wf_hist_status='A' then 'APPROVE'
				when wf_hist_status='R' then 'REJECT'
				when wf_hist_status='V' then 'REVISE'
			end as status,
			wf_hist_date_create
		from wf_history a
			join mst_workflow b on a.wf_hist_id=b.wf_id
			join infinity.titik c on a.outlet_proses=c.kode_titik
        where 
			wf_hist_dept='$_SESSION[dept]' and 
			wf_hist_job='$_SESSION[job]' and 
			wf_hist_status is null and
			wf_hist_seq=$status+1";
	$hasil = mysqli_query($con,$query);
	$data = mysqli_fetch_array($hasil);
	

	die($query);
	
?>