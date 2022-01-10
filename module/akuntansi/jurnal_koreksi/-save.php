<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$gl_date = $_REQUEST['gl_date'];
$gl_desc = strtoupper($_REQUEST['gl_desc']);
$gl_month = strtoupper(date('M',strtotime($gl_date)));
$gl_year = date('y',strtotime($gl_date));
$data = json_decode($_POST['data']);

mysqli_autocommit($con,FALSE);

$error = array();

//sequence
$query = "select * from seq_jc where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data2 = mysqli_fetch_array($hasil);
	$tahun = $data2['year'];
	if($tahun < year){
		$seq = '00000001';
	} else {
		$seq = sprintf("%'.08d",$data2['seq']+1);
	}
	$jc_no = $_SESSION['outlet'].date('y').'JC'.$seq;
	$year = date('y');
	$month = strtoupper(date('M'));
}
$sql = "insert into seq_jc(outlet,year,seq) values('$_SESSION[outlet]','$year','$seq')";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

foreach($data as $dt){
	$sql3 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_cr,gl_create_by,gl_created) values('$jc_no','$gl_month','$gl_year','$gl_date','JC','$gl_desc','$dt->gl_segment1','$dt->gl_segment2','$dt->gl_dr','$dt->gl_cr','$_SESSION[uid]',now())";
	$result3 = mysqli_query($con,$sql3);
	if($result3 == false){
		array_push($error,mysqli_error($con));
	}
}

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved','jc_no'=>$jc_no,'create_by'=>$_SESSION['uid']));
}

mysqli_close($con);
?>