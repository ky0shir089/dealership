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
	   ->setCellValue('B1', 'Tgl Pengajuan')
       ->setCellValue('C1', 'Nama Cabang Parenting/FIF')
	   ->setCellValue('D1', 'No. Paket Distribusi')
	   ->setCellValue('E1', 'No. Kontrak')
	   ->setCellValue('F1', 'Type Motor')
	   ->setCellValue('G1', 'Tahun')
	   ->setCellValue('H1', 'HJR')
	   ->setCellValue('I1', 'HBM')
	   ->setCellValue('J1', 'Profit');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
$outlet = $_POST['kode_titik'];

$query = "select
			utj_created,
			nama_titik,
			utj_no_paket,
			utj_no_contract,
			utj_type,
			utj_tahun,
			spuk_dtl_total,
			utj_hutang_konsumen,
			spuk_dtl_scheme
		from 
			unit_titip_jual a
			left join infinity.titik b on a.utj_outlet=b.kode_titik
			left join spuk_dtl c on a.utj_id=c.spuk_dtl_utj
		where 
			(utj_created >= '$start' and utj_created <= '$end') and
			utj_outlet like '%$outlet%'";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $data['utj_created'])
	 ->setCellValue("C$baris", $data['nama_titik'])
	 ->setCellValue("D$baris", $data['utj_no_paket'])
	 ->setCellValue("E$baris", "'".$data['utj_no_contract'])
	 ->setCellValue("F$baris", $data['utj_type'])
	 ->setCellValue("G$baris", $data['utj_tahun'])
	 ->setCellValue("H$baris", $data['spuk_dtl_total'])
	 ->setCellValue("I$baris", $data['utj_hutang_konsumen'])
	 ->setCellValue("J$baris", $data['spuk_dtl_scheme']);
$baris = $baris + 1;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('REPORT Dealership');
 
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
 