<?php
include '../../../conn2.php';
include '../../../cek_session.php';

$cancel_id = $_REQUEST['cancel_id'];
$cancel_spuk_id = $_REQUEST['cancel_spuk_id'];
$spuk_outlet = $_REQUEST['spuk_outlet'];
$month_name = strtoupper(date('M'));
$year = date('y');

mysqli_autocommit($con,FALSE);

$error = array();

$query = "select wf_hist_id,count(no_proses) as seq from wf_history where no_proses='$cancel_id' and wf_hist_status='A'";
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

$sql = "update wf_history set wf_hist_executor='$_SESSION[uid]',wf_hist_status='A',wf_hist_date_process=now() where no_proses='$cancel_id' and wf_hist_seq='$seq'";
$result = mysqli_query($con,$sql);
if($result == false){
	array_push($error,mysqli_error($con));
}

$sql2 = "update wf_process set jml_approve=jml_approve+1 where wf_process_no='$cancel_id'";
$result2 = mysqli_query($con,$sql2);
if($result2 == false){
	array_push($error,mysqli_error($con));
}

if($seq == $urutan){
	$query3 = "select 
					cancel_utj,
					utj_nopol
				from cancel_unit a 
				left join unit_titip_jual b on a.cancel_utj=b.utj_id
				where cancel_id='$cancel_id'";
	$hasil3 = mysqli_query($con,$query3);
	while($data3 = mysqli_fetch_array($hasil3)){
		$sql3 = "delete from spuk_dtl where spuk_dtl_utj='$data3[cancel_utj]'";
		$result3 = mysqli_query($con,$sql3);
		if($result3 == false){
			array_push($error,mysqli_error($con));
		}
		
		/*$query4= "select * from
					(select cancel_hbm,cancel_utj_scheme from cancel_unit where cancel_utj='$data3[cancel_utj]') as data,
					(select margin_amount as margin from mst_margin where margin_id='M1') as margin,
					(select margin_amount as titipan_operasional from mst_margin where margin_id='M2') as titipan_operasional,
					(select rv_no,rv_scheme from fin_trn_rv a join repayment_rv b on a.rv_no=b.rrv_no_rv where rrv_spuk_id='$cancel_spuk_id' and rv_scheme > 0) as rv";
		$hasil4 = mysqli_query($con,$query4);*/
		$query4 = "select cancel_hbm,cancel_utj_scheme from cancel_unit where cancel_utj='$data3[cancel_utj]'";
		$data4 = mysqli_fetch_array($query4);
		//$titipan_operasional = $data4['titipan_operasional'];
		//$administrasi_mokas = ($data4['cancel_utj_scheme']-$titipan_operasional)/1.1;
		//$hutang_ppn = $administrasi_mokas*0.1;
		$margin = $data4['cancel_utj_scheme'];
		$refund = $data4['cancel_hbm']+$data4['cancel_utj_scheme'];
		//$rv_no = $data4['rv_no'];
		
		$cancel_desc = "PEMBATALAN SPUK ".$cancel_spuk_id." NOPOL: ".$data3['utj_nopol'];

		$sql9 = "update fin_trn_rv set
					rv_amount='$refund',
					rv_scheme=rv_scheme-'$margin',
					rv_used=rv_used-'$data4[cancel_hbm]',
					rv_status='N'
				where rv_no='$rv_no'";
		$result9 = mysqli_query($con,$sql9);
		if($result9 == false){
			array_push($error,mysqli_error($con));
		}

		//Jurnal Selisih Margin
		/* $sql6 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_create_by,gl_created) values('$cancel_id','$month_name','$year',now(),'JV','$cancel_desc','$_SESSION[outlet]','3220701','$hutang_ppn','$_SESSION[uid]',now())";
		$result6 = mysqli_query($con,$sql6);
		if($result6 == false){
			array_push($error,mysqli_error($con));
		}
		
		$sql5 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_create_by,gl_created) values('$cancel_id','$month_name','$year',now(),'JV','$cancel_desc','$spuk_outlet','6210101','$administrasi_mokas','$_SESSION[uid]',now())";
		$result5 = mysqli_query($con,$sql5);
		if($result5 == false){
			array_push($error,mysqli_error($con));
		} */
		
		$sql4 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_dr,gl_create_by,gl_created) values('$cancel_id','$month_name','$year',now(),'JV','$cancel_desc','$spuk_outlet','3310101','$margin','$_SESSION[uid]',now())";
		$result4 = mysqli_query($con,$sql4);
		if($result4 == false){
			array_push($error,mysqli_error($con));
		}

		$sql7 = "insert into gl_journal(gl_no,gl_period_month,gl_period_year,gl_date,gl_type,gl_desc,gl_segment1,gl_segment2,gl_cr,gl_create_by,gl_created) values('$cancel_id','$month_name','$year',now(),'JV','$cancel_desc','$_SESSION[outlet]','3310201','$margin','$_SESSION[uid]',now())";
		$result7 = mysqli_query($con,$sql7);
		if($result7 == false){
			array_push($error,mysqli_error($con));
		}
		
		$sql8 = "update cancel_unit set cancel_status='C' where cancel_id='$cancel_id'";
		$result8 = mysqli_query($con,$sql8);
		if($result8 == false){
			array_push($error,mysqli_error($con));
		}
	}
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