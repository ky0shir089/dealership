<?php
include '../../../conn2.php';
include '../../../cek_session.php';

//$reff = @$_REQUEST['id'];
$q = isset($_GET['q']) ? $_GET['q'] : '';

$count = "select count(*) from fin_trn_rv 
		where 
			(rv_no like '%$q%' or
			rv_amount like '%$q%') and
			rv_amount > 0 and 
			rv_status='N'";
$rs = mysqli_query($con,$count);
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0];

$query = "select * from fin_trn_rv 
		where 
			(rv_no like '%$q%' or
			rv_amount like '%$q%') and
			rv_amount > 0 and 
			rv_status='N' 
		order by rv_no desc";
$rs = mysqli_query($con,$query);
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

echo json_encode($result);
?>