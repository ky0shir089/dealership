<?php
include 'conn2.php';
include 'fungsi_login.php';
error_reporting(E_ALL);

function anti_injection($data){
  $filter = mysqli_real_escape_string($con, stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$username	= $_POST['uid'];
$pass		= md5($_POST['password']);
// pastikan username dan password adalah berupa huruf atau angka.
if (!ctype_alnum($username) OR !ctype_alnum($pass)){
?>
<script>
	alert('Terdapat karakter yang tidak sesuai.');
	window.location.href='index.php';
</script>
<?php
}else{
	$login	= mysqli_query($con, "SELECT * FROM users WHERE user_id='$username' and user_enable_sts='Y'");
	$ketemu	= mysqli_num_rows($login);
	if ($ketemu == 1){
		$r		= mysqli_fetch_array($login);
		$pwd	= $r['user_password'];
		if ($pwd == $pass){
			sukses_masuk($username,$pass);
		}else{
			salah_password();
		}
	}else{
		salah_username($username);
	}
}
?>
