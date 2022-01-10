<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Klasifikasi RV</title>
</head>
<body>
	<form id="fm" method="post">
		<table>
			<tr>
				<td>No RV</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_no" name="rv_no" class="easyui-textbox" editable="false">
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_received_date" name="rv_received_date" class="easyui-textbox" style="width:80px" value="<?= date('Y-m-d'); ?>" editable="false">
				</td>
			</tr>
			<tr>
				<td>Type Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_mst_code" name="rv_mst_code" class="easyui-textbox" style="width:60px" editable="false">
					<input id="type_trx" name="type_trx" class="easyui-textbox" editable="false">
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_received_from" name="rv_received_from" class="easyui-textbox" style="width:300px" editable="false">
				</td>
			</tr>
			<tr>
				<td>Kode Bank</td>
				<td width="1px">:</td>
				<td>
					<input id="bank_name" name="bank_name" class="easyui-textbox" style="width:60px" editable="false">
					<input id="rekout_name" name="rekout_name" class="easyui-textbox" editable="false">
					<input id="rv_bank_rek" name="rv_bank_rek" class="easyui-textbox" editable="false">
				</td>
			</tr>
			<tr>
				<td>Code Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_segment2" name="rv_segment2" class="easyui-textbox" style="width:60px" editable="false">
					<input id="coa_description" name="coa_description" class="easyui-textbox" editable="false">
				</td>
			</tr>
			<tr>
				<td>Amount</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_amount" name="rv_amount" class="easyui-numberbox" data-options="groupSeparator:','" editable="false">
				</td>
			</tr>
			<tr>
				<td>Outlet</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_classification" name="rv_classification" class="easyui-textbox" style="width:60px" editable="false">
					<input id="nama_titik" name="nama_titik" class="easyui-combobox" style="width:250px" validType="inList['#nama_titik']" data-options="
						url:'outlet.php',
						valueField:'nama_titik',
						textField:'nama_titik',
						onSelect:function(rec){
							$('#rv_classification').textbox('setValue',rec.kode_titik)
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()">Submit</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="reset()">Reset</a>
				</td>
			</tr>
		</table>
		<!-- LOV -->
		<div id="dlg" class="easyui-dialog" style="width:720px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg" class="easyui-datagrid" style="height:340px" 
				data-options="
					singleSelect:true,
					striped:true,
					autoRowHeight:false,
					toolbar:'#toolbar',
					view:bufferview,
					remoteFilter:true,
					pageSize:50">
				<thead>
					<tr>
						<th field="rv_no" width="150px">No RV</th>
						<th field="rv_received_from" width="400px">Keterangan</th>
						<th field="rv_amount" width="90px" align="right" formatter="numberFormat">Amount</th>
					</tr>
				</thead>
			</table>
			<div id="toolbar">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
			</div>
		</div>
	</form>
	
	<script type="text/javascript">
		$(function(){
			$('#dg').datagrid('enableFilter');
		});
		
		//combobox data
		var type_trx = [{label: 'LAIN-LAIN',value: 'TRX01'}];
		var code_trx = [{label: 'TITIPAN LAIN-LAIN',value: '3310201'}];
		
		//save
		function submitForm(){
			$('#fm').form('submit', {
				url: 'save.php',
				onSubmit: function(param){
					return $(this).form('validate');
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
						$('#btnSubmit').linkbutton('disable');
						$('#dg').datagrid('reload');
					}
				}
			});
		}
		
		function reset(){
			location.reload()
		}
		
		//lov
		function lov(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
			$('#dg').datagrid('load','pdo_lov.php');
		}
		
		//add lov
		function ambil(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#rv_no').textbox('setValue', row.rv_no);
				$('#rv_received_date').textbox('setValue', row.rv_received_date);
				$('#rv_mst_code').textbox('setValue', row.rv_mst_code);
				$('#type_trx').textbox('setValue', row.type_trx);
				$('#rv_received_from').textbox('setValue', row.rv_received_from);
				$('#bank_name').textbox('setValue',row.bank_name);
				$('#rekout_name').textbox('setValue',row.rekout_name);
				$('#rv_bank_rek').textbox('setValue', row.rv_bank_rek);
				$('#rv_segment2').textbox('setValue', row.rv_segment2);
				$('#coa_description').textbox('setValue', row.coa_description);
				$('#rv_amount').numberbox('setValue', row.rv_amount);
				$('#rv_classification').textbox('setValue', row.rv_classification);
				$('#nama_titik').combobox('setValue', row.nama_titik);
				$('#btnSubmit').linkbutton('enable');
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih no RV terlebih dahulu.')
			}
		}
		
		//validasi easyui combobox
		$.extend($.fn.validatebox.defaults.rules,{
		    inList:{
				validator:function(value,param){
					var c = $(param[0]);
					var opts = c.combobox('options');
					var data = c.combobox('getData');
					var exists = false;
					for(var i=0; i<data.length; i++){
						if (value == data[i][opts.textField]){
							exists = true;
							break;
						}
					}
						return exists;
				},
				message:'invalid value.'
		    }
		});
		
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