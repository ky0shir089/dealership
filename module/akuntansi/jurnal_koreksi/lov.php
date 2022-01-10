<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$count = "select count(*) from gl_journal
		where gl_type='JC'
		group by gl_no";
$rs = mysqli_query($con,$count);
$row = mysqli_fetch_row($rs);
$result["total"] = $row[0];

$query = "select
			gl_no,
			gl_date,
			gl_desc,
			gl_create_by
		from gl_journal
		where gl_type='JC'
		group by gl_no
		order by gl_no desc";
$rs = mysqli_query($con,$query);
$items = array();
while($row = mysqli_fetch_assoc($rs)){
	$items[] = $row;
}
echo json_encode($items);

?>