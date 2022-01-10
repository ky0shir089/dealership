<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		bank_name,
		rek_no,
		rek_name
	from mst_rekening a 
	join mst_bank b on a.rek_bank=b.bank_id
	where rek_cs='$id'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>