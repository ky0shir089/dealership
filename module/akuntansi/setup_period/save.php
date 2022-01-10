<?php

$period_id = strtoupper($_REQUEST['period_id']);
$period_num = $_REQUEST['period_num'];
$period_start_date = $_REQUEST['period_start_date'];
$period_end_date = $_REQUEST['period_end_date'];

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into gl_period(period_id,period_num,period_start_date,period_end_date,period_create_by,period_created) values('$period_id','$period_num','$period_start_date','$period_end_date','$_SESSION[uid]',now())";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved'));
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>