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
       ->setCategory("Report Dealership");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'No')
	   ->setCellValue('B1', 'No Jurnal')
       ->setCellValue('C1', 'Tanggal')
	   ->setCellValue('D1', 'Code Trx')
	   ->setCellValue('E1', 'Akun')
	   ->setCellValue('F1', 'Keterangan')
	   ->setCellValue('G1', 'Debet')
	   ->setCellValue('H1', 'Credit');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
//$outlet = $_POST['kode_titik'];

$query = "select *
		from gl_journal a
		join gl_coa b on a.gl_segment2=b.coa_code
		where gl_date between '$start' and '$end'
		order by gl_id asc";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $data['gl_no'])
	 ->setCellValue("C$baris", $data['gl_date'])
	 ->setCellValue("D$baris", $data['gl_segment2'])
	 ->setCellValue("E$baris", $data['coa_description'])
	 ->setCellValue("F$baris", $data['gl_desc'])
	 ->setCellValue("G$baris", $data['gl_dr'])
	 ->setCellValue("H$baris", $data['gl_cr']);
$baris = $baris + 1;
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
 