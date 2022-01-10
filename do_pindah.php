<?php
session_unset();
session_start();

$_SESSION['outlet'] = $_POST['new1'];
$_SESSION['out_name'] = $_POST['new2'];

echo "Pindah Cabang Berhasil";
?>