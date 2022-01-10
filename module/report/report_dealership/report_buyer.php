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
       ->setCellValue('A1', 'NO')
	   ->setCellValue('B1', 'ID')
       ->setCellValue('C1', 'NAME')
	   ->setCellValue('D1', 'TYPE')
	   ->setCellValue('E1', 'KTP')
	   ->setCellValue('F1', 'OWNER')
	   ->setCellValue('G1', 'ALAMAT')
	   ->setCellValue('H1', 'PROVINSI')
	   ->setCellValue('I1', 'KOTA/KABUPATEN')
	   ->setCellValue('J1', 'HP1')
	   ->setCellValue('K1', 'HP2')
	   ->setCellValue('L1', 'NAMA TITIK');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
$outlet = $_POST['kode_titik'];

$query = "select
			supl_id,
			supl_name,
			(case
				when supl_type='I' then 'INDIVIDU'
				when supl_type='C' then 'COMPANY'
				else 'PEDAGANG'
			end) as supl_type,
			cust_ktp,
			cust_owner,
			cust_address,
			province_name,
			regency_name,
			cust_hp,
			cust_hp2,
			(select 
				nama_titik 
			from spuk_hdr a
			join infinity.titik b on a.spuk_outlet=b.kode_titik
			where spuk_cust=supl_id
			group by supl_id) as nama_titik
		from mst_suppliers a
		join mst_customers b on a.supl_id=b.cust_id
		join mst_regencies c on b.cust_regency=c.regency_id
		join mst_provinces d on c.regency_province=d.province_id
		order by supl_id desc";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A$baris", $no)
     ->setCellValue("B$baris", $data['supl_id'])
	 ->setCellValue("C$baris", $data['supl_name'])
	 ->setCellValue("D$baris", $data['supl_type'])
	 ->setCellValueExplicit("E$baris", $data['cust_ktp'])
	 ->setCellValue("F$baris", $data['cust_owner'])
	 ->setCellValueExplicit("G$baris", $data['cust_address'])
	 ->setCellValue("H$baris", $data['province_name'])
	 ->setCellValue("I$baris", $data['regency_name'])
	 ->setCellValueExplicit("J$baris", $data['cust_hp'])
	 ->setCellValueExplicit("K$baris", $data['cust_hp2'])
	 ->setCellValue("L$baris", $data['nama_titik']);
$baris = $baris + 1;
}
 
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('REPORT Buyer');
 
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
 