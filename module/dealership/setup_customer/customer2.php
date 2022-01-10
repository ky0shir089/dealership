<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select 
		supl_id,
		supl_name,
		supl_type,
		(case
			when supl_type = 'I' then 'INDIVIDU'
			when supl_type = 'C' then 'COMPANY'
			else 'PEDAGANG'
		end) as type,
		(case
			when supl_status = 'Y' then 'ACTIVE'
			else 'INACTIVE'
		end) as supl_status
	from mst_suppliers
	where supl_id not in (select cust_id from mst_customers)");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>