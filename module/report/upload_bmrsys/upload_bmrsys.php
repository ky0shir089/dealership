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

$period_month = $_POST['period_month'];
$period_year = $_POST['period_year'];
$period_end_date = $_POST['period_end_date'];
$date = date_create($period_end_date);
$acc_date = date_format($date,'dmY');
$outlet = $_POST['kode_titik'];
	   
$query2 = "select count(gl_no) as jumlah 
		from gl_journal 
		where gl_period_month='$period_month' and 
		gl_period_year='$period_year' and 
		gl_segment1 like '%$outlet%'";
$result2 = mysql_query($query2) or die(mysql_error());			
$data2 = mysql_fetch_array($result2);
	   
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue("A1", '10000')
	 ->setCellValue("B1", $acc_date)
	 ->setCellValue("C1", $acc_date)
	 ->setCellValue("D1", $data2['jumlah']);

$baris = 2;
$no = 0;

$query = "select
			concat(gl_period_month,'-',gl_period_year) as period_name,
			concat('10000','/',date_format(gl_date,'%d-%m-%Y')) as batch_name,
			gl_no,
			date_format(gl_date,'%d%m%Y') as trx_date,
			gl_segment1,
			gl_segment2,
			gl_dr,
			gl_cr,
			gl_desc,
			case
				when gl_type='IN' then 'CASH-IN'
				when gl_type='OUT' then 'CASH-OUT'
				when gl_type='JV' then 'JVI AUTO'
				else 'PENYESUAIAN'
			end as gl_type
		from 
			gl_journal
		where 
			gl_period_month='$period_month' and
			gl_period_year='$period_year' and
			gl_segment1 like '%$outlet%'
		order by gl_id asc";
$result = mysql_query($query) or die(mysql_error());			
while($data = mysql_fetch_array($result)){
$no = $no +1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValueExplicit("A$baris", strtoupper($data['period_name']), PHPExcel_Cell_DataType::TYPE_STRING)
     ->setCellValue("B$baris", $data['batch_name'])
	 ->setCellValue("C$baris", $data['gl_no'])
	 ->setCellValue("D$baris", 'IDR')
	 ->setCellValueExplicit("E$baris", $data['trx_date'], PHPExcel_Cell_DataType::TYPE_STRING)
	 ->setCellValueExplicit("F$baris", $data['trx_date'], PHPExcel_Cell_DataType::TYPE_STRING)
	 ->setCellValue("G$baris", $data['gl_segment1'])
	 ->setCellValue("H$baris", $data['gl_segment2'])
	 ->setCellValueExplicit("I$baris", '00', PHPExcel_Cell_DataType::TYPE_STRING)
	 ->setCellValueExplicit("J$baris", '00000', PHPExcel_Cell_DataType::TYPE_STRING)
	 ->setCellValue("S$baris", $data['gl_dr'])
	 ->setCellValue("T$baris", $data['gl_cr'])
	 ->setCellValue("U$baris", $data['gl_dr'])
	 ->setCellValue("V$baris", $data['gl_cr'])
	 ->setCellValue("W$baris", $data['gl_type'])
	 ->setCellValue("X$baris", 'MANUAL')
	 ->setCellValue("AA$baris", $data['gl_desc'])
	 ->setCellValue("AB$baris", '10000')
	 ->setCellValue("AF$baris", 'MIG')
	 ->setCellValueExplicit("AG$baris", $data['trx_date'], PHPExcel_Cell_DataType::TYPE_STRING)
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
 