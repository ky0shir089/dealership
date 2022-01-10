<?php

include '../../../conn2.php';

$rs = mysqli_query($con,"select 
							person_id,
							person_name,
							person_outlet,
							nama_titik
						from 
							hr_people_all a 
						join infinity.titik b on a.person_outlet=b.kode_titik
						where person_status='Y' and
						person_id not in (select user_id from users where user_id=person_id)
						order by person_name asc");
$rows = array();
while($row = mysqli_fetch_assoc($rs)){
	$rows[] = $row;
}
echo json_encode($rows);

?>