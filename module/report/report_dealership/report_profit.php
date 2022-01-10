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
       ->setCategory("Report Profit");
 
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
       ->setCellValue('A1', 'No')
	   ->setCellValue('B1', 'Tanggal')
       ->setCellValue('C1', 'Nama Cabang Parenting/FIF')
	   ->setCellValue('D1', 'Total Unit yg sudah di lunasi')
	   ->setCellValue('E1', 'Profit 225')
	   ->setCellValue('F1', 'Profit <225')
	   ->setCellValue('G1', 'Profit >225');
	   
$baris = 2;
$no = 0;

$outlet = $_POST['kode_titik'];
$month = substr($_POST['start'],5,2);

$query = "select
			substr(utj_updated,1,7) as periode,
			kode_titik,
			nama_titik,
			(select count(utj_id) from unit_titip_jual where utj_status='P' and month(utj_updated)='$month' and utj_outlet=kode_titik) as total_unit,
			(select count(spuk_dtl_utj) from spuk_dtl a 
				left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id 
			where 
				spuk_dtl_scheme=225000 and 
				utj_status='P' and
				month(utj_updated)='$month' and
				utj_outlet=kode_titik) as profit1,
			(select count(spuk_dtl_utj) from spuk_dtl a 
				left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id 
			where 
				spuk_dtl_scheme<225000 and 
				utj_status='P' and
				month(utj_updated)='$month' and
				utj_outlet=kode_titik) as profit2,
			(select count(spuk_dtl_utj) from spuk_dtl a 
				left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id 
			where 
				spuk_dtl_scheme>225000 and 
				utj_status='P' and
				month(utj_updated)='$month' and
				utj_outlet=kode_titik) as profit3
		from 
			unit_titip_jual a
			left join infinity.titik b on a.utj_outlet=b.kode_titik
		where 
			month(utj_updated)='$month' and
			utj_outlet like '%$outlet%'
		GROUP by periode,utj_outlet";
		//die($query);
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $data['periode'])
	 ->setCellValue("C$baris", $data['nama_titik'])
	 ->setCellValue("D$baris", $data['total_unit'])
	 ->setCellValue("E$baris", $data['profit1'])
	 ->setCellValue("F$baris", $data['profit2'])
	 ->setCellValue("G$baris", $data['profit3']);
$baris = $baris + 1;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('REPORT PROFIT');
 
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
 