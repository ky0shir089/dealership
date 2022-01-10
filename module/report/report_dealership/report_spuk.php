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
       ->setCategory("Report SPUK");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'No')
	   ->setCellValue('B1', 'Tgl SPUK')
       ->setCellValue('C1', 'No. SPUK')
	   ->setCellValue('D1', 'Cabang')
	   ->setCellValue('E1', 'Status SPUK');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
$outlet = $_POST['kode_titik'];

$query = "select
			spuk_date,
			spuk_id,
			nama_titik,
			case
				when spuk_status='N' then 'NEW'
				when spuk_status='S' then 'SAVED'
				when spuk_status='R' then 'REQUEST'
				when spuk_status='A' then 'APPROVED'
				when spuk_status='J' then 'REJECT'
				else 'PAID'
			end as spuk_status
		from 
			spuk_hdr a
			left join infinity.titik b on a.spuk_outlet=b.kode_titik
		where 
			(spuk_date >= '$start' and spuk_date <= '$end') and
			spuk_outlet like '%$outlet%'";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $data['spuk_date'])
	 ->setCellValue("C$baris", $data['spuk_id'])
	 ->setCellValue("D$baris", $data['nama_titik'])
	 ->setCellValue("E$baris", $data['spuk_status']);
$baris = $baris + 1;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('REPORT SPUK');
 
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
 