<?php

error_reporting(E_ALL);

require_once '../../../plugins/excel/PHPExcel.php';
require_once '../../../conn2.php';

set_time_limit(600);

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
	   ->setCellValue('B1', 'Tanggal')
       ->setCellValue('C1', 'No. RV')
	   ->setCellValue('D1', 'Keterangan')
	   ->setCellValue('E1', 'Proses ID')
	   ->setCellValue('F1', 'Nominal')
	   ->setCellValue('G1', 'No. PV')
	   ->setCellValue('H1', 'Pemakaian')
	   ->setCellValue('I1', 'Sisa Saldo');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];

$query = "select
			rv_received_date,
			rv_no,
			rv_received_from,
			GROUP_CONCAT(pv_proses_id) as pv_proses_id,
			rv_start,
			GROUP_CONCAT(pv_no) as pv_no,
			rv_used
		from fin_trn_rv a
        left join repayment_rv b on a.rv_no=b.rrv_no_rv
        left join fin_trn_inv_hdr c on a.rv_no=c.invhdr_reff_no
        join fin_trn_payment d on b.rrv_spuk_id=d.pv_proses_id OR c.invhdr_no=d.pv_proses_id  
		where (rv_received_date between '$start' and '$end') and rv_segment2 = '3310201'
		GROUP BY rv_no
		order by rv_received_date asc,rv_no asc";
$result = mysqli_query($con,$query) or die(mysqli_error($con));			
while($data = mysqli_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $data['rv_received_date'])
	 ->setCellValue("C$baris", $data['rv_no'])
	 ->setCellValue("D$baris", $data['rv_received_from'])
	 ->setCellValue("E$baris", $data['pv_proses_id'])
	 ->setCellValue("F$baris", $data['rv_start'])
	 ->setCellValue("G$baris", $data['pv_no'])
	 ->setCellValue("H$baris", $data['rv_used'])
	 ->setCellValue("I$baris", $data['rv_start']-$data['rv_used']);
$baris = $baris + 1;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('REPORT RV');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
$objWriter->save('php://output');
exit;
?>
 