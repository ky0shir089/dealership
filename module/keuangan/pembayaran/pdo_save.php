<?php
include '../../../pdo.php';
session_start();

$pv_paid_date = htmlspecialchars($_REQUEST['pv_paid_date']);
$pv_desc = htmlspecialchars(strtoupper($_REQUEST['pv_desc']));
$pv_bank_rek = htmlspecialchars($_REQUEST['pv_bank_rek']);
$rekout_segment = htmlspecialchars($_REQUEST['rekout_segment']);
$sumAmount = $_REQUEST['sumAmount'];
$sumScheme = $_REQUEST['sumScheme'];
$outlets = json_decode($_POST['data1']);
$data = json_decode($_POST['data2']);
foreach($outlets as $outlet){
	$spuk_outlet = $outlet->pv_outlet;
}

try
{
	$pdo->beginTransaction();
	
	$query = "select * from seq_pv 
			where 
				outlet = ?
			order by seq_id desc 
			limit 0,1";
	$stmt = $pdo->prepare($query);
	$stmt->execute([
		$_SESSION['outlet']
	]);
	$data7 = $stmt->fetch();
	$tahun = $data7['year'];
	if($tahun < year){
		$seq = '0000001';
	} else {
		$seq = sprintf("%'.07d",$data7['seq']+1);
	}
	$pv_no = $_SESSION['outlet'].date('y').'PVD'.$seq;
	$jv_no = str_replace('PVD','JVD',$pv_no);
	$year = date('y');
	$month = strtoupper(date('M'));
	
	$sql = "insert into seq_pv(
				outlet,
				year,
				seq) 
			values(
				?,
				?,
				?)";
	$stmt2 = $pdo->prepare($sql);
	$stmt2->execute([
		$_SESSION['outlet'],
		$year,
		$seq
	]);
	
	foreach($data as $dt){
		$sql2 = "update fin_trn_payment set
					pv_no = ?,
					pv_desc = ?,
					pv_bank_rek = ?,
					pv_paid_status = ?,
					pv_paid_by = ?,
					pv_paid_date = ?,
					pv_segment1 = ?,
					pv_segment2 = ?
				where pv_proses_id = ?";
		$stmt3 = $pdo->prepare($sql2);
		$stmt3->execute([
			$pv_no,
			$pv_desc,
			$pv_bank_rek,
			'P',
			$_SESSION['uid'],
			$pv_paid_date,
			$_SESSION['outlet'],
			$rekout_segment,
			$dt->pv_proses_id
		]);
		
		$sql3 = "update spuk_hdr set
					spuk_status = ?
				where spuk_id = ?";
		$stmt4 = $pdo->prepare($sql3);
		$stmt4->execute([
			'P',
			$dt->pv_proses_id
		]);
		
		$query2 = "select 
					spuk_dtl_utj 
				from spuk_dtl 
				where 
					spuk_dtl_id = ?";
		$stmt5 = $pdo->prepare($query2);
		$stmt5->execute([
			$dt->pv_proses_id
		]);
		while($data2 = $stmt5->fetch()){
			$spuk_dtl_utj = $data2['spuk_dtl_utj'];
			
			$sql4 = "update unit_titip_jual set
						utj_status = ?,
						utj_update_by = ?,
						utj_updated = current_date()
					where utj_id = ?";
			$stmt6 = $pdo->prepare($sql4);
			$stmt6->execute([
				'P',
				$_SESSION['uid'],
				$spuk_dtl_utj
			]);
		}	
			
		$query3 = "select 
					rrv_no_rv 
				from repayment_rv 
				where 
					rrv_spuk_id = ?";
		$stmt7 = $pdo->prepare($query3);
		$stmt7->execute([
			$dt->pv_proses_id
		]);
		while($data3 = $stmt7->fetch()){
			$no_rv = $data3['rrv_no_rv'];
			
			$sql5 = "update fin_trn_rv set
						rv_status = ?
					where rv_no = ?";
			$stmt8 = $pdo->prepare($sql5);
			$stmt8->execute([
				'C',
				$no_rv
			]);
		}
		
		$query4 = "select 
					rrv_no_rv,
					rrv_rv_amount
				from repayment_rv
				where 
					rrv_spuk_id = ?
				order by rrv_rv_amount asc,rrv_no_rv asc";
		$stmt9 = $pdo->prepare($query4);
		$stmt9->execute([
			$dt->pv_proses_id
		]);
		while($data4 = $stmt9->fetch()){
			$rrv_no_rv = $data4['rrv_no_rv'];
			$rv_amount = $data4['rrv_rv_amount'];
			
			$query5 = "select 
						pv_calculate,
						pv_scheme 
					from fin_trn_payment 
					where 
						pv_proses_id = ?";
			$stmt10 = $pdo->prepare($query5);
			$stmt10->execute([
				$dt->pv_proses_id
			]);
			$data5 = $stmt10->fetch();
			$pv_calculate = $data5['pv_calculate'];
			$selisih = $pv_calculate - $rv_amount;
			$pv_scheme = $data5['pv_scheme'];
			
			if($pv_calculate > $rv_amount){
				$sql6 = "update fin_trn_rv set 
							rv_used = rv_used + ?,
							rv_amount = rv_amount - rv_used 
						where rv_no = ?";
						
				$stmt11 = $pdo->prepare($sql6);
				$stmt11->execute([
					$rv_amount,
					$rrv_no_rv
				]);
				
				$sql7 = "update fin_trn_payment set 
							pv_calculate = ?
						where pv_proses_id = ?";
				$stmt12 = $pdo->prepare($sql7);
				$stmt12->execute([
					$selisih,
					$dt->pv_proses_id
				]);
			}
			
			if($pv_calculate < $rv_amount){
				$sql8 = "update fin_trn_rv set 
							rv_used = rv_used + ?,
							rv_scheme = rv_scheme + ?,
							rv_amount = rv_amount - ? - ?
						where rv_no = ?";
				$stmt13 = $pdo->prepare($sql8);
				$stmt13->execute([
					$pv_calculate,
					$pv_scheme,
					$pv_calculate,
					$pv_scheme,
					$rrv_no_rv
				]);
				
				$sql9 = "update fin_trn_payment set 
							pv_calculate = ?
						where pv_proses_id = ?";
				$stmt14 = $pdo->prepare($sql9);
				$stmt14->execute([
					0,
					$dt->pv_proses_id
				]);
				
				$query6 = "select 
							rv_amount,
							rv_scheme 
						from fin_trn_rv 
						where 
							rv_no = ?";
				$stmt15 = $pdo->prepare($query6);
				$stmt15->execute([
					$rrv_no_rv
				]);
				$data6 = $stmt15->fetch();
				$sisa_rv = $data6['rv_amount'];
				$scheme = $data6['rv_scheme'];
				
				if($sisa_rv > 0){
					$sql10 = "update fin_trn_rv set 
								rv_status = ?,
								rv_classification = ? 
							where rv_no = ?";
					$stmt15 = $pdo->prepare($sql10);
					$stmt15->execute([
						'N',
						NULL,
						$rrv_no_rv
					]);
				}
			}
		}
	}
	
	$sql11 = "insert into gl_journal(
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
				now())";
	$stmt16 = $pdo->prepare($sql11);
	$stmt16->execute([
		$pv_no,
		$month,
		$year,
		'OUT',
		$pv_desc,
		$_SESSION['outlet'],
		'3310201',
		$sumAmount,
		$_SESSION['uid']
	]);
	
	$sql12 = "insert into gl_journal(
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
				now())";
	$stmt17 = $pdo->prepare($sql12);
	$stmt17->execute([
		$pv_no,
		$month,
		$year,
		'OUT',
		$pv_desc,
		$_SESSION['outlet'],
		$rekout_segment,
		$sumAmount,
		$_SESSION['uid']
	]);
	
	$sql13 = "insert into gl_journal(
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
				now())";
	$stmt18 = $pdo->prepare($sql13);
	$stmt18->execute([
		$jv_no,
		$month,
		$year,
		'JV',
		$pv_desc,
		$_SESSION['outlet'],
		'3310201',
		$sumScheme,
		$_SESSION['uid']
	]);
	
	$sql14 = "insert into gl_journal(
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
				now())";
	$stmt19 = $pdo->prepare($sql14);
	$stmt19->execute([
		$jv_no,
		$month,
		$year,
		'JV',
		$pv_desc,
		$_SESSION['outlet'],
		'3310101',
		$sumScheme,
		$_SESSION['uid']
	]);
	
	$pdo->commit();
	echo json_encode(array('success'=>'Data Saved','pv_no'=>$pv_no));
}

catch(PDOException $e)
{
	$pdo->rollback();
	echo json_encode(array('errorMsg' => "Error: " . $e->getMessage()));
}

$pdo = null;
?>