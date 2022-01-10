<?php

$id = $_REQUEST['id'];

include '../../../conn2.php';

$rs = mysqli_query($con,"select
		supl_id,
		supl_name,
		supl_type
	from mst_suppliers
	where 
		supl_id in (select cust_id from mst_customers) and
		supl_type='$id' and
		supl_status='Y'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>