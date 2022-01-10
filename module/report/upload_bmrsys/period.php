<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"SELECT
		period_id,
		date_format(period_end_date,'%b') as period_month,
		date_format(period_end_date,'%y') as period_year,
		period_end_date
	from gl_period order by period_created desc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>