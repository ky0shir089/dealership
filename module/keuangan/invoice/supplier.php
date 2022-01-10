<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

if($id == 'S'){
	$rs = mysqli_query($con,"select
			supl_id,
			supl_name
		from mst_suppliers
		where supl_status='Y' and
		supl_id not in (select cust_id from mst_customers)");
} 
if($id == 'C'){
	$rs = mysqli_query($con,"select
		cust_id as supl_id,
		supl_name,
		cust_ktp,
		cust_owner
	from mst_customers a
	join mst_suppliers b on a.cust_id=b.supl_id
	where supl_status='Y'");
}

$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>