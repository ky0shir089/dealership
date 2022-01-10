<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$rs = mysqli_query($con,"select * from fin_trn_rv where rv_amount > 0 and rv_status='N'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>