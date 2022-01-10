<?php

	include '../../../conn2.php';
	
	$rs = mysqli_query($con,"select count(*) from users a
			left join hr_people_all b on a.user_personid=b.person_id
			join infinity.titik c on a.user_outlet=c.kode_titik");
	$row = mysqli_fetch_row($rs);
	$result["total"] = $row[0];
	$rs = mysqli_query($con,"select
			user_id,
			user_name,
			user_password,
			user_description,
			user_lastlogin,
			user_lastpassword,
			user_enable_sts,
			user_personid,
			person_name,
			user_outlet,
			nama_titik,
			user_ipaddress,
			user_agent
		from users a
			left join hr_people_all b on a.user_personid=b.person_id
			join infinity.titik c on a.user_outlet=c.kode_titik");

	$items = array();
	while($row = mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;

	echo json_encode($result);

?>