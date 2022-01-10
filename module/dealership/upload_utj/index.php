<!DOCTYPE html>
<html>
<head>
	<title>Upload UJT</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(document).ready(function () {
			$("#formABC").submit(function (e) {
				//disable the submit button
				$("#btnSubmit").attr("disabled", true);			
				return true;
			});
		});
	</script>
</head>
<body>
	<div class="easyui-panel" style="padding:20px" fit="true">
		<b>Panduan Upload UTJ:</b>
		<ul>
			<li>Mulai isi data di baris ke 2, kolom B2.</li>
			<li>Untuk isian tanggal, format isian: YYYY-MM-DD.</li>
			<li>File yang bisa di upload hanya yang ber ekstensi .xls, .xlsx, .csv.</li>
			<li>Download template format file --> <a href="../../../uploads/template.xlsx" target="_blank">Disini</a></li>
		</ul>
		<form id="formABC" method="post" action="import.php" enctype="multipart/form-data">
			Pilih File: <input type="file" id="file" name="file" accept=".xls, .xlsx, .csv" /><input id="btnSubmit" type="submit" name="import" value="Upload" />
		</form>
	</div>
</body>
</html>