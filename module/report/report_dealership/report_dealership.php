<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

include '../../../conn2.php';
include '../../../plugins/excel/PHPExcel.php';

set_time_limit(600);
ini_set('memory_limit', '256M');

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
	   ->setCellValue('B1', 'TGL SPUK')
       ->setCellValue('C1', 'NO INVENTORY')
	   ->setCellValue('D1', 'KODE CABANG')
	   ->setCellValue('E1', 'NAMA CABANG')
	   ->setCellValue('F1', 'NO PAKET DISTRIBUSI')
	   ->setCellValue('G1', 'NO KONTRAK')
	   ->setCellValue('H1', 'NO SPUK')
	   ->setCellValue('I1', 'GRADE')
	   ->setCellValue('J1', 'ID BUYER')
	   ->setCellValue('K1', 'NAMA BUYER')
	   ->setCellValue('L1', 'NO POL')
	   ->setCellValue('M1', 'NO RANGKA')
	   ->setCellValue('N1', 'NO MESIN')
	   ->setCellValue('O1', 'TYPE')
	   ->setCellValue('P1', 'TAHUN')
	   ->setCellValue('Q1', 'OTR')
	   ->setCellValue('R1', 'HBM')
	   ->setCellValue('S1', 'TGL SETOR')
	   ->setCellValue('T1', 'TGL TRANSFER')
	   ->setCellValue('U1', 'STATUS')
	   ->setCellValue('V1', 'PROFIT')
	   ->setCellValue('W1', 'BANK IN')
	   ->setCellValue('X1', 'BANK OUT')
	   ->setCellValue('Y1', 'NO PV')
	   ->setCellValue('Z1', 'RV FIF');
	   
$baris = 2;
$no = 0;

$start = $_POST['start'];
$end = $_POST['end'];
$outlet = $_POST['kode_titik'];

$query = "select
			spuk_date,
			spuk_dtl_utj,
			spuk_outlet,
			nama_titik,
			utj_no_paket,
			utj_no_contract,
			spuk_id,
			utj_grade,
			spuk_cust,
			supl_name,
			utj_nopol,
			utj_noka,
			utj_nosin,
			utj_type,
			utj_tahun,
			spuk_dtl_total,
			utj_hutang_konsumen,
			(select group_concat(distinct rv_received_date) 
				from repayment_rv a 
				left join fin_trn_rv b on a.rrv_no_rv=b.rv_no
				where rrv_spuk_id=spuk_id) as rv_received_date,
			pv_paid_date,
			(case
				when utj_status='N' then 'NEW'
				when utj_status='D' then 'DRAFT'
				when utj_status='S' then 'SAVE'
				when utj_status='R' then 'REQUEST'
				when utj_status='A' then 'APROVED'
				when utj_status='P' then 'PAID'
				else 'CANCEL'
			end) as utj_status,
			spuk_dtl_scheme,
			(select GROUP_CONCAT(distinct(concat(bank_name,' - ',rv_bank_rek)))
            	from repayment_rv a
             	left join fin_trn_rv b on a.rrv_no_rv=b.rv_no
            	left join mst_rekening_outlet c on b.rv_bank_rek=c.rekout_no
            	left join mst_bank d on c.rekout_id=d.bank_id
            	where rrv_spuk_id=spuk_id) as rv_bank_rek,
            concat(bank_name,' - ',pv_bank_rek) as pv_bank_rek,
			pv_no,
			utj_rv_fif
		from 
			spuk_hdr a
			left join spuk_dtl b on a.spuk_id=b.spuk_dtl_id
			left join infinity.titik c on a.spuk_outlet=c.kode_titik
			left join unit_titip_jual d on b.spuk_dtl_utj=d.utj_id
			left join mst_suppliers e on a.spuk_cust=e.supl_id
			left join fin_trn_payment f on a.spuk_id=f.pv_proses_id
			left join mst_rekening g on f.pv_paid_rek=g.rek_no
            left join mst_bank h on g.rek_bank=h.bank_id
		where 
			(pv_paid_date between '$start' and '$end') and
			spuk_outlet like '%$outlet%' and
			spuk_status!='J'";
			
/* $query = "select * from report_dealership
		where 
			(pv_paid_date between '$start' and '$end') and
			spuk_outlet like '%$outlet%' and
			spuk_status!='J'"; */
$result = mysqli_query($con,$query) or die(mysqli_error($con));			
while($data = mysqli_fetch_array($result)){
	$no = $no +1;
	$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue("A$baris", $no)
				 ->setCellValue("B$baris", $data['spuk_date'])
				 ->setCellValue("C$baris", $data['spuk_dtl_utj'])
				 ->setCellValue("D$baris", $data['spuk_outlet'])
				 ->setCellValue("E$baris", $data['nama_titik'])
				 ->setCellValue("F$baris", $data['utj_no_paket'])
				 ->setCellValueExplicit("G$baris", $data['utj_no_contract'])
				 ->setCellValue("H$baris", $data['spuk_id'])
				 ->setCellValue("I$baris", $data['utj_grade'])
				 ->setCellValue("J$baris", $data['spuk_cust'])
				 ->setCellValue("K$baris", $data['supl_name'])
				 ->setCellValue("L$baris", $data['utj_nopol'])
				 ->setCellValue("M$baris", $data['utj_noka'])
				 ->setCellValue("N$baris", $data['utj_nosin'])
				 ->setCellValue("O$baris", $data['utj_type'])
				 ->setCellValue("P$baris", $data['utj_tahun'])
				 ->setCellValue("Q$baris", $data['spuk_dtl_total'])
				 ->setCellValue("R$baris", $data['utj_hutang_konsumen'])
				 ->setCellValue("S$baris", $data['rv_received_date'])
				 ->setCellValue("T$baris", $data['pv_paid_date'])
				 ->setCellValue("U$baris", $data['utj_status'])
				 ->setCellValue("V$baris", $data['spuk_dtl_scheme'])
				 ->setCellValue("W$baris", $data['rv_bank_rek'])
				 ->setCellValue("X$baris", $data['pv_bank_rek'])
				 ->setCellValue("Y$baris", $data['pv_no'])
				 ->setCellValue("Z$baris", $data['utj_rv_fif']);
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