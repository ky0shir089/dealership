<?php

error_reporting(E_ALL);

require_once '../../../plugins/excel/PHPExcel.php';
require_once '../../../conn.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objWorksheet = $objPHPExcel->getActiveSheet();
 
// Set properties
$objPHPExcel->getProperties()->setCreator("Prima")
      ->setLastModifiedBy("Prima")
      ->setTitle("Office 2013 XLSX Test Document")
      ->setSubject("Office 2013 XLSX Test Document")
       ->setDescription("Laporan Database.")
       ->setKeywords("office 2013 openxml php")
       ->setCategory("Report Dealership");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'Keterangan')
	   ->setCellValue('B1', 'Code Trx')
       ->setCellValue('C1', 'No Jurnal')
	   ->setCellValue('D1', 'Tanggal')
	   ->setCellValue('E1', 'Debet')
	   ->setCellValue('F1', 'Credit')
	   ->setCellValue('G1', 'Balance');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
//$before = date('Y-m-01', strtotime('-1 month', strtotime($start)));
//$outlet = $_POST['kode_titik'];

$query = "select gl_segment2,coa_parent,coa_description
			from gl_journal a
			join gl_coa b on a.gl_segment2=b.coa_code
			group by gl_segment2
			order by coa_code asc";
$result = mysql_query($query) or die(mysql_error());
while($data = mysql_fetch_array($result)){
	$query2 = "select sum(gl_dr-gl_cr) as balance from gl_journal where gl_segment2='$data[gl_segment2]' and gl_date < '$start'";
	$result2 = mysql_query($query2) or die(mysql_error());			
	$data2 = mysql_fetch_array($result2);
	
	$query4 = "select sum(gl_dr) as debit, sum(gl_cr) as credit from gl_journal where gl_segment2='$data[gl_segment2]' and gl_date between '$start' and '$end'";
	$result4 = mysql_query($query4) or die(mysql_error());			
	$data4 = mysql_fetch_array($result4);
	$balance2 = $data2['balance']+$data4['debit']-$data4['credit'];
	
	$objPHPExcel->setActiveSheetIndex(0)
		 ->setCellValue("A$baris", $data['coa_parent'].'.'.$data['coa_description'])
		 ->setCellValue("G$baris", $data2['balance']);
	$baris = $baris + 1;
	
	$query3 = "select *
			from gl_journal a
			join gl_coa b on a.gl_segment2=b.coa_code
			where
				gl_segment2='$data[gl_segment2]' and
				gl_date between '$start' and '$end'
			order by gl_date asc";
	$result3 = mysql_query($query3) or die(mysql_error());			
	while($data3 = mysql_fetch_array($result3)){
		$objPHPExcel->setActiveSheetIndex(0)
			 ->setCellValue("A$baris", $data3['gl_desc'])
			 ->setCellValue("B$baris", $data3['gl_segment2'])
			 ->setCellValue("C$baris", $data3['gl_no'])
			 ->setCellValue("D$baris", $data3['gl_date'])
			 ->setCellValue("E$baris", $data3['gl_dr'])
			 ->setCellValue("F$baris", $data3['gl_cr']);
		$baris = $baris + 1;
	}
	$objPHPExcel->setActiveSheetIndex(0)
		 ->setCellValue("A$baris", "TOTAL ".$data['coa_parent'].'.'.$data['coa_description'])
		 ->setCellValue("E$baris", $data4['debit'])
		 ->setCellValue("F$baris", $data4['credit'])
		 ->setCellValue("G$baris", $balance2);
	$baris = $baris + 2;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('REPORT GL');
 
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
 