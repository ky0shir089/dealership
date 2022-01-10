<?php
session_start();

if (empty($_SESSION['uid']) AND empty($_SESSION['password']) AND empty($_SESSION['username'])){
  echo "<script>";
  echo "alert('Untuk Mengakses Konten Anda Harus Login');";
  echo "window.top.location.href='../../../../efiling/';";
  echo "</script>";
}
?>