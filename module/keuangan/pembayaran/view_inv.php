<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>View Invoice</title>
	<script type="text/javascript">
		// load form
		$(function(){
			$('#fm').form('load','view.php?id=<?= $_REQUEST['id']; ?>');
		});
	</script>
</head>
<body>
<div class="easyui-panel" data-options="fit:true,border:false">
	<form id="fm">
		<table>
			<tr>
				<td>No Invoice</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_no" name="invhdr_no" class="easyui-textbox" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Type Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_mst_code" name="invhdr_mst_code" class="easyui-textbox" style="width:60px" data-options="editable:false">
					<input id="type_trx" name="type_trx" class="easyui-textbox" style="width:250px" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_created" name="invhdr_created" class="easyui-textbox" style="width:80px" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_status" name="invhdr_status" class="easyui-textbox" style="width:80px" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Bayar Ke</td>
				<td width="1px">:</td>
				<td>
					<input id="supl_type" name="supl_type" class="easyui-textbox" style="width:80px" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Nama</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_supplier" name="invhdr_supplier" class="easyui-textbox" style="width:60px" data-options="editable:false">
					<input id="supl_name" name="supl_name" class="easyui-textbox" style="width:200px" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Kode Bank</td>
				<td width="1px">:</td>
				<td>
					<input id="bank_name" name="bank_name" class="easyui-textbox" style="width:70px" data-options="editable:false">
					<input id="rek_name" name="rek_name" class="easyui-textbox" style="width:250px" data-options="editable:false">
					<input id="invhdr_rek_no" name="invhdr_rek_no" class="easyui-textbox" style="width:120px" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Code Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_segment2" name="invhdr_segment2" class="easyui-textbox" style="width:60px" data-options="editable:false">
					<input id="coa_description" name="coa_description" class="easyui-textbox" style="width:200px" data-options="editable:false">
				</td>
			</tr>
			<tr id="lov_ref" style="display:none">
				<td>No Referensi</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_reff_no" name="invhdr_reff_no" class="easyui-textbox" style="width:130px" data-options="editable:false">
					<input id="rv_amount" name="rv_amount" class="easyui-numberbox" style="width:80px;text-align:right" data-options="groupSeparator:','" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_desc" name="invhdr_desc" class="easyui-textbox" style="width:500px" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Amount</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_amount" name="invhdr_amount" style="width:80px;text-align:right" class="easyui-numberbox" data-options="groupSeparator:','" data-options="editable:false">
				</td>
			</tr>
		</table>
	</form>
</div>
</body>
</html>