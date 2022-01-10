<?php
include '../../../conn2.php';
include '../../../cek_session.php';
include '../../../plugins/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';
include '../../../plugins/PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php';

error_reporting(0);

$target_dir = "../../../uploads/";

if(!is_dir($target_dir.$_SESSION['outlet'])){
	 mkdir($target_dir.$_SESSION['outlet']); 
}

$target_dir = $target_dir.$_SESSION['outlet'].'/';
$file = $_FILES['file']['name'];
$fname = pathinfo($file,PATHINFO_FILENAME);
$fext = pathinfo($file,PATHINFO_EXTENSION);
$fupload = $target_dir.$file;
$counter = 0;

while($counter = 1){
	$a = 'a_'.$counter;
	$counter++;
}

echo $a;

//move_uploaded_file($_FILES["file"]["tmp_name"], $fupload);
?>