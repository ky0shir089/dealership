<?php

	include '../../../conn2.php';
	
	$rs = mysqli_query($con,"select count(*) from users inner join titik on users.id_titik=titik.kode_titik");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
	users.uid,
	users.username,
	users.id_titik,
	titik.nama_titik,
	users.date_create,
	users.create_by,
	users.lastlogin,
	users.ipaddress,
	users.user_agent,
	case
		when users.status='Y' then 'ACTIVE'
		when users.status='N' then 'NOT ACTIVE'
	end as status
	from users
	inner join titik on users.id_titik=titik.kode_titik");
	
	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>