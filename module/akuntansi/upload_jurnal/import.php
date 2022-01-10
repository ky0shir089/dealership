<?php
header("Access-Control-Allow-Origin: *");
include '../../../pdo.php';
include '../../../session.php';
include '../../../assets/PHPExcel/Classes/PHPExcel.php';
include '../../../assets/PHPExcel/Classes/PHPExcel/IOFactory.php';

ini_set('max_execution_time', 300);
set_time_limit(300);

$target_dir = "uploads/";

$file = $_FILES['file']['name'];
$fname = pathinfo($file,PATHINFO_FILENAME);
$fext = pathinfo($file,PATHINFO_EXTENSION);
$fupload = $target_dir.'['.date('ymd').'] '.$file;

// Allow certain file formats
if($fext != "xls" && $fext != "xlsx" && $fext != "csv") 
{
	echo "Sorry, only XLS, XLSX,& CSV files are allowed. <br />\n";
}

else
{
	move_uploaded_file($_FILES["file"]["tmp_name"], $fupload);
	
	$phpexcel = PHPExcel_IOFactory::load($fupload);

	$sheet1 = $phpexcel->getSheet(0);

	$result_set = array();
	$success_count = 0;
	$fail_count = 0;
	$not_balance = array();
	$balance = 0;
	$debit = 0;
	$credit = 0;

	for ($row_index = 2; $row_index <= $sheet1->getHighestRow(); $row_index++)
	{
		$row_error = array();
		
		// Cek no
		if (trim($sheet1->getCellByColumnAndRow(1, $row_index)->getValue()) == '')
		{
			array_push($row_error, " JC NO kosong");
		}
		
		// Cek bulan
		if (trim($sheet1->getCellByColumnAndRow(2, $row_index)->getValue()) == '')
		{
			array_push($row_error, " bulan kosong");
		}
		else
		{
			$bulan = $sheet1->getCellByColumnAndRow(2, $row_index)->getValue();
			
			if (strtoupper($bulan) != 'JAN' && strtoupper($bulan) != 'FEB' && strtoupper($bulan) != 'MAR' && strtoupper($bulan) != 'APR' && strtoupper($bulan) != 'MEI' && strtoupper($bulan) != 'JUN' && strtoupper($bulan) != 'JUL' && strtoupper($bulan) != 'AUG' && strtoupper($bulan) != 'SEP' && strtoupper($bulan) != 'OCT' && strtoupper($bulan) != 'NOV' && strtoupper($bulan) != 'DEC')
			{
				array_push($row_error, " bulan (JAN/FEB/MAR/APR/MEI/JUN/JUL/AUG/SEP/OCT/NOV/DEC)");
			}
		}
		
		// Cek tahun
		if (trim($sheet1->getCellByColumnAndRow(3, $row_index)->getValue()) == '')
		{
			array_push($row_error, " tahun kosong");
		}
		
		// Cek tanggal
		if (trim($sheet1->getCellByColumnAndRow(4, $row_index)->getValue()) == '')
		{
			array_push($row_error, " tanggal kosong");
		}
		
		// Cek tipe
		if (trim($sheet1->getCellByColumnAndRow(5, $row_index)->getValue()) == '')
		{
			array_push($row_error, " tipe kosong");
		}
		else
		{
			$tipe = $sheet1->getCellByColumnAndRow(5, $row_index)->getValue();
			
			if (strtoupper($tipe) != 'JC')
			{
				array_push($row_error, " khusus upload jurnal tipe JC");
		
			}
		}
		
		// Cek keterangan
		if (trim($sheet1->getCellByColumnAndRow(6, $row_index)->getValue()) == '')
		{
			array_push($row_error, " keterangan kosong");
		}
		
		// Cek segment1
		if (trim($sheet1->getCellByColumnAndRow(7, $row_index)->getValue()) == '')
		{
			array_push($row_error, " segment1 kosong");
		}
		
		// Cek segment2
		if (trim($sheet1->getCellByColumnAndRow(8, $row_index)->getValue()) == '')
		{
			array_push($row_error, " segment2 kosong");
		}
		
		// Cek dr
		$dr = trim($sheet1->getCellByColumnAndRow(9, $row_index)->getValue());
		if ($dr == '')
		{
			array_push($row_error, " debit kosong");
		}
		else
		{
			$debit += $dr;
		}
		
		// Cek cr
		$cr = trim($sheet1->getCellByColumnAndRow(10, $row_index)->getValue());
		if ($cr == '')
		{
			array_push($row_error, " credit kosong");
		}
		else
		{
			$credit += $cr;
		}
		
		// Proses counting error + success
		if (count($row_error) > 0)
		{
			$result = join(",", $row_error);
			$fail_count++;
		}
		else
		{
			$result = "";
			$success_count++;
		}
		
		array_push($result_set, array("Baris " . $row_index . " : ", $result));
	}
	
	if(round($debit) != round($credit)){
		$balance++;
		array_push($not_balance, " NOT BALANCE");
	}

	if ($fail_count == 0 and $balance == 0)
	{	
		for ($row_index = 2; $row_index <= $sheet1->getHighestRow(); $row_index++)
		{	
			try
			{			
				$pdo->beginTransaction();
				
				/* //sequence
				$query = "select * from seq_jc where outlet = ? order by seq_id desc limit 0,1";
				$stmt2 = $pdo->prepare($query);
				$stmt2->execute([
					$_SESSION['outlet']
				]);
				$data2 = $stmt2->fetch();
				$tahun = $data2['year'];
				if($tahun < year){
					$seq = '0000001';
				} else {
					$seq = sprintf("%'.07d",$data2['seq']+1);
				}
				$jc_no = $_SESSION['outlet'].date('y').'JCD'.$seq;
				
				$sql2 = "insert into seq_jc(outlet,year,seq) values(?,?,?)";
				$stmt3 = $pdo->prepare($sql2);
				$stmt3->execute([
					$_SESSION['outlet'],
					year,
					$seq
				]); */
				
				// prepare data
				$gl_no				= $sheet1->getCellByColumnAndRow(1, $row_index)->getValue();
				$gl_period_month	= $sheet1->getCellByColumnAndRow(2, $row_index)->getValue();
				$gl_period_year		= $sheet1->getCellByColumnAndRow(3, $row_index)->getValue();
				$date				= $sheet1->getCellByColumnAndRow(4, $row_index)->getValue();
				$gl_date			= date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($date));
				$gl_type			= $sheet1->getCellByColumnAndRow(5, $row_index)->getValue();
				$gl_desc			= $sheet1->getCellByColumnAndRow(6, $row_index)->getValue();
				$gl_segment1		= $sheet1->getCellByColumnAndRow(7, $row_index)->getValue();
				$gl_segment2		= $sheet1->getCellByColumnAndRow(8, $row_index)->getValue();
				$gl_dr				= $sheet1->getCellByColumnAndRow(9, $row_index)->getValue();
				$gl_cr				= $sheet1->getCellByColumnAndRow(10, $row_index)->getValue();
			
			
				
				$sql = "insert into gl_journal(
							gl_no,
							gl_period_month,
							gl_period_year,
							gl_date,
							gl_type,
							gl_desc,
							gl_segment1,
							gl_segment2,
							gl_dr,
							gl_cr,
							gl_create_by,
							gl_created) 
						values(
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							?,
							current_date())";
				$stmt4 = $pdo->prepare($sql);
				$stmt4->execute([
					trim($gl_no),
					strtoupper(trim($gl_period_month)),
					trim($gl_period_year),
					trim($gl_date),
					strtoupper(trim($gl_type)),
					trim($gl_desc),
					trim($gl_segment1),
					trim($gl_segment2),
					trim($gl_dr),
					trim($gl_cr),
					$_SESSION['uid']
				]);
				
				$pdo->commit();
			}
			catch(PDOException $e)
			{
				$pdo->rollback();
				echo json_encode(array('errorMsg' => "Error: " . $e->getMessage()));
			}
		}
		
		try
		{			
			$pdo->beginTransaction();
			
			$query = "select gl_no from gl_journal where gl_type = ? order by gl_no desc limit 0,1";
			$stmt2 = $pdo->prepare($query);
			$stmt2->execute([
				'JC'
			]);
			$data2 = $stmt2->fetch();
			$seq = substr($data2['gl_no'],-7);
			
			$sql2 = "insert into seq_jc(outlet,year,seq) values(?,?,?)";
			$stmt3 = $pdo->prepare($sql2);
			$stmt3->execute([
				$_SESSION['outlet'],
				year,
				$seq
			]);
			
			$pdo->commit();
		}
		catch(PDOException $e)
		{
			$pdo->rollback();
			echo json_encode(array('errorMsg' => "Error: " . $e->getMessage()));
		}
	}

	echo "<h1 style='margin: 10px 0'>Hasil upload : ".$fupload."</h1>";
	echo "<b>Summary:</b><br />";
	echo "Baris Terproses : ".$success_count."<br />\n";
	echo "Jumlah Error : ".$fail_count."<br />\n";
	echo "<hr>";
	foreach($result_set as $result)
	{
		if($result[1] != '')
		{
			echo "<img src='../../../images/icons/cancel.png'></img><b>".$result[0]."</b>".$result[1]."<br />\n";
		}
		else
		{
			echo "<img src='../../../images/icons/tick.png'></img><b>".$result[0]."</b> OK <br />\n";
		}
	}
	if($fail_count > 0 and $balance > 0)
	{
		echo "<p>Apabila terdapat salah satu baris saja yg error dan tidak balance maka import tidak dimasukkan ke database</p>";
	}
	else
	{
		if($balance > 0){
			echo "<h2>NOT BALANCE:</h2>";
			echo "DEBIT: ".round($debit)."<br>";
			echo "CREDIT: ".round($credit)."<br><br>";
		} else {
			echo "<p>Berhasil import ke database</p>";
		}
	}
}
?>
<a href="index.php">Kembali ke halaman upload</a>