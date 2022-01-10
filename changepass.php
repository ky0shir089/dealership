<?php
	include 'conn.php';
	session_start();
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login - Raharja Motor</title>
    <link rel="stylesheet" type="text/css" href="plugins/jquery-easyui-1.5.1/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="plugins/jquery-easyui-1.5.1/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="plugins/jquery-easyui-1.5.1/demo/demo.css">
	<script type="text/javascript" src="plugins/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-easyui-1.5.1/jquery.easyui.min.js"></script>
	<script type="text/javascript">
		$(document).on('submit', function (event) {
			if($('#pass').val() == "" || $('#pass2').val() == ""){
				alert("Anda belum mengisikan Password.");
				event.preventDefault();
			}
			if($('#pass').val().length < 8){
				alert("Password harus 8 digit");
				event.preventDefault();
			}
			if($('#pass').val() != $('#pass2').val()){
				alert("Password Tidak Sama");
				event.preventDefault();
			}
		});
	</script>
</head>
<body>
    <div style="margin:20px 0" align="center"></div>
	<div align="center">
		<form id="ff" method="post" action="do_change.php" onsubmit="return validasi(this)">
			<div class="easyui-panel" title="Masukan Password Baru" style="width:400px;padding:30px 70px 20px 70px">
				<div style="margin-bottom:10px;display:none">
					<input class="easyui-textbox" style="width:100%;height:40px;padding:12px" data-options="prompt:'Username',iconCls:'icon-man',iconWidth:38" name="uid" value="<?php echo $_SESSION['uid']; ?>">
				</div>
				<div style="margin-bottom:10px">
					New Password: <input class="easyui-textbox" type="password" style="width:100%;height:40px;padding:12px" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38" name="pass" id="pass">
				</div>
				<div style="margin-bottom:20px">
					Re-type Password: <input class="easyui-textbox" type="password" style="width:100%;height:40px;padding:12px" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38" name="pass2" id="pass2">
				</div>
				<div>
					<input name="submit" type="submit" style="padding:5px 0px;width:100%" value="SUBMIT" />
				</div>
			</div>
		</form>
	</div>
</body>
</html>