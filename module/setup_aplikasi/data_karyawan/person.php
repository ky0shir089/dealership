<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select
		person_id,
		person_name,
		person_dept,
		dept_name,
		person_job,
		job_name,
		person_outlet,
		nama_titik
	from 
		hr_people_all a,
		hr_dept_all b,
		hr_mst_job c,
		infinity.titik d
	where
		a.person_dept=b.dept_id and
		a.person_job=c.job_id and
		a.person_outlet=d.kode_titik");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>