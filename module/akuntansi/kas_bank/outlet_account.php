<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select
		rekout_no,
		rekout_id,
		bank_name,
		rekout_name,
		rekout_outlet,
		nama_titik,
		rekout_segment,
		rekout_status,
		case
			when rekout_status='Y' then 'ACTIVE'
			when rekout_status='N' then 'INACTIVE'
		end as status
	from mst_rekening_outlet a
	join mst_bank b on a.rekout_id=b.bank_id
	join infinity.titik c on a.rekout_outlet=c.kode_titik");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>