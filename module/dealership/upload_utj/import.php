<?php
include '../../../conn2.php';
include '../../../cek_session.php';
include '../../../plugins/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';
include '../../../plugins/PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php';

error_reporting(0);

$target_dir = "../../../uploads/";

if(!is_dir($target_dir.$_SESSION['outlet'])){
	 mkdir($target_dir.$_SESSION['outlet']); 
}

$target_dir = $target_dir.$_SESSION['outlet'].'/';
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

	for ($row_index = 2; $row_index <= $sheet1->getHighestRow(); $row_index++)
	{
		$row_error = array();
		
		// Cek name
		if (trim($sheet1->getCellByColumnAndRow(1, $row_index)->getValue()) == '')
		{
			array_push($row_error, " nama kosong");
		}
		
		// Cek no contract
		$contract = trim($sheet1->getCellByColumnAndRow(2, $row_index)->getValue());
		if ($contract == '')
		{
			array_push($row_error, " no kontrak kosong");
		}
		else
		{
			$query = "select utj_no_contract from unit_titip_jual where utj_no_contract='$contract'";
			$hasil = mysqli_query($con,$query) or die(mysqli_error($con));
			$data = mysql_num_rows($hasil);
			
			if ($data > 0)
			{
				array_push($row_error, " no kontrak sudah ada");
			}
		}
		
		// Cek bpkb name
		if (trim($sheet1->getCellByColumnAndRow(3, $row_index)->getValue()) == '')
		{
			array_push($row_error, " nama bpkb kosong");
		}
		
		// Cek nopol
		if (trim($sheet1->getCellByColumnAndRow(4, $row_index)->getValue()) == '')
		{
			array_push($row_error, " nopol kosong");
		}
		
		// Cek noka
		if (trim($sheet1->getCellByColumnAndRow(5, $row_index)->getValue()) == '')
		{
			array_push($row_error, " noka kosong");
		}
		
		// Cek nosin
		if (trim($sheet1->getCellByColumnAndRow(6, $row_index)->getValue()) == '')
		{
			array_push($row_error, " nosin kosong");
		}
		
		// Cek type
		$vehicle_name = trim($sheet1->getCellByColumnAndRow(7, $row_index)->getValue());
		if ($vehicle_name == '')
		{
			array_push($row_error, " type kosong");
		}
		
		// Cek stnk
		if (trim($sheet1->getCellByColumnAndRow(8, $row_index)->getValue()) == '')
		{
			array_push($row_error, " stnk kosong");
		}
		else
		{
			$stnk = $sheet1->getCellByColumnAndRow(8, $row_index)->getValue();
			
			if (strtoupper($stnk) != 'Y' && strtoupper($stnk) != 'N' && strtoupper($stnk) != 'Y ' && strtoupper($stnk) != 'N ')
			{
				array_push($row_error, " stnk = (Y/N)");
			}
		}
		
		// Cek grade
		if (trim($sheet1->getCellByColumnAndRow(9, $row_index)->getValue()) == '')
		{
			array_push($row_error, " grade kosong");
		}
		else
		{
			$grade = $sheet1->getCellByColumnAndRow(9, $row_index)->getValue();
			
			if (strtoupper($grade) != 'A' && strtoupper($grade) != 'B' && strtoupper($grade) != 'C' && strtoupper($grade) != 'D')
			{
				array_push($row_error, " grade = (A/B/C/D)");
			}
		}
		
		// Cek tahun
		if (trim($sheet1->getCellByColumnAndRow(10, $row_index)->getValue()) == '')
		{
			array_push($row_error, " tahun kosong");
		}
		
		// Cek hutang konsumen
		if (trim($sheet1->getCellByColumnAndRow(11, $row_index)->getValue()) == '')
		{
			array_push($row_error, " hutang konsumen kosong");
		}
		
		// Cek tgl_ct
		if (trim($sheet1->getCellByColumnAndRow(12, $row_index)->getValue()) == '')
		{
			array_push($row_error, " tanggal cash tempo kosong");
		}
		
		// Cek no_paket
		if (trim($sheet1->getCellByColumnAndRow(13, $row_index)->getValue()) == '')
		{
			array_push($row_error, " no paket kosong");
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

	if ($fail_count == 0)
	{	
		for ($row_index = 2; $row_index <= $sheet1->getHighestRow(); $row_index++)
		{	
			//sequence
			$query2 = "select * from seq_utj where outlet='$_SESSION[outlet]' order by seq_id desc limit 0,1";
			$hasil2 = mysqli_query($con,$query2);
			$data2 = mysqli_fetch_array($hasil2);
			$tahun = $data2['year'];
			if($tahun < year){
				$seq = '00001';
			} else {
				$seq = sprintf("%'.05d",$data2['seq']+1);
			}
			$utj_id = $_SESSION['outlet'].date('y').'UTJ'.$seq;
			$year = date('y');
			$sql2 = "insert into seq_utj(outlet,year,seq) values('$_SESSION[outlet]','$year','$seq')";
			mysqli_query($con,$sql2) or die(mysqli_error($con));
			$id = $sheet1->setCellValueByColumnAndRow(0, $row_index, $utj_id);
			
			// prepare data
			$utj_bpkb_name		= $sheet1->getCellByColumnAndRow(3, $row_index)->getValue();
			$utj_tgl_stnk		= $sheet1->getCellByColumnAndRow(9, $row_index)->getValue();
			$utj_ct_date		= $sheet1->getCellByColumnAndRow(13, $row_index)->getValue();
			
			// formatting data
			if ($utj_ct_date != '')
			{
				$tgl2			= $sheet1->getCellByColumnAndRow(13, $row_index)->getValue();
				$utj_ct_date	= date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($tgl1));
			}

			$id					= $sheet1->getCellByColumnAndRow(0, $row_index)->getValue();
			$utj_name			= trim($sheet1->getCellByColumnAndRow(1, $row_index)->getValue());
			$utj_no_contract	= $sheet1->getCellByColumnAndRow(2, $row_index)->getValue();
			$utj_bpkb_name		= $utj_bpkb_name == '' ? 'NULL' : "'".$utj_bpkb_name."'";	
			$utj_nopol			= $sheet1->getCellByColumnAndRow(4, $row_index)->getValue();
			$utj_noka			= $sheet1->getCellByColumnAndRow(5, $row_index)->getValue();
			$utj_nosin			= $sheet1->getCellByColumnAndRow(6, $row_index)->getValue();
			$utj_type			= $sheet1->getCellByColumnAndRow(7, $row_index)->getValue();
			$utj_stnk			= $sheet1->getCellByColumnAndRow(8, $row_index)->getValue();		
			$utj_grade			= $sheet1->getCellByColumnAndRow(9, $row_index)->getValue();
			$utj_tahun			= $sheet1->getCellByColumnAndRow(10, $row_index)->getValue();
			$utj_hutang_konsumen= $sheet1->getCellByColumnAndRow(11, $row_index)->getValue();
			$utj_ct_date		= $utj_ct_date == '' ? 'NULL' : "'".$utj_ct_date."'";	
			$utj_no_paket		= $sheet1->getCellByColumnAndRow(13, $row_index)->getValue();
			
			$sql = "insert into unit_titip_jual(
					utj_id,
					utj_outlet,
					utj_name,
					utj_no_contract,
					utj_bpkb_name,
					utj_nopol,
					utj_noka,
					utj_nosin,
					utj_type,
					utj_stnk,
					utj_grade,
					utj_tahun,
					utj_hutang_konsumen,
					utj_ct_date,
					utj_no_paket,
					utj_create_by,
					utj_created) 
				values(
					'$id',
					'$_SESSION[outlet]',
					'$utj_name',
					'$utj_no_contract',
					$utj_bpkb_name,
					'$utj_nopol',
					'$utj_noka',
					'$utj_nosin',
					'$utj_type',
					'$utj_stnk',
					'$utj_grade',
					'$utj_tahun',
					'$utj_hutang_konsumen',
					$utj_ct_date,
					'$utj_no_paket',
					'$_SESSION[uid]',
					now());";
			mysqli_query($con,$sql) or die(mysqli_error($con));
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
	if($fail_count > 0)
	{
		echo "<p>Apabila terdapat salah satu baris saja yg error maka import tidak dimasukkan ke database</p>";
	}
	else
	{
		echo "<p>Berhasil import ke database</p>";
	}
}
?>
<a href="index.php">Kembali ke halaman upload</a>