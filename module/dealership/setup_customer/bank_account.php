<?php

$id = $_REQUEST['id'];

include '../../../conn2.php';

$rs = mysqli_query($con,"select
		rek_id,
		rek_bank,
		bank_name,
		rek_no,
		rek_name
	from 
		mst_rekening a,
		mst_bank b
	where 
		a.rek_bank=b.bank_id and
		rek_cs='$id'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>