<?php
function sukses_masuk($username,$pass){
	$con = mysqli_connect("localhost","root","","dealership");
	
	// Apabila username dan password ditemukan
	$log_in = "SELECT
			user_id,
			user_name,
			user_password,
			user_chpass,
			user_personid,
			user_outlet,
			person_dept,
			person_job,
			nama_titik 
		FROM users a 
			join infinity.titik b on a.user_outlet=b.kode_titik 
			join hr_people_all c on a.user_personid=c.person_id
		WHERE 
			user_id='$username' AND 
			user_password='$pass' and 
			user_enable_sts='Y'";
	$login = mysqli_query($con, $log_in);
			//die($log_in);
	$ketemu = mysqli_num_rows($login);
	$r = mysqli_fetch_array($login);
	if ($ketemu == 1){
		session_start();
	
		$_SESSION['uid']      	= $r['user_id'];
		$_SESSION['password'] 	= $r['user_password'];
		$_SESSION['username'] 	= $r['user_name'];
		$_SESSION['person_id']  = $r['user_person_id'];
		$_SESSION['outlet']     = $r['user_outlet'];
		$_SESSION['out_name']	= $r['nama_titik'];
		$_SESSION['dept']		= $r['person_dept'];
		$_SESSION['job']		= $r['person_job'];
		
		$ipaddress = empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP'];
		$agent = $_SERVER["HTTP_USER_AGENT"];
	
		$sql = "UPDATE users SET user_lastlogin=now(),user_ipaddress='$ipaddress',user_agent='$agent' WHERE user_id='$username' AND user_password='$pass'";
		mysqli_query($con,$sql);
		
		if($r['user_chpass']=='Y'){
			header('location:changepass.php');
		}else{
			header('location:home.php');
		}
		
		if (isset($_POST['rememberme'])) {
            // Set cookie to last 1 day
			// 60 sec * 60 minute * 24 hour
            setcookie('username', $_POST['uid'], time()+60);
        }
	}
	return false;
}

function salah_username($username){
  echo "<link href='plugins/screen.css' rel='stylesheet' type='text/css'>
  <link href='plugins/reset.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, Username <b>$username</b> tidak dikenal.";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";	
  return false;
}
function salah_password(){
  echo "<link href='plugins/screen.css' rel='stylesheet' type='text/css'>
  <link href='plugins/reset.css' rel='stylesheet' type='text/css'>
  <center><br><br><br><br><br><br>Maaf, silahkan cek kembali <b>Password</b> Anda<br>";
  echo "<div> <a href='index.php'><img src='images/kunci.png'  height=176 width=143></a>
  </div>";
  echo "<input type=button class='button buttonblue mediumbtn' value='KEMBALI' onclick=location.href='index.php'></a></center>";
  return false;
}