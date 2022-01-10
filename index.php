<?php session_start(); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="plugins/jquery-easyui-1.5.1/themes/default/easyui.css" media="screen">
	<link rel="stylesheet" type="text/css" href="plugins/jquery-easyui-1.5.1/themes/icon.css" media="screen">
	<link rel="stylesheet" type="text/css" href="plugins/jquery-easyui-1.5.1/demo/demo.css" media="screen">
	<link rel="stylesheet" type="text/css" href="plugins/style.css" media="screen">
	<script type="text/javascript" src="plugins/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="plugins/jquery-easyui-1.5.1/jquery.easyui.min.js"></script>
    <title>Login - Raharja Motor</title>    
	<script type="text/javascript">
		//auto focus
		$(function() {
			if($("#uid").val()==""){
				$("#uid").textbox('textbox').focus();
			} else {
				$("#password").textbox('textbox').focus();
			}
		});
	</script>
</head>
<body class="easyui-layout" fit="true">
    <div style="margin:20px 0" align="center"></div>
	<div align="center">
		<form id="ff" method="post" action="cek_login.php">
			<div class="easyui-panel" title="Login to System" style="width:400px;padding:30px 70px 20px 70px">
				<div style="margin-bottom:10px">
					<input class="easyui-textbox" style="width:100%;height:40px;padding:12px" data-options="prompt:'Username',iconCls:'icon-man',iconWidth:38" name="uid" id="uid" value="<?php echo @$_COOKIE['username']; ?>">
				</div>
				<div style="margin-bottom:20px">
					<input class="easyui-textbox" type="password" style="width:100%;height:40px;padding:12px" data-options="prompt:'Password',iconCls:'icon-lock',iconWidth:38" name="password" id="password">
				</div>
				<div style="margin-bottom:20px">
					<input type="checkbox" checked="checked" name="rememberme">
					<span>Remember me</span>
				</div>
				<div>
					<input name="submit" type="submit" style="padding:5px 0px;width:100%" value="LOGIN" />
				</div>
			</div>
		</form>
		<p>Prima Aulia Rachman - IT Department</p>
		<p>Copyright &copy 2017</p>
	</div>
</body>
</html>