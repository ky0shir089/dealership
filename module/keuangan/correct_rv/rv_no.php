<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_GET['id'];

$rs = mysqli_query($con,"select
		rv_received_date,
		rv_no,
		rv_received_from,
		bank_name,
		rv_bank_rek,
		rv_amount
	from fin_trn_rv a
	join mst_rekening_outlet b on a.rv_bank_rek=b.rekout_no
	join mst_bank c on b.rekout_id=c.bank_id
	where rv_no='$id'
	order by rv_no asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>