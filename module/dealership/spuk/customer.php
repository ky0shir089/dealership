<?php

$id = $_REQUEST['id'];

include '../../../conn2.php';

$rs = mysqli_query($con,"select
		cust_id,
		supl_name,
		cust_ktp,
		cust_owner
	from mst_customers a
	join mst_suppliers b on a.cust_id=b.supl_id
	where 
		supl_type='$id' and
		supl_status='Y'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>