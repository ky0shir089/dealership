<?php

require_once '../../../conn2.php';
session_start();

$id = $_REQUEST['id'];
$spuk_date = $_REQUEST['spuk_date'];
$supl_name = $_REQUEST['supl_name'];
$total = "";
$no = 0;

$query2 = "select 
			utj_nopol,
			utj_nosin,
			utj_type,
			utj_tahun,
			utj_hutang_konsumen
		from spuk_dtl a
		left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
		where spuk_dtl_id='$id'";
$hasil2 = mysqli_query($con,$query2);

$handle = fopen('tagihan/'.$id.'.txt', "w");
$content = "--------------------------------------------------------------------------------
\t\t\tTAGIHAN PELUNASAN UNIT
--------------------------------------------------------------------------------\r\n
Tanggal		: ".$spuk_date."\n
No		: ".$id."\n
Kepada Yth	: ".$supl_name."\r\n
DESKRIPSI
--------------------------------------------------------------------------------
Pembayaran atas unit kendaraan sepeda motor bekas yang telah disepakati.\r\n
NO	NO POLISI	NO MESIN	TIPE		TAHUN	AMOUNT\n";
fwrite($handle, $content);
while($data2 = mysqli_fetch_array($hasil2)){
	$no++;
	$hbm = format_rupiah($data2['utj_hutang_konsumen']);
	$content = "\r\n$no   \t$data2[utj_nopol]\t$data2[utj_nosin]\t$data2[utj_type]\t$data2[utj_tahun]\t$hbm";
	$total+= $data2['utj_hutang_konsumen'];
	fwrite($handle, $content);
}
$content = "\r\nTOTAL\t\t\t\t\t\t\t        ".format_rupiah($total)."\r\n
--------------------------------------------------------------------------------
TERBILANG: ".ucwords(terbilang($total))."
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
header('Content-Length: ' . filesize('tagihan/'.$id.'.txt'));
readfile('tagihan/'.$id.'.txt');
exit;

?>
 