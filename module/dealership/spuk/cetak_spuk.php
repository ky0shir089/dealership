<?php

require_once '../../../conn2.php';
session_start();

$spuk_id = $_REQUEST['spuk_id'];
$supl_name = $_REQUEST['supl_name'];
$spuk_total_hutang = $_REQUEST['spuk_total_hutang'];
$spuk_subtotal = $_REQUEST['spuk_subtotal'];

$query = "select 
			utj_no_paket,
			nama_titik
		from spuk_dtl a
		inner join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
		inner join spuk_hdr c on a.spuk_dtl_id=c.spuk_id
		inner join infinity.titik d on c.spuk_outlet=d.kode_titik
		where spuk_dtl_id='$spuk_id'
		limit 0,1";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);

$handle = fopen('cetak/spuk_'.$spuk_id.'.txt', "w");
$content = "
[DEALERSHIP] PELUNASAN ".$spuk_id."\r\n
Cabang: ".$data['nama_titik']."\r\n
No Paket Distribusi: ".$data['utj_no_paket']."\r\n
Buyer: ".$supl_name."\r\n
HBM: ".format_rupiah($spuk_total_hutang)."\r\n
OTR: ".format_rupiah($spuk_subtotal)."\r\n
Silahkan lakukan pelunasan untuk NO SPUK tersebut.\r\n
Terima Kasih";
fwrite($handle, $content);
fclose($handle);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=spuk_'.$spuk_id.'.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('cetak/spuk_'.$spuk_id.'.txt'));
readfile('cetak/spuk_'.$spuk_id.'.txt');
exit;

?>
 