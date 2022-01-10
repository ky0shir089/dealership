<?php
include "conn2.php";
session_start();

session_destroy();
		
header("location:../dealership");

//close koneksi
mysql_close($conn);
?>