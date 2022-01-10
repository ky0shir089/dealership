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
		req_outlet,
		nama_titik,
		req_created,
		req_updated,
		case
			when req_status = 'N' then 'NEW'
			when req_status = 'R' then 'REJECT'
			else 'APPROVE'
		end as req_status,
		req_reason
		from request_id a
		join infinity.titik b on a.req_outlet=b.kode_titik
		order by req_seq desc");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>