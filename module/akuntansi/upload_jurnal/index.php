<!DOCTYPE html>
<html>
<head>
	<?php 
		include "../../../session.php";
		include "../../../css/css.php"; 
	?>
	<title>Upload Booking</title>
</head>
<body>
	<div class="easyui-panel" style="margin:10px" data-options="fit:true,border:false">
		<?php if(@$_SESSION['mobile'] == 1){ ?>
			<header>
				<div class="m-toolbar">
					<div class="m-left">
						<a href="#" class="easyui-linkbutton m-back" data-options="plain:true,outline:true,back:true">Back</a>
					</div>
					<div class="m-title">Upload Booking</div>
				</div>
			</header>
		<?php } ?>
		<b>Panduan Upload Data Booking:</b>
		<ul>
			<li>Mulai isi data di baris ke 2, kolom B2.</li>
			<li>Untuk isian tanggal, format isian: YYYY-MM-DD.</li>
			<li>File yang bisa di upload hanya yang ber ekstensi .xls, .xlsx, .csv.</li>
			<li>Download template format file --> <a href="uploads/templates.xlsx" target="_blank">Disini</a></li>
		</ul>
		<form method="post" action="import.php" enctype="multipart/form-data">
			<?php if(@$_SESSION['mobile'] == 1){ ?>
				<input class="easyui-filebox" name="file" style="width:95%" 
					data-options="
						label:'Pilih File:', 
						labelPosition:'top',
						prompt:'Choose a file...'">
				<p><input id="btnSubmit" type="submit" name="import" value="Upload" style="width:95%;height:38px"></p>
			<?php } else { ?>
				Pilih File: <input type="file" id="file" name="file" accept=".xls, .xlsx, .csv" /><input id="btnSubmit" type="submit" name="import" value="Upload" />
			<?php } ?>
		</form>
	</div>
	
	<?php include '../../../js/js.php'; ?>
</body>    
</html>