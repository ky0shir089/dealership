<?php
$con = mysqli_connect("localhost","root","","dealership");

// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
//path url
$path = str_replace($_SERVER["DOCUMENT_ROOT"],'', str_replace('\\','/',realpath(dirname(__FILE__))));
define('path',$path);

//fungsi format rupiah 
function format_rupiah($rp) {
	$rows = "Rp " . number_format($rp, 0, "", ".");
	return $rows;
}

function terbilang($x) {
	$angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
	if ($x < 12)
		return " " . $angka[$x];
	elseif ($x < 20)
		return terbilang($x - 10) . " belas";
	elseif ($x < 100)
		return terbilang($x / 10) . " puluh" . terbilang($x % 10);
	elseif ($x < 200)
		return "seratus" . terbilang($x - 100);
	elseif ($x < 1000)
		return terbilang($x / 100) . " ratus" . terbilang($x % 100);
	elseif ($x < 2000)
		return "seribu" . terbilang($x - 1000);
	elseif ($x < 1000000)
		return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
	elseif ($x < 1000000000)
		return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
}

//date sequence
$year = date('y');
define('year',$year);

$month = date('m');
define('month',$month);
?> 