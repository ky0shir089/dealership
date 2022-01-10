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
       ->setCategory("Upload");

$baris = 2;
$no = 0;

$period_month = $_POST['period_month'];
$period_year = $_POST['period_year'];
$outlet = $_POST['kode_titik'];

$query = "select
			concat(gl_period_month,'-',gl_period_year) as period_name,
			concat(gl_segment1,'/',gl_date) as batch_name,
			gl_no,
			date_format(gl_date,'%d%m%Y') as trx_date,
			gl_segment1,
			gl_segment2,
			gl_dr,
			gl_cr,
			gl_desc
		from gl_journal
		where gl_date between '$start' and '$end'
		and gl_segment1='$outlet'
		order by gl_id asc";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $data['period_name'])
     ->setCellValue("B$baris", $data['batch_name'])
	 ->setCellValue("C$baris", $data['gl_no'])
	 ->setCellValue("D$baris", 'IDR')
	 ->setCellValue("E$baris", $data['trx_date'])
	 ->setCellValue("F$baris", $data['trx_date'])
	 ->setCellValue("G$baris", $data['gl_segment1'])
	 ->setCellValue("H$baris", $data['gl_segment2'])
	 ->setCellValue("I$baris", "'".'00')
	 ->setCellValue("J$baris", "'".'00000')
	 ->setCellValue("S$baris", $data['gl_dr'])
	 ->setCellValue("T$baris", $data['gl_cr'])
	 ->setCellValue("U$baris", $data['gl_dr'])
	 ->setCellValue("V$baris", $data['gl_cr'])
	 ->setCellValue("W$baris", 'OPENBAL')
	 ->setCellValue("X$baris", 'MANUAL')
	 ->setCellValue("AA$baris", $data['gl_desc'])
	 ->setCellValue("AB$baris", $data['gl_segment1'])
	 ->setCellValue("AF$baris", 'MIG')
	 ->setCellValue("AG$baris", $data['gl_segment2'])
	 ->setCellValue("AJ$baris", '1');
$baris = $baris + 1;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Upload Bmrsys');
 
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
 