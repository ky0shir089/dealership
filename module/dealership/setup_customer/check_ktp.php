<?php
include '../../../conn2.php';

$cust_ktp = $_POST['cust_ktp'];

$sql = "select cust_ktp from mst_customers where cust_ktp='$cust_ktp'";
$result = mysqli_query($con,$sql);

if(mysqli_num_rows($result) == 0){
	echo 0;
} else {
	echo 1;
}
?>