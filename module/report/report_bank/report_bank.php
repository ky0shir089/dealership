<?php

error_reporting(E_ALL);

require_once '../../../plugins/excel/PHPExcel.php';
require_once '../../../conn.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
 
// Set properties
$objPHPExcel->getProperties()->setCreator("Prima")
      ->setLastModifiedBy("Prima")
      ->setTitle("Office 2013 XLSX Test Document")
      ->setSubject("Office 2013 XLSX Test Document")
       ->setDescription("Laporan Database.")
       ->setKeywords("office 2013 openxml php")
       ->setCategory("Report Bank");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'No')
	   ->setCellValue('B1', 'Tanggal')
       ->setCellValue('C1', 'No RV/PV')
	   ->setCellValue('D1', 'Keterangan')
	   ->setCellValue('E1', 'Saldo Awal')
	   ->setCellValue('F1', 'Debet')
	   ->setCellValue('G1', 'Credit')
	   ->setCellValue('H1', 'Saldo Akhir');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
$bank_acctno = $_POST['bank_acctno'];
$rekout_segment = $_POST['rekout_segment'];

$query = "select * from fin_trnbank_balance
		  where 
			bankbal_acctno='$bank_acctno' and
			(bankbal_date between '$start' and '$end')
		  order by bankbal_date asc";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
	$no = $no +1;
	$objPHPExcel->setActiveSheetIndex(0)
		 ->setCellValue("A$baris", $no)
		 ->setCellValue("B$baris", $data['bankbal_date'])
		 ->setCellValue("E$baris", $data['bankbal_saldo_awal']);
	$baris = $baris + 1;

	$query2 = "select * from gl_journal
			   where 
				 gl_segment2='$rekout_segment' and
				 gl_date='$data[bankbal_date]' and
				 gl_dr!='$data[bankbal_saldo_awal]'";
	$result2 = mysql_query($query2) or die(mysql_error());			
	while($data2 = mysql_fetch_array($result2)){
		$objPHPExcel->setActiveSheetIndex(0)
			 ->setCellValue("B$baris", $data['bankbal_date'])
			 ->setCellValue("C$baris", $data2['gl_no'])
			 ->setCellValue("D$baris", $data2['gl_desc'])
			 ->setCellValue("F$baris", $data2['gl_dr'])
			 ->setCellValue("G$baris", $data2['gl_cr']);
		$baris = $baris + 1;
	}
	
	$objPHPExcel->setActiveSheetIndex(0)
		 ->setCellValue("B$baris", $data['bankbal_date'])
		 ->setCellValue("H$baris", $data['bankbal_saldo_akhir']);
	$baris = $baris + 1;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('REPORT Bank');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report.xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 