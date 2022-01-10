<?php

$req_nama = htmlspecialchars(strtoupper($_REQUEST['req_nama']));
$req_tempat_lahir = htmlspecialchars(strtoupper($_REQUEST['req_tempat_lahir']));
$req_tanggal_lahir = htmlspecialchars($_REQUEST['req_tanggal_lahir']);
$req_outlet = htmlspecialchars($_REQUEST['req_outlet']);
$cat_id = htmlspecialchars($_REQUEST['cat_id']);

include '../../../conn2.php';
include '../../../cek_session.php';
require '../../../plugins/PHPMailer-master/PHPMailerAutoload.php';

mysqli_autocommit($con,FALSE);

$query = "select substr(req_seq,4) as req_seq from request_id order by req_seq desc limit 0,1";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$seq = sprintf("%'.03d",$data['req_seq']+1);
$req_seq = 'REQ'.$seq;

$sql = "insert into request_id(req_seq,req_nama,req_tempat_lahir,req_tanggal_lahir,req_cat,req_outlet,req_create_by,req_created) values('$req_seq','$req_nama','$req_tempat_lahir','$req_tanggal_lahir','$cat_id','$req_outlet','$_SESSION[uid]',now())";
$result = mysqli_query($con,$sql);

if($result){
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Saved'));
	
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "bmr.dealership@gmail.com";
	$mail->Password = "raharjamotor";
	$mail->setFrom('bmr.dealership@gmail.com', 'Dealership');
	if($cat_id == 'K'){
		$mail->addAddress('alfiansyah@raharja-motor.com');
	} else {
		$mail->addAddress('prima.aulia@raharja-motor.com');
	}
	$mail->Subject = '[Dealership] Pengajuan ID Sistem Dealership';
	$mail->msgHTML('[REQUEST ID] Dealership System <br><br> 
		Nama: '.$req_nama.'<br>
		Outlet: '.$_SESSION['outlet'].'<br><br>
		Silahkan login untuk melakukan approval terhadap pengajuan ID tersebut <br><br>
		Terima Kasih');
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
} else {
	echo json_encode(array('errorMsg'=>mysqli_error($con)));
	mysqli_rollback($con);
}

mysqli_close($con);
?>