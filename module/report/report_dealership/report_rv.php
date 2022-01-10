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
	   ->setCellValue('F1', 'Keterangan')
	   ->setCellValue('G1', 'RV Amount');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
$outlet = $_POST['kode_titik'];

$query = "select
			spuk_date,
			a.rrv_spuk_id,
			nama_titik,
			(select GROUP_CONCAT(rrv_no_rv) from repayment_rv where rrv_spuk_id=a.rrv_spuk_id) as no_rv,
			(select GROUP_CONCAT(rv_received_from) from repayment_rv b join fin_trn_rv c on b.rrv_no_rv = c.rv_no where rrv_spuk_id=a.rrv_spuk_id) as keterangan,
			(select GROUP_CONCAT(rrv_rv_amount) from repayment_rv where rrv_spuk_id=a.rrv_spuk_id) as rv_amount
		from repayment_rv a
		join spuk_hdr b on a.rrv_spuk_id=b.spuk_id
		join infinity.titik c on b.spuk_outlet=c.kode_titik
		where 
			(spuk_date >= '$start' and spuk_date <= '$end') and
			spuk_outlet like '%$outlet%'
		group by rrv_spuk_id
		order by spuk_date,rrv_spuk_id asc";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $data['spuk_date'])
	 ->setCellValue("C$baris", $data['rrv_spuk_id'])
	 ->setCellValue("D$baris", $data['nama_titik'])
	 ->setCellValue("E$baris", $data['no_rv'])
	 ->setCellValue("F$baris", $data['keterangan'])
	 ->setCellValue("G$baris", $data['rv_amount']);
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
 