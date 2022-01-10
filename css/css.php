<?php
	//path url
	$path = str_replace($_SERVER["DOCUMENT_ROOT"],'', str_replace('\\','/',realpath(dirname(__FILE__,2))));
	define('path',$path);
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?= path; ?>/assets/jquery-easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="<?= path; ?>/assets/jquery-easyui/themes/mobile.css" />
<link rel="stylesheet" type="text/css" href="<?= path; ?>/assets/jquery-easyui/themes/icon.css" />
<link rel="stylesheet" type="text/css" href="<?= path; ?>/assets/jquery-easyui/themes/color.css" />
<link rel="stylesheet" type="text/css" href="<?= path; ?>/css/style.css" />