<?php

include '../../../conn2.php';
include '../../../cek_session.php';

$id = $_REQUEST['id'];

$rs = mysqli_query($con,"select
		invdtl_code,
		coa_description
	from fin_mst_invdtl a
	join gl_coa b on a.invdtl_code=b.coa_code
	where invmst_code='$id'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>