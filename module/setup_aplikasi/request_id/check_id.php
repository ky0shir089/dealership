<?php
include '../../../conn2.php';

$nama = $_POST['nama'];
$tempat = $_POST['tempat'];
$tgl = $_POST['tgl'];

$sql = "select req_nama from hr_people_all a left join request_id b on a.person_name=b.req_nama where req_nama='$nama' and req_tempat_lahir='$tempat' and req_tanggal_lahir='$tgl'";
$result = mysqli_query($con,$sql);

if(mysqli_num_rows($result) == 0){
	echo 0;
} else {
	echo 1;
}
?>