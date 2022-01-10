<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$rs = mysqli_query($con,"select
		cabang_bmr,
		kode_titik,
		nama_titik
	from infinity.titik a
	left join infinity.cabang b on a.kode_cabang=b.kode_cabang
	where (id_biz=2 or id_biz is null) and
	a.status='Y'
	order by cabang_bmr asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>