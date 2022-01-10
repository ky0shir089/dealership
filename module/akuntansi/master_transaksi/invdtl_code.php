<?php

include '../../../conn2.php';

$id = $_GET['id'];

$rs = mysqli_query($con,"select 
			invdtl_code,
			coa_description,
			case
				when invdtl_status='Y' then 'ACTIVE'
				else 'INACTIVE'
			end as invdtl_status
		from fin_mst_invdtl a
		join gl_coa b on a.invdtl_code=b.coa_code
		where invmst_code='$id'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>