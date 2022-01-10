<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$rs = mysqli_query($con,"select
		coa_code,
		coa_description
	from gl_coa");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>