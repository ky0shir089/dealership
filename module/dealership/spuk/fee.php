<?php

require_once '../../../conn2.php';
session_start();

$id = $_REQUEST['id'];
$spuk_date = $_REQUEST['spuk_date'];
$supl_name = $_REQUEST['supl_name'];

$query = "select 
			sum(spuk_dtl_scheme) as scheme
		from spuk_dtl
		where
			spuk_dtl_id='$id'";
$hasil = mysqli_query($con,$query);
$data = mysqli_fetch_array($hasil);
$fee = format_rupiah($data['scheme']);

$handle = fopen('fee/'.$id.'.txt', "w");
$content = "--------------------------------------------------------------------------------
\t\t\tTAGIHAN ATAS FEE
--------------------------------------------------------------------------------\r\n
Tanggal		: ".$spuk_date."\n
No		: ".$id."\n
Kepada Yth	: ".$supl_name."\r\n
DESKRIPSI
--------------------------------------------------------------------------------
Pembayaran fee atas jasa yang telah diberikan kepada saudara dalam mencarikan unit kendaraan sepeda motor bekas yang telah disepakati sebesar ".$fee."\r\n
--------------------------------------------------------------------------------
TERBILANG: ".ucwords(terbilang($data['scheme']))."
--------------------------------------------------------------------------------\r\n
CARA PEMBAYARAN: BANK _____ dengan Rekening No. _______________ atas nama _______________.
--------------------------------------------------------------------------------\r\n
Mohon setelah ditransfer bukti pembayaran dapat dikirimkan kepada kami agar dapat segera kami proses untuk di verifikasi.\r\n
Demikian yang kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.\r\n
Hormat kami,
PT. Bersama Makmur Raharja\r\n\r\n\r\n\r\n
(                        )";
fwrite($handle, $content);
fclose($handle);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$id.'.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('fee/'.$id.'.txt'));
readfile('fee/'.$id.'.txt');
exit;

?>
 