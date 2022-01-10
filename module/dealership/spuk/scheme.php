<?php
include '../../../conn2.php';

$rs = mysqli_query($con,"select * from mst_scheme where scheme_status='Y'");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>