<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select 
			rvmst_code,
			rvmst_desc,
			case
				when rvmst_status='Y' then 'ACTIVE'
				else 'INACTIVE'
			end as rvmst_status
		from fin_mst_rvhdr");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>