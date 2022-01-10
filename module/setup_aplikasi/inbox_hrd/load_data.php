<?php

	include '../../../conn2.php';
	
	$rs = mysqli_query($con,"select count(*) from request_id");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
		req_seq,
		req_id,
		req_nama,
		req_tempat_lahir,
		req_tanggal_lahir,
		dept_name,
		job_name,
		req_outlet,
		nama_titik,
		req_created,
		case
			when req_status = 'N' then 'NEW'
			else 'APPROVE'
		end as req_status
		from request_id a
		left join hr_people_all b on a.req_id=b.person_id
		left join hr_dept_all c on b.person_dept=c.dept_id
		left join hr_mst_job d on b.person_job=d.job_id
		left join infinity.titik e on a.req_outlet=e.kode_titik
		where req_status = 'N' and req_cat='K'
		order by req_seq desc");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>