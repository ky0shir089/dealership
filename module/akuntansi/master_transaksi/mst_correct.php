<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select 
			correct_code,
			coa_description,
			case
				when correct_status='Y' then 'ACTIVE'
				else 'INACTIVE'
			end as correct_status
		from fin_mst_correct a
		join gl_coa b on a.correct_code=b.coa_code");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>