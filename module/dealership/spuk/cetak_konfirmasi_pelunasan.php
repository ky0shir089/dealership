<?php
include '../../../conn2.php';

$id = $_REQUEST['id'];

$query = "select 
			supl_name,
			nama_titik,
			pv_paid_date,
			pv_paid_rek,
			rek_name,
			pv_amount
		from fin_trn_payment a
		left join mst_suppliers b on a.pv_paid_to=b.supl_id
		left join spuk_hdr c on a.pv_spuk_id=c.spuk_id
		left join infinity.titik d on c.spuk_outlet=d.kode_titik
		left join mst_rekening e on a.pv_paid_rek=e.rek_no";
$result = mysqli_query($con,$query) or die(mysqli_error($conn));
$data = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<style>
		table {
			border-collapse: collapse;
		}
		th {
			border: 2px solid black;
		}
		.mytable tr td {
			border: 2px solid black;
		}
		.sign td {
			width: 25%;
			text-align: center;
			height: 150px;
			margin-bottom: 100px;
		}
		.sign2 td {
			text-align: center;
			height: 150px;
			margin-bottom: 100px;
		}
	</style>
</head>
<body>
	<table border=1>
		<thead>
			<tr>
				<th>No</th>
				<th>No Mesin</th>
				<th>No Distribusi</th>
				<th>Nama Konsumen</th>
				<th>HBM</th>
			</tr>
		</thead>
		<tbody>
			<?php $no = 1; while($data2 = mysql_fetch_array($result2)){ ?>
				<tr>
					<td align="center"><?php echo $no++; ?></td>
					<td><?php echo $data2['part_num']; ?></td>
					<td><?php echo $data2['nama_produk']; ?></td>
					<td align="center"><?php echo $data2['qty']; ?></td>
					<td align="right"><?php echo format_rupiah($data2['total']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4"></td>
				<td align="right"><?php echo format_rupiah($data['total']); ?></td>
			</tr>
		</tfoot>
	</table>
	<br><br>
	<div>Demikian konfirmasi pelunasan ke FIF atas unit kredit yang telah dilunasi, dan mohon ditindak lanjuti proses penagihannya. Atas Kerjasamanya kami ucapkan terima kasih.</div>
	<br><br>
	<div>Hormat Kami,</div>
	<br><br><br><br>
	<div><?= $_SESSION['uid']; ?></div>
</body>
</html>
<?php  
$filename = $id.".pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
//==========================================================================================================  
//Copy dan paste langsung script dibawah ini,untuk mengetahui lebih jelas tentang fungsinya silahkan baca-baca tutorial tentang HTML2PDF  
//==========================================================================================================  
$content = ob_get_clean();  
//$content = $content;  
require_once('../../../plugins/html2pdf-4.03/html2pdf.class.php');  
try  
{  
	$html2pdf = new HTML2PDF('P','A4','en', false, 'ISO-8859-15');  
	$html2pdf->setDefaultFont('Arial');  
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));  
	$html2pdf->Output($filename);  
}  
catch(HTML2PDF_exception $e) { echo $e; }
?> 