<!DOCTYPE html>
<html>
<head>
	<title>View Cancel Unit</title>
	<?php include ("../../../header.php"); ?>
	<meta name="viewport" content="initial-scale=0.5, maximum-scale=1.0, user-scalable=no">
</head>
<body>
	<div class="easyui-panel" fit="true" border="false">
		<form id="fm" method="post">
			<table>
				<tr>
					<td>No Cancel</td>
					<td width="1px">:</td>
					<td>
						<input id="cancel_id" name="cancel_id" class="easyui-textbox" style="width:140px" editable="false">
					</td>
					<td rowspan=6 width="300px" align="center">
						<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton c8" data-options="iconCls:'icon-ok'" onclick="approve()">Approve</a>
						<a href="javascript:void(0)" id="btnReject" class="easyui-linkbutton c8" data-options="iconCls:'icon-cancel'" onclick="reject()">Reject</a>
					</td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td width="1px">:</td>
					<td>
						<input id="cancel_date" name="cancel_date" class="easyui-textbox" style="width:80px" editable="false">
					</td>
				</tr>
				<tr>
					<td>No SPUK</td>
					<td width="1px">:</td>
					<td>
						<input id="spuk_outlet" name="spuk_outlet" class="easyui-textbox" style="width:50px">
						<input id="cancel_spuk_id" name="cancel_spuk_id" class="easyui-textbox" style="width:140px" editable="false">
					</td>
				</tr>
				<tr>
					<td>Supplier Name</td>
					<td width="1px">:</td>
					<td>
						<input id="supl_name" name="supl_name" class="easyui-textbox" style="width:200px" editable="false">
					</td>
				</tr>
				<tr>
					<td>Reason</td>
					<td width="1px">:</td>
					<td>
						<input id="cancel_reason" name="cancel_reason" class="easyui-textbox" style="width:300px" editable="false">
					</td>
				</tr>
				<tr>
					<td>Status</td>
					<td width="1px">:</td>
					<td>
						<input id="cancel_status" name="cancel_status" class="easyui-textbox" style="width:80px" editable="false">
					</td>
				</tr>
			</table>
			<table id="dg_list_unit" title="List Unit" class="easyui-datagrid" style="height:350px"
				data-options="
					autoRowHeight:false,
					striped:true,
					rownumbers:true,
					showFooter:true">
				<thead>
					<tr>
						<th field="spuk_dtl_utj">UTJ ID</th>
						<th field="utj_no_paket">No Paket</th>
						<th field="utj_no_contract">No Contract</th>
						<th field="utj_nopol">No Polisi</th>
						<th field="utj_nosin">No Mesin</th>
						<th field="utj_type">Tipe</th>
						<th field="utj_tahun">Tahun</th>
						<th field="utj_hutang_konsumen" align="right" formatter="numberFormat">Hutang Konsumen</th>
						<th field="spuk_dtl_scheme" align="right" formatter="numberFormat">Margin</th>
						<th field="spuk_dtl_total" align="right" formatter="numberFormat">Subtotal</th>
					</tr>
				</thead>
			</table>
		</form>
	</div>
	
	<script type="text/javascript">
		// load form
		$(function(){
			$('#fm').form('load','view_form_cancel.php?id=<?= $_REQUEST['id']; ?>');
			$('#fm').form({
				onLoadSuccess: function(data){
					$('#dg_list_unit').datagrid('options').url = 'view_cancel_unit.php?id='+data.cancel_id;
					$('#dg_list_unit').datagrid('reload');
				}
			});
		});
		
		// submit
		function approve(){
			$('#btnSubmit').linkbutton('disable');
			$('#fm').form('submit', {
				url: 'pdo_cancel.php',
				success:function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(result.success);
						window.location.href='index.php';
					}
				}
			});
		}
		function reject(){
			$('#btnReject').linkbutton('disable');
			$('#fm').form('submit', {
				url: 'reject_cancel.php',
				success:function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(result.success);
						window.location.href='index.php';
					}
				}
			});
		}
		
		
		// formatter
		function numberFormat(val,row){
			var rev = parseInt(val, 10).toString().split('').reverse().join('');
			var rev2 = '';
			for(var i = 0; i < rev.length; i++){
				rev2 += rev[i];
				if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
					rev2 += ',';
				}
			}
			return rev2.split('').reverse().join('');
		}
	</script>
</body>
</html>