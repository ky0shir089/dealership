<?php

require_once '../../../conn2.php';
session_start();

$id = $_REQUEST['id'];
$supl_name = $_REQUEST['supl_name'];
$spuk_total_hutang = $_REQUEST['spuk_total_hutang'];
$total = "";
$no = 0;

$query = "select 
			nama_titik,
			pv_paid_date,
			bank_name,
			pv_bank_rek
		from spuk_hdr a 
		left join infinity.titik b on a.spuk_outlet=b.kode_titik
		left join fin_trn_payment c on a.spuk_id=c.pv_proses_id
		join mst_rekening_outlet d on c.pv_bank_rek=d.rekout_no
		join mst_bank e on d.rekout_id=e.bank_id
		where spuk_id='$id'";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
	$bank = $data['bank_name'];
	
	if($bank == 'BCA'){
		$rek = $bank.' 13017996';
	} 
	if($bank == 'PERMATA'){
		$rek = $bank.' 200191161';
	} 
	else {
		$rek = $bank.' 1200000056007';
	}
	
}

$query2 = "select 
			utj_nosin,
			utj_no_paket,
			utj_hutang_konsumen
		from spuk_dtl a
		left join unit_titip_jual b on a.spuk_dtl_utj=b.utj_id
		where spuk_dtl_id='$id'";
$hasil2 = mysqli_query($con,$query2);

$handle = fopen('cetak/'.$id.'.txt', "w");
$content = "\t\t\tKONFIRMASI PELUNASAN UNIT ".$data['nama_titik']."\r\n
Kepada Yth	:\n
Cabang		: ".$data['nama_titik']."\n
Perihal		: Konfirmasi Pelunasan Unit\r\n
Sesuai konfirmasi pelunasan ke Cabang FIF ".$data['nama_titik']." maka dengan ini kami beritahukan bahwa pada tanggal ".$data['pv_paid_date']." kami telah melakukan pelunasan ke rekening ".$rek." an PT FIF sejumlah ".format_rupiah($spuk_total_hutang)." dengan rincian sebagai berikut:\r\n
No  No. Mesin\tNo. Distribusi\t\tNama Konsumen\t\tHBM\r\n";
fwrite($handle, $content);
while($data2 = mysqli_fetch_array($hasil2)){
	$no++;
	$hbm = format_rupiah($data2['utj_hutang_konsumen']);
	$content = "\r\n$no   $data2[utj_nosin]\t$data2[utj_no_paket]\t$supl_name\t$hbm";
	$total+= $data2['utj_hutang_konsumen'];
	fwrite($handle, $content);
}
$content = "\r\nTOTAL\t\t\t\t\t\t\t        ".format_rupiah($total)."\r\n
Demikian konfirmasi pelunasan ke Cabang ".$data['nama_titik']." atas unit yang telah dilunasi, dan mohon ditindak lanjuti proses penagihannya. Atas kerjasamanya kami ucapkan terima kasih.\r\n
Hormat kami,\r\nBMR";
fwrite($handle, $content);
fclose($handle);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$id.'.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('cetak/'.$id.'.txt'));
readfile('cetak/'.$id.'.txt');
exit;

?>
 