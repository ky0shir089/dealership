<?php

require_once '../../../conn2.php';

$start = $_REQUEST['start'];
$start2 = strtotime($start);
$start3 = date('Y',$start2)."-01-01";
$end = $_REQUEST['end'];
$kode_titik = $_REQUEST['kode_titik'] == "" ? "" : $_REQUEST['kode_titik'];
$cabang_bmr = $_REQUEST['cabang_bmr'] == "" ? "ALL" : $_REQUEST['cabang_bmr'];
if($kode_titik == ""){
	$segment = "90000";
} else {
	$segment = $kode_titik;
}

$query = "select * from
	(
		(select sum(gl_dr) as saldo_bank from gl_journal where gl_segment1=90000 and gl_segment2=112010134 and (gl_date between '$start' and '$end')) as s1,
		(select sum(gl_cr) as hutang_ppn from gl_journal where gl_segment1=90000 and gl_segment2=3220701 and (gl_date between '$start' and '$end')) as s2,
		(select sum(gl_cr) as titipan_leasing from gl_journal where gl_segment2=3310101 and (gl_date between '$start' and '$end')) as s3,
		(select sum(gl_cr) as t1 from gl_journal where gl_segment1=90000 and gl_segment2=3310201 and (gl_date between '$start' and '$end')) as s4,
		(select sum(gl_dr) as t2 from gl_journal where gl_segment1=90000 and gl_segment2=3310201 and gl_type='JV' and (gl_date between '$start' and '$end')) as s5,
		(select sum(gl_cr) as laba1 from gl_journal where gl_segment2=6210101 and (gl_date between '$start' and '$end')) as s6,
		(select sum(gl_cr) as laba2 from gl_journal where gl_segment2=6210101 and (gl_date between '$start3' and '$end')) as s7,
		(select sum(gl_cr) as laba3 from gl_journal where gl_segment2=6210101 and gl_date < '$start3') as s8
	)";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
	$titipan_lain2 = $data['t1']-$data['t2'];
	$pasiva = $data['hutang_ppn']+$data['titipan_leasing']+$titipan_lain2+$data['laba1'];
}

$filename = "neraca";
$extension =  "txt";
$FileCounter = 1;
$FinalFilename = $filename . '_' . $FileCounter++ . '.' . $extension;
while (file_exists( 'neraca/'.$FinalFilename )){
	$FinalFilename = $filename . '_' . $FileCounter++ . '.' . $extension;
}
$handle = fopen('neraca/'.$FinalFilename, "w");
$content = "\t\t\t\t\tLAPORAN NERACA\n
\t\t\t\tTANGGAL: ".$start." S/D ".$end."\n
\t\t\t\t\tOUTLET : ".$cabang_bmr."\n
------------------------------------------------------------------------------------------------
\tKETERANGAN\t\t\t\t\t\t\t\t   SALDO
------------------------------------------------------------------------------------------------
AKTIVA\n
1110001 - KAS BMR\n
   1120001 - BANK BMR\n
      1120101 - BANK BCA\n
         112010134 - BANK BCA HEAD OFFICE - JAKARTA 6590303494\t\t\t ".$data['saldo_bank']."
------------------------------------------------------------------------------------------------
TOTAL AKTIVA\t\t\t\t\t\t\t\t\t ".$data['saldo_bank']."
------------------------------------------------------------------------------------------------\n\n
PASIVA\n
3220001 - PAJAK YANG MASIH HARUS DIBAYAR BMR\n
   3220701 - HUTANG PPN\t\t\t\t\t\t\t\t    ".$data['hutang_ppn']."\n
3310001 - PENDAPATAN YANG MASIH HARUS DITERIMA BMR\n
   3310101 - TITIPAN LEASING\t\t\t\t\t\t\t   ".$data['titipan_leasing']."\n
   3310201 - TITIPAN LAIN-LAIN\t\t\t\t\t\t\t ".$titipan_lain2."\n
5210001 - Laba ditahan BMR\n
   5210101 - Laba ditahan Bulan ini\t\t\t\t\t\t   ".$data['laba1']."\n
   5210201 - Laba ditahan Tahun Berjalan\t\t\t\t\t   ".$data['laba2']."\n
   5210301 - Laba ditahan Tahun Sebelumnya\t\t\t\t   ".$data['laba3']."\n
------------------------------------------------------------------------------------------------
TOTAL PASIVA\t\t\t\t\t\t\t\t\t ".$pasiva."
------------------------------------------------------------------------------------------------";
fwrite($handle, $content);
fclose($handle);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$FinalFilename);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('neraca/'.$FinalFilename));
readfile('neraca/'.$FinalFilename);
exit;

?>
 