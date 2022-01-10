<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$rs = mysqli_query($con,"select
		rekout_no,
		rekout_name,
		bank_name,
		rekout_segment
	from mst_rekening_outlet a 
	join mst_bank b on a.rekout_id=b.bank_id
	where rekout_outlet='$_SESSION[outlet]'
	and rekout_status='Y'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>