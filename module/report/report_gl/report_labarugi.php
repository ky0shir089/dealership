<?php

require_once '../../../conn2.php';

$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$kode_titik = $_REQUEST['kode_titik'] == "" ? "" : $_REQUEST['kode_titik'];
$cabang_bmr = $_REQUEST['cabang_bmr'] == "" ? "ALL" : $_REQUEST['cabang_bmr'];
if($kode_titik == ""){
	$segment = "90000";
} else {
	$segment = $kode_titik;
}

$query = "select sum(gl_cr) as administrasi_mokas from gl_journal where gl_segment2=6210101 and (gl_date between '$start' and '$end')";
$hasil = mysqli_query($con,$query);
if($hasil == false){
	array_push($error,mysqli_error($con));
} else {
	$data = mysqli_fetch_array($hasil);
}

$filename = "laba-rugi";
$extension =  "txt";
$FileCounter = 1;
$FinalFilename = $filename . '_' . $FileCounter++ . '.' . $extension;
while (file_exists( 'laba-rugi/'.$FinalFilename )){
	$FinalFilename = $filename . '_' . $FileCounter++ . '.' . $extension;
}
$handle = fopen('laba-rugi/'.$FinalFilename, "w");
$content = "\t\t\t\t\tLAPORAN LABA RUGI\n
\t\t\t\tTANGGAL: ".$start." S/D ".$end."\n
\t\t\t\t\tOUTLET : ".$cabang_bmr."\n
------------------------------------------------------------------------------------------------
\tKETERANGAN\t\t\t\t\t\t\t\t   SALDO
------------------------------------------------------------------------------------------------
PENDAPATAN\n
6210001 - PENDAPATAN ADMINISTRASI BMR\n
   6210101 - ADMINISTRASI MOKAS\t\t\t\t\t\t\t ".$data['administrasi_mokas']."
------------------------------------------------------------------------------------------------
TOTAL PENDAPATAN\t\t\t\t\t\t\t\t ".$data['administrasi_mokas']."
------------------------------------------------------------------------------------------------\n
LABA\t\t\t\t\t\t\t\t\t\t ".$data['administrasi_mokas']."\n
------------------------------------------------------------------------------------------------";
fwrite($handle, $content);
fclose($handle);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$FinalFilename);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('laba-rugi/'.$FinalFilename));
readfile('laba-rugi/'.$FinalFilename);
exit;

?>
 