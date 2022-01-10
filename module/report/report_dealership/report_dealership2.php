<?php
require_once '../../../conn2.php';
session_start();

$id = rand();
$start = $_POST['start'];
$end = $_POST['end'];
$outlet = $_POST['kode_titik'];
$no = 0;

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
				else 'PAID'
			end) as utj_status,
			spuk_dtl_scheme,
			(select GROUP_CONCAT(distinct(concat(bank_name,' - ',rv_bank_rek)))
            	from repayment_rv a
             	left join fin_trn_rv b on a.rrv_no_rv=b.rv_no
            	left join mst_rekening_outlet c on b.rv_bank_rek=c.rekout_no
            	left join mst_bank d on c.rekout_id=d.bank_id
            	where rrv_spuk_id=spuk_id) as rv_bank_rek,
            concat(bank_name,' - ',pv_bank_rek) as pv_bank_rek,
			pv_no
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
$result = mysqli_query($con,$query) or die(mysqli_error($con));

$handle = fopen('report/'.$id.'.txt', "w");
$content = "REPORT DEALERSHIP TGL ".$start." s/d ".$end."\r\n
NO|TGL SPUK|NO INVENTORY|KODE CABANG|NAMA CABANG|NO PAKET DISTRIBUSI|NO KONTRAK|NO SPUK|GRADE|ID BUYER|NAMA BUYER|NO POL|NO RANGKA|NO MESIN|TYPE|TAHUN|OTR|HBM|TGL SETOR|TGL TRANSFER|STATUS|PROFIT|BANK IN|BANK OUT|NO PV\r\n";
fwrite($handle, $content);
while($data = mysqli_fetch_array($result)){
	$no++;
	$content = "$no|$data[spuk_date]|$data[spuk_dtl_utj]|$data[spuk_outlet]|$data[nama_titik]|$data[utj_no_paket]|$data[utj_no_contract]|$data[spuk_id]|$data[utj_grade]|$data[spuk_cust]|$data[supl_name]|$data[utj_nopol]|$data[utj_noka]|$data[utj_nosin]|$data[utj_type]|$data[utj_tahun]|$data[spuk_dtl_total]|$data[utj_hutang_konsumen]|$data[rv_received_date]|$data[pv_paid_date]|$data[utj_status]|$data[spuk_dtl_scheme]|$data[rv_bank_rek]|$data[pv_bank_rek]|$data[pv_no]\n";
	fwrite($handle, $content);
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$id.'.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('report/'.$id.'.txt'));
readfile('report/'.$id.'.txt');
exit;

?>
 