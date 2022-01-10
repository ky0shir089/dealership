<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select 
			coa_code,
			coa_description
		from gl_coa
		where 
			coa_parent is not null and
			coa_code!='1120101' and
			coa_code!='1120201'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>