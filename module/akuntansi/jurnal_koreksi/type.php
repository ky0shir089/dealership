<?php

include '../../../conn2.php';

$id = $_REQUEST['id'];

if($id == 'BANK'){
	$rs = mysqli_query($con,"select 
			correct_code as coa_code,
			coa_description
		from fin_mst_correct a
		join gl_coa b on a.correct_code=b.coa_code
		where 
			correct_type='B' and
			correct_status='Y'
		order by coa_code asc");
} else {
	$rs = mysqli_query($con,"select 
			correct_code as coa_code,
			coa_description
		from fin_mst_correct a
		join gl_coa b on a.correct_code=b.coa_code
		where
			correct_type='L' and
			correct_status='Y'
		order by coa_code asc");
}
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>