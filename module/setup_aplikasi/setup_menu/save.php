<?php

$module_id = htmlspecialchars(strtoupper($_REQUEST['module_id']));
$module_name = htmlspecialchars(strtoupper($_REQUEST['module_name']));

include '../../../conn2.php';
include '../../../cek_session.php';

mysqli_autocommit($con,FALSE);

$sql = "insert into mst_modules(module_id,module_name,module_create_by,module_created) values('$module_id','$module_name','$_SESSION[uid]',now())";
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