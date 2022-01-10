<?php
require_once '../../../conn2.php';
session_start();

$id = rand();
$start = $_POST['start'];
$end = $_POST['end'];
$outlet = $_POST['kode_titik'];
$no = 0;

$query = "select
			invhdr_no,
			invhdr_created,
			invmst_desc,
			invhdr_status,
			supl_name,
			invhdr_rek_no,
			coa_description,
			invhdr_desc,
			invhdr_amount,
			invhdr_reff_no,
			rv_received_from,
			(case
				when invhdr_reff_amount=0 then ''
				else invhdr_reff_amount
			end) as invhdr_reff_amount,
			invhdr_payment_no,
			invhdr_payment_date
		from fin_trn_inv_hdr a 
		join fin_mst_invhdr b on a.invhdr_mst_code=b.invmst_code
		join mst_suppliers c on a.invhdr_supplier=c.supl_id
		join gl_coa d on a.invhdr_segment2=d.coa_code
		left join fin_trn_rv e on a.invhdr_reff_no=e.rv_no
		where invhdr_created between '$start' and '$end'
		order by invhdr_created asc,invhdr_no asc";
$result = mysqli_query($con,$query) or die(mysqli_error($con));

$handle = fopen('report/'.$id.'.txt', "w");
$content = "No|No. Invoice|Tanggal Invoice|Type TRX|Status|Supplier|No. Rekening|Code TRX|Keterangan|Inv. Amount|No. Referensi|Terima Dari|Reff. Amount|No. PV|Tanggal Bayar\r\n";
fwrite($handle, $content);
while($data = mysqli_fetch_array($result)){
	$no++;
	$content = "$no|$data[invhdr_no]|$data[invhdr_created]|$data[invmst_desc]|$data[invhdr_status]|$data[supl_name]|$data[invhdr_rek_no]|$data[coa_description]|$data[invhdr_desc]|$data[invhdr_amount]|$data[invhdr_reff_no]|$data[rv_received_from]|$data[invhdr_reff_amount]|$data[invhdr_payment_no]|$data[invhdr_payment_date]\n";
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
 