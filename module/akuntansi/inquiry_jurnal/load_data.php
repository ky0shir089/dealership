<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$start = isset($_POST['start']) ? $_POST['start'] : date('Y-m-d');
$end = isset($_POST['end']) ? $_POST['end'] : date('Y-m-d');
$proses = isset($_POST['proses']) ? $_POST['proses'] : '';

$offset = ($page-1)*$rows;

$rs = mysqli_query($con,"select count(*) from gl_journal  a
	join gl_coa b on a.gl_segment2=b.coa_code
	where gl_no like '%$proses%' and
	(gl_date >= '$start' and gl_date <= '$end')");
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0];

$rs = mysqli_query($con,"select *
	from gl_journal a
	join gl_coa b on a.gl_segment2=b.coa_code
	where gl_no like '%$proses%' and
	(gl_date between '$start' and '$end')
	order by gl_id asc");
$items = array();
while($row = mysqli_fetch_object($rs)){
	array_push($items, $row);
}
$result["rows"] = $items;

echo json_encode($result);
?>