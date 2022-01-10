<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select menu_id,menu_name from mst_menus where is_aktif=1 order by menu_id asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>