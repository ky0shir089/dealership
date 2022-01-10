<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		utj_id,
		utj_no_contract,
		utj_no_paket,
		utj_nopol,
		utj_nosin,
		utj_type,
		utj_tahun,
		utj_hutang_konsumen
	from unit_titip_jual a
	where 
		utj_outlet='$_SESSION[outlet]' and
		utj_status='N' and
		utj_name='$id'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>