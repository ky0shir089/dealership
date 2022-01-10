<!DOCTYPE html>
<html>
<head>
	<title>Cancel Unit</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg_spuk, #dg_cancel');
			dg.datagrid();
			dg.datagrid('enableFilter');
		});
		
		// lov
		function lov_spuk(){
			$('#dlg_spuk').dialog('open').dialog('setTitle','List of Value');
		}
		function lov_cancel(){
			$('#dlg_cancel').dialog('open').dialog('setTitle','List of Value');
		}
		
		// load lov
		function ambil(){
			//$('#fm').form('clear');
			var row = $('#dg_spuk').datagrid('getSelected');
			//$('#cancel_date').textbox('setValue','<?= date('Y-m-d'); ?>');
			//$('#cancel_status').textbox('setValue','NEW');
			$('#cancel_spuk_id').textbox('setValue',row.spuk_id);
			$('#spuk_outlet').textbox('setValue',row.spuk_outlet);
			$('#dg_list_unit').datagrid('options').url = 'list_unit.php?id='+row.spuk_id;
			$('#dg_list_unit').datagrid('reload');
			$('#dlg_spuk').dialog('close');
		}
		function ambil2(){
			$('#fm').form('clear');
			var row = $('#dg_cancel').datagrid('getSelected');
			$('#cancel_id').textbox('setValue',row.cancel_id);
			$('#cancel_date').textbox('setValue',row.cancel_date);
			$('#cancel_spuk_id').textbox('setValue',row.cancel_spuk_id);
			$('#cancel_reason').textbox('setValue',row.cancel_reason);
			$('#spuk_outlet').textbox('setValue',row.spuk_outlet);
			$('#cancel_status').textbox('setValue',row.cancel_status);
			$('#lov_spuk').linkbutton('disable');
			$('#btnDelete').linkbutton('disable');
			$('#dg_list_unit').datagrid('options').url = 'list_cancel.php?id='+row.cancel_id;
			$('#dg_list_unit').datagrid('reload');
			$('#dlg_cancel').dialog('close');
		}
		
		// remove
		function remove(){
			var row = $('#dg_list_unit').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to delete this?',function(r){
					if (r){
						$('#fm').form('submit', {
							url: 'request_cancel.php',
							onSubmit: function(param){
								param.data = JSON.stringify($('#dg_list_unit').datagrid('getSelections'));
							},
							success:function(result){
								var result = eval('('+result+')');
								if (result.errorMsg){
									$.messager.show({
										title: 'Error',
										msg: result.errorMsg
									});
								} else {
									alert(result.success);
									$('#cancel_id').textbox('setValue',result.cancel_id);
									$('#dg_list_unit').datagrid('options').url = 'list_cancel.php?id='+result.cancel_id;
									$('#dg_list_unit').datagrid('reload');
									$('#cancel_status').textbox('setValue','REQUEST');
									$('#lov_spuk').linkbutton('disable');
									$('#btnDelete').linkbutton('disable');
								}
							}
						});
					}
				});
			}
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
</head>
<body>
<div class="easyui-panel" fit="true" border="false">
	<form id="fm" method="post">
		<table>
			<tr>
				<td>No Cancel</td>
				<td width="1px">:</td>
				<td>
					<input id="cancel_id" name="cancel_id" class="easyui-textbox" style="width:140px" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov_cancel()"></a>
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="cancel_date" name="cancel_date" class="easyui-textbox" value="<?= date('Y-m-d'); ?>" style="width:80px" editable="false">
				</td>
			</tr>
			<tr>
				<td>No SPUK</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="spuk_outlet" name="spuk_outlet" class="easyui-textbox" style="width:60px"></div>
					<input id="cancel_spuk_id" name="cancel_spuk_id" class="easyui-textbox" style="width:140px" editable="false" required="true">
					<a href="javascript:void(0)" id="lov_spuk" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov_spuk()"></a>
				</td>
			</tr>
			<tr>
				<td>Reason</td>
				<td width="1px">:</td>
				<td>
					<input id="cancel_reason" name="cancel_reason" class="easyui-textbox" style="width:300px" required="true">
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td width="1px">:</td>
				<td>
					<input id="cancel_status" name="cancel_status" class="easyui-textbox" value="NEW" style="width:80px" editable="false">
				</td>
			</tr>
		</table>
		<table id="dg_list_unit" title="List Unit" class="easyui-datagrid" style="height:350px"
			data-options="
				autoRowHeight:false,
				toolbar:'#toolbar',
				striped:true,
				rownumbers:true,
				showFooter:true">
			<thead>
				<tr>
					<th field="ck" checkbox="true"></th>
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
		<div id="toolbar">
			<a href="javascript:void(0)" id="btnDelete" class="easyui-linkbutton" data-options="iconCls:'icon-cancel',plain:true" onclick="remove()">Delete</a>
		</div>
	</form>
	<!-- LOV SPUK-->
	<div id="dlg_spuk" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_spuk" class="easyui-datagrid" style="height:330px" 
			data-options="
				singleSelect:true,
				url:'spuk.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar2',
				view:bufferview">
			<thead>
				<tr>
					<th field="spuk_id" width="160px">SPUK ID</th>
					<th field="spuk_date" width="90px">Tanggal</th>
					<th field="supl_name" width="200px">Nama Buyer</th>
					<th field="spuk_status" width="80px">Status</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar2">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
		</div>
	</div>
	<!-- LOV CANCEL-->
	<div id="dlg_cancel" class="easyui-dialog" style="width:760px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_cancel" class="easyui-datagrid" style="height:330px" 
			data-options="
				singleSelect:true,
				url:'lov_cancel.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar3'">
			<thead>
				<tr>
					<th field="cancel_id" width="160px">Cancel ID</th>
					<th field="cancel_date" width="90px">Tanggal</th>
					<th field="cancel_spuk_id" width="160px">SPUK ID</th>
					<th field="supl_name" width="200px">Nama Buyer</th>
					<th field="cancel_status" width="80px">Status</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar3">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil2()">Ambil</a>
		</div>
	</div>
</div>	
</body>
</html>