<?php
include '../../../pdo.php';
session_start();

$spuk_id = $_REQUEST['spuk_id'];
$outlet = substr($spuk_id,0,5);
$supl_name = $_REQUEST['supl_name'];
$spuk_total_scheme = $_REQUEST['spuk_total_scheme'];
$spuk_total_hutang = $_REQUEST['spuk_total_hutang'];
$spuk_subtotal = $_REQUEST['spuk_subtotal'];

try
{
	$pdo->beginTransaction();
	
	$query = "select 
				wf_hist_id,
				count(no_proses) as seq 
			from wf_history 
			where 
				no_proses = ? and 
				wf_hist_status = ?";
	$stmt = $pdo->prepare($query);
	$stmt->execute([
		$spuk_id,
		'A'
	]);
	$data = $stmt->fetch();
	$wf_id = $data['wf_hist_id'];
	$seq = $data['seq']+1;
	
	$query2 = "select 
				count(wf_dtl_urutan) as urutan 
			from mst_wf_detail 
			where wf_dtl_id = ?";
	$stmt2 = $pdo->prepare($query2);
	$stmt2->execute([
		$wf_id,
	]);
	$data2 = $stmt2->fetch();
	$urutan = $data2['urutan'];
	
	$query3 = "select sum(rrv_rv_amount) as total_rv 
				from repayment_rv
			where 
				rrv_spuk_id = ?";
	$stmt3 = $pdo->prepare($query3);
	$stmt3->execute([
		$spuk_id,
	]);
	$data3 = $stmt3->fetch();
	$total_rv = $data3['total_rv'];
	
	$sql = "update wf_history set 
				wf_hist_executor = ?,
				wf_hist_status = ?,
				wf_hist_date_process = now() 
			where 
				no_proses = ? and 
				wf_hist_seq = ?";
	$stmt4 = $pdo->prepare($sql);
	$stmt4->execute([
		$_SESSION['uid'],
		'A',
		$spuk_id,
		$seq
	]);
	
	$sql2 = "update wf_process set 
				jml_approve = jml_approve+1 
			where wf_process_no = ?";
	$stmt5 = $pdo->prepare($sql2);
	$stmt5->execute([
		$spuk_id
	]);
	
	if($seq == $urutan){
		$sql3 = "update spuk_hdr set 
					spuk_status = ? 
				where 
				spuk_id = ?";
		$stmt6 = $pdo->prepare($sql3);
		$stmt6->execute([
			'A',
			$spuk_id
		]);
		
		$query4 = "select 
					spuk_dtl_utj 
				from spuk_dtl 
				where 
					spuk_dtl_id = ?";
		$stmt7 = $pdo->prepare($query4);
		$stmt7->execute([
			$spuk_id,
		]);
		while($data4 = $stmt7->fetch())
		{
			$utj = $data4['spuk_dtl_utj'];
			
			$sql4 = "update unit_titip_jual set 
						utj_status = ?
					where utj_id = ?";
			$stmt8 = $pdo->prepare($sql4);
			$stmt8->execute([
				'A',
				$utj
			]);
		}
		
		$sql5 = "insert into fin_trn_payment(
					pv_proses_id,
					pv_outlet,
					pv_amount,
					pv_scheme,
					pv_rv_amount,
					pv_calculate,
					type_trx) 
				values(
					?,
					?,
					?,
					?,
					?,
					?,
					?)";
		$stmt9 = $pdo->prepare($sql5);
		$stmt9->execute([
			$spuk_id,
			$outlet,
			$spuk_total_hutang,
			$spuk_total_scheme,
			$total_rv,
			$spuk_total_hutang,
			'TRX02'
		]);
	}
	
	$pdo->commit();
	echo json_encode(array('success'=>'Data Approved'));
}

catch(PDOException $e)
{
	$pdo->rollback();
	echo json_encode(array('errorMsg' => "Error: " . $e->getMessage()));
}

$pdo = null;
?>