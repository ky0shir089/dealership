<?php
session_start();

if (empty($_SESSION['uid']) AND empty($_SESSION['password']) AND empty($_SESSION['username'])){
  echo "<script>alert('Untuk Mengakses Konten Anda Harus Login'); window.top.location.href='../../../../dealership';</script>";
}
?>