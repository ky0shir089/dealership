<?php

$id = $_REQUEST['id'];

include '../../../conn2.php';

$rs = mysqli_query($con,"select
	cabang_bmr,
	access_no,
	access_outlet,
	nama_titik,
	case
		when access_status='Y' then 'ACTIVE'
		else 'INACTIVE'
	end as access_status
	from dealership.outlet_access a
	left join infinity.titik b on a.access_outlet=b.kode_titik
	left join infinity.cabang c on b.kode_cabang=c.kode_cabang
	where access_user='$id'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>