<?php
include '../../../conn2.php';
include '../../../cek_session.php';
require '../../../plugins/PHPMailer-master/PHPMailerAutoload.php';

$spuk_id = $_REQUEST['spuk_id'];
$outlet = substr($spuk_id,0,5);
$supl_name = $_REQUEST['supl_name'];
$spuk_total_scheme = $_REQUEST['spuk_total_scheme'];
$spuk_total_hutang = $_REQUEST['spuk_total_hutang'];
$spuk_subtotal = $_REQUEST['spuk_subtotal'];

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select wf_hist_id,count(no_proses) as seq from wf_history where no_proses='$spuk_id' and wf_hist_status='A'";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
	$wf_id = $data['wf_hist_id'];
	$seq = $data['seq']+1;
}

$query2 = "select count(wf_dtl_urutan) as urutan from mst_wf_detail where wf_dtl_id='$wf_id'";
$hasil2 = mysqli_query($con,$query2);
if($hasil2 == false){
	array_push($error,mysqli_error($con));
} else {
	$data2 = mysqli_fetch_array($hasil2);
	$urutan = $data2['urutan'];
}


$query3 = "select sum(rv_amount) as total_rv from repayment_rv a join fin_trn_rv b on a.rrv_no_rv=b.rv_no where rrv_spuk_id='$spuk_id'";
$hasil3 = mysqli_query($con,$query3);
if($hasil3 == false){
	array_push($error,mysqli_error($con));
} else {
	$data3 = mysqli_fetch_array($hasil3);
	$total_rv = $data3['total_rv'];
}

$sql = "update wf_history set wf_hist_executor='$_SESSION[uid]',wf_hist_status='A',wf_hist_date_process=now() where no_proses='$spuk_id' and wf_hist_seq='$seq'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql2 = "update wf_process set jml_approve=jml_approve+1 where wf_process_no='$spuk_id'";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

if($seq == $urutan){
	$sql3 = "update spuk_hdr set spuk_status='A' where spuk_id='$spuk_id'";
	$result3 = mysqli_query($con,$sql3);
	if($result3 == false){
		array_push($error,mysqli_error($con));
	}

	$query4 = "select spuk_dtl_utj from spuk_dtl where spuk_dtl_id='$spuk_id'";
	$hasil4 = mysqli_query($con,$query4);
	while($data4 = mysqli_fetch_array($hasil4)){
		$utj = $data4['spuk_dtl_utj'];
		$sql4 = "update unit_titip_jual set utj_status='A' where utj_id='$utj'";
		$result4 = mysqli_query($con,$sql4);
		if($result4 == false){
			array_push($error,mysqli_error($con));
		}
	}
	
	/* $sql4 = "update unit_titip_jual set utj_status='A' where utj_id in (select spuk_dtl_utj from spuk_dtl where spuk_dtl_id='$spuk_id')";
	$result4 = mysqli_query($con,$sql4);
	if($result4 == false){
		array_push($error,mysqli_error($con));
	} */
	
	$sql5 = "insert into fin_trn_payment(pv_proses_id,pv_outlet,pv_amount,pv_scheme,pv_rv_amount,pv_calculate,type_trx) values('$spuk_id','$outlet','$spuk_total_hutang','$spuk_total_scheme','$total_rv','$spuk_total_hutang','TRX02')";
	$result5 = mysqli_query($con,$sql5);
	if($result5 == false){
		array_push($error,mysqli_error($con));
	}
	
	/* $query4 = "select
					nama_titik,
					utj_no_paket
				from spuk_hdr a
				join infinity.titik b on a.spuk_outlet=b.kode_titik
				join spuk_dtl c on a.spuk_id=c.spuk_dtl_id
				join unit_titip_jual d on c.spuk_dtl_utj=d.utj_id
				where spuk_dtl_id='$spuk_id'
				group by spuk_id";
	$hasil4 = mysqli_query($con,$query4);
	if($hasil4 == false){
		array_push($error,mysqli_error($con));
	} else {
		$data4 = mysqli_fetch_array($hasil4);
		$nama_titik = $data4['nama_titik'];
		$utj_no_paket = $data4['utj_no_paket'];
	}
	
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "bmr.dealership@gmail.com";
	$mail->Password = "raharjamotor";
	$mail->setFrom('bmr.dealership@gmail.com', 'Dealership');
	$mail->addAddress('ade.yusuf@raharjamotor.com');
	$mail->AddCC('finance.group@raharjamotor.com');
	$mail->Subject = '[DEALERSHIP] PELUNASAN SPUK';
	$mail->msgHTML('[DEALERSHIP] PELUNASAN '.$spuk_id.'<br><br> 
		Cabang: '.$nama_titik.'<br><br>
		No Paket Distribusi: '.$utj_no_paket.'<br><br>
		Buyer: '.$supl_name.'<br><br>
		HBM: '.format_rupiah($spuk_total_hutang).'<br><br>
		OTR: '.format_rupiah($spuk_subtotal).'<br><br>
		Silahkan lakukan pelunasan untuk NO SPUK tersebut.<br><br>
		Terima Kasih.');
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} */
}

if(count($error) > 0){
	$errors = join("<br>", $error);
	echo json_encode(array('errorMsg'=>$errors));
	mysqli_rollback($con);
}
else {
	mysqli_commit($con);
	echo json_encode(array('success'=>'Data Approved'));
}

mysqli_close($con);
?>