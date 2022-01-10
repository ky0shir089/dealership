<?php

$id = $_REQUEST['id'];

include '../../../conn2.php';
include '../../../cek_session.php';

$sql = "select kode_cabang from infinity.titik where kode_titik='$_SESSION[outlet]'";
$result = mysqli_query($con,$sql);
$data = mysqli_fetch_array($result);

if($_SESSION['outlet'] == 10000){
	$rs = mysqli_query($con,"select
			cabang_bmr,
			kode_titik,
			nama_titik
		from infinity.titik a
		left join infinity.cabang b on a.kode_cabang=b.kode_cabang
		where (id_biz=1 or id_biz=2 or id_biz is null) and
		a.status='Y' and 
		kode_titik not in (select access_outlet from dealership.outlet_access where access_user='$id')
		order by cabang_bmr asc");
} else {
	$rs = mysqli_query($con,"select 
			cabang_bmr,
			kode_titik,
			nama_titik
		from infinity.titik a
		left join infinity.cabang b on a.kode_cabang=b.kode_cabang
		where (id_biz=1 or id_biz=2 or id_biz is null) and 
		a.kode_cabang='$data[kode_cabang]' and 
		a.status='Y' and 
		kode_titik not in (select access_outlet from dealership.outlet_access where access_user='$id')
		order by nama_titik asc");
}
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>