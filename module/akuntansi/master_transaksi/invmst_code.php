<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select 
			invmst_code,
			invmst_desc,
			case
				when invmst_status='Y' then 'ACTIVE'
				else 'INACTIVE'
			end as invmst_status
		from fin_mst_invhdr");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>