<?php
include '../../../pdo.php';
session_start();

$cancel_id = $_REQUEST['cancel_id'];
$cancel_spuk_id = $_REQUEST['cancel_spuk_id'];
$spuk_outlet = $_REQUEST['spuk_outlet'];
$month_name = strtoupper(date('M'));
$year = date('y');

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
		$cancel_id,
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
	
	$sql = "update wf_history set 
				wf_hist_executor = ?,
				wf_hist_status = ?,
				wf_hist_date_process = now() 
			where 
				no_proses = ? and 
				wf_hist_seq = ?";
	$stmt3 = $pdo->prepare($sql);
	$stmt3->execute([
		$_SESSION['uid'],
		'A',
		$cancel_id,
		$seq
	]);
	
	$sql2 = "update wf_process set 
				jml_approve = jml_approve+1 
			where wf_process_no = ?";
	$stmt4 = $pdo->prepare($sql2);
	$stmt4->execute([
		$cancel_id
	]);
	
	if($seq == $urutan){
		$query3 = "select 
					cancel_utj,
					utj_nopol
				from cancel_unit a 
				join unit_titip_jual b on a.cancel_utj=b.utj_id
				where cancel_id = ?";
		$stmt5 = $pdo->prepare($query3);
		$stmt5->execute([
			$cancel_id
		]);
		while($data3 = $stmt5->fetch())
		{
			$sql3 = "delete from spuk_dtl where spuk_dtl_utj = ?";
			$stmt6 = $pdo->prepare($sql3);
			$stmt6->execute([
				$data3['cancel_utj']
			]);
			
			$query4 = "select 
						cancel_utj_scheme,
						cancel_hbm 
					from cancel_unit 
					where cancel_utj = ?";
			$stmt7 = $pdo->prepare($query4);
			$stmt7->execute([
				$data3['cancel_utj']
			]);
			$data4 = $stmt7->fetch();
			$margin = $data4['cancel_utj_scheme'];
			$refund = $margin + $data4['cancel_hbm'];
			
			$query5 = "select 
						rv_no
					from fin_trn_rv a 
					join repayment_rv b on a.rv_no=b.rrv_no_rv 
					where 
						rrv_spuk_id = ? and 
						rv_scheme > 0";
			$stmt8 = $pdo->prepare($query5);
			$stmt8->execute([
				$cancel_spuk_id
			]);
			$data5 = $stmt8->fetch();
			$rv_no = $data5['rv_no'];
			
			$cancel_desc = "PEMBATALAN SPUK ".$cancel_spuk_id." NOPOL: ".$data3['utj_nopol'];
			
			$sql4 = "update fin_trn_rv set
						rv_amount = ?,
						rv_scheme = rv_scheme - ?,
						rv_used = rv_used - ?,
						rv_status = ?,
						rv_update_by = ?,
						rv_updated = current_date()
					where rv_no = ?";
			$stmt9 = $pdo->prepare($sql4);
			$stmt9->execute([
				$refund,
				$margin,
				$data4['cancel_hbm'],
				'N',
				$_SESSION['uid'],
				$rv_no
			]);
			
			$sql5 = "insert into gl_journal(
						gl_no,
						gl_period_month,
						gl_period_year,
						gl_date,
						gl_type,
						gl_desc,
						gl_segment1,
						gl_segment2,
						gl_dr,
						gl_create_by,
						gl_created) 
					values(
						?,
						?,
						?,
						current_date(),
						?,
						?,
						?,
						?,
						?,
						?,
						current_date())";
			$stmt10 = $pdo->prepare($sql5);
			$stmt10->execute([
				$cancel_id,
				$month_name,
				$year,
				'JV',
				$cancel_desc,
				$spuk_outlet,
				'3310101',
				$margin,
				$_SESSION['uid']
			]);
			
			$sql6 = "insert into gl_journal(
						gl_no,
						gl_period_month,
						gl_period_year,
						gl_date,
						gl_type,
						gl_desc,
						gl_segment1,
						gl_segment2,
						gl_cr,
						gl_create_by,
						gl_created) 
					values(
						?,
						?,
						?,
						current_date(),
						?,
						?,
						?,
						?,
						?,
						?,
						current_date())";
			$stmt11 = $pdo->prepare($sql6);
			$stmt11->execute([
				$cancel_id,
				$month_name,
				$year,
				'JV',
				$cancel_desc,
				$spuk_outlet,
				'3310201',
				$margin,
				$_SESSION['uid']
			]);
			
			$sql8 = "update unit_titip_jual set 
						utj_status = ?,
						utj_update_by = ?,
						utj_updated = current_date()						
					where utj_id = ?";
			$stmt13 = $pdo->prepare($sql8);
			$stmt13->execute([
				'C',
				$_SESSION['uid'],
				$data3['cancel_utj']
			]);
		}
		
		$sql7 = "update cancel_unit set 
					cancel_status = ?,
				where cancel_id = ?";
		$stmt12 = $pdo->prepare($sql7);
		$stmt12->execute([
			'C',
			$cancel_id
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