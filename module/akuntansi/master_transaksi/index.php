<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Master Transaksi</title>
	<script type="text/javascript">
		// enableFilter
		$(function(){
			$('#dg_penerimaan, #dg_coa').datagrid('enableFilter');
		})
			
		// combobox data
		var stats = [{label: 'ACTIVE',value: 'Y'},{label: 'INACTIVE',value: 'N'}];

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
		
		//reset
		function reset(){
			location.reload();
		}
		
		//save
		function submitPenerimaan(){
			var rvmst_code = $('#rvmst_code').textbox('getValue');
			if(rvmst_code == ""){
				var url = 'save_penerimaan_hdr.php'
			} else {
				var url = 'update_penerimaan_hdr.php'
			}
			$('#fm').form('submit', {
				url: url,
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
						$('#rvmst_desc').combobox('reload');
						$('#rvmst_code').textbox('setValue',result.rvmst_code);
					}
				}
			});
		}
		
		//lov
		function lov1(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
			$('#lov1').css('display',"inline");
			$('#lov2').css('display',"none");
			$('#lov3').css('display',"none");
		}
		function lov2(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
			$('#lov1').css('display',"none");
			$('#lov2').css('display',"inline");
			$('#lov3').css('display',"none");
		}
		function lov3(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
			$('#lov1').css('display',"none");
			$('#lov2').css('display',"none");
			$('#lov3').css('display',"inline");
		}
		
		//add lov
		function ambilPenerimaan(){
			var rvmst_code = $('#rvmst_code').textbox('getValue');
			$('#fm').form('submit', {
				url: 'save_penerimaan_dtl.php',
				onSubmit: function(param){
					param.data = JSON.stringify($('#dg_coa').datagrid('getSelections'));
				},
				success:function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} 
					if (result.found){
						alert(result.found);
					} else {
						alert(result.success);
						$('#dg_penerimaan').datagrid('options').url = 'invdtl_code.php?id='+rvmst_code;
						$('#dg_penerimaan').datagrid('reload');
						$('#dlg').dialog('close');
					}
				}
			});
		}
		
		//save
		function submitInvoice(){
			var invmst_code = $('#invmst_code').textbox('getValue');
			if(invmst_code == ""){
				var url = 'save_invoice_hdr.php'
			} else {
				var url = 'update_invoice_hdr.php'
			}
			$('#ff').form('submit', {
				url: url,
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
						$('#invmst_desc').combobox('reload');
						$('#invmst_code').textbox('setValue',result.invmst_code);
					}
				}
			});
		}
		
		//add lov
		function ambilInvoice(){
			var invmst_code = $('#invmst_code').textbox('getValue');
			$('#ff').form('submit', {
				url: 'save_invoice_dtl.php',
				onSubmit: function(param){
					param.data = JSON.stringify($('#dg_coa').datagrid('getSelections'));
				},
				success:function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} 
					if (result.found){
						alert(result.found);
					} else {
						alert(result.success);
						$('#dg_invoice').datagrid('options').url = 'invdtl_code.php?id='+invmst_code;
						$('#dg_invoice').datagrid('reload');
						$('#dlg').dialog('close');
					}
				}
			});
		}
		
		//add lov
		function ambilKoreksi(){
			$('#ff').form('submit', {
				url: 'save_correct.php',
				onSubmit: function(param){
					param.data = JSON.stringify($('#dg_coa').datagrid('getSelections'));
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
						$('#dg_correct').datagrid('reload');
						$('#dlg').dialog('close');
					}
				}
			});
		}
	</script>
</head>
<body>
	<div id="tt" class="easyui-tabs" plain="true">
		<div title="Penerimaan" style="padding:10px 40px 20px 40px">
			<form id="fm" method="post">
				<table width="30%">
					<tr>
						<td>Type TRX</td>
						<td width="1px">:</td>
						<td>
							<input id="rvmst_code" name="rvmst_code" class="easyui-textbox" style="width:60px" editable="false">
							<input id="rvmst_desc" name="rvmst_desc" class="easyui-combobox" data-options="
								url:'rvmst_code.php',
								valueField:'rvmst_desc',
								textField:'rvmst_desc',
								onSelect:function(rec){
									$('#rvmst_status').textbox('clear');
									$('#rvmst_code').textbox('setValue',rec.rvmst_code);
									$('#rvmst_status').textbox('setValue',rec.rvmst_status);
									$('#dg_penerimaan').datagrid('options').url = 'rvdtl_code.php?id='+rec.rvmst_code;
									$('#dg_penerimaan').datagrid('reload');
								},
								required:true">
						</td>
					</tr>
					<tr>
						<td>Status</td>
						<td width="1px">:</td>
						<td>
							<input id="rvmst_status" name="rvmst_status" class="easyui-combobox" style="width:90px" data-options="
								data:stats,
								valueField:'value',
								textField:'label',
								required:true">
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<table id="dg_penerimaan" class="easyui-datagrid" style="height:330px" 
								data-options="
									singleSelect:true,
									striped:true,
									autoRowHeight:false,
									toolbar:'#toolbar'">
								<thead>
									<tr>
										<th field="rvdtl_code">Code Trx</th>
										<th field="coa_description">Keterangan</th>
										<th field="rvdtl_status">Status</th>
									</tr>
								</thead>
							</table>
							<div id="toolbar">
								<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="lov1()">Add</a>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-save" onclick="submitPenerimaan()">Submit</a>
							<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-reload" onclick="reset()">Reset</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
		
		<div title="Invoice" style="padding:10px 40px 20px 40px">
			<form id="ff" method="post">
				<table width="40%">
					<tr>
						<td>Type TRX</td>
						<td width="1px">:</td>
						<td>
							<input id="invmst_code" name="invmst_code" class="easyui-textbox" style="width:60px" editable="false">
							<input id="invmst_desc" name="invmst_desc" class="easyui-combobox" style="width:200px" data-options="
								url:'invmst_code.php',
								valueField:'invmst_code',
								textField:'invmst_desc',
								onSelect:function(rec){
									$('#invmst_status').textbox('clear');
									$('#invmst_code').textbox('setValue',rec.invmst_code);
									$('#invmst_status').textbox('setValue',rec.invmst_status);
									$('#dg_invoice').datagrid('options').url = 'invdtl_code.php?id='+rec.invmst_code;
									$('#dg_invoice').datagrid('reload');
								},
								required:true">
						</td>
					</tr>
					<tr>
						<td>Status</td>
						<td width="1px">:</td>
						<td>
							<input id="invmst_status" name="invmst_status" class="easyui-combobox" style="width:90px" data-options="
								data:stats,
								valueField:'value',
								textField:'label',
								required:true">
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<table id="dg_invoice" class="easyui-datagrid" style="height:330px" 
								data-options="
									singleSelect:true,
									striped:true,
									autoRowHeight:false,
									toolbar:'#toolbar3'">
								<thead>
									<tr>
										<th field="invdtl_code">Code Trx</th>
										<th field="coa_description">Keterangan</th>
										<th field="invdtl_status">Status</th>
									</tr>
								</thead>
							</table>
							<div id="toolbar3">
								<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="lov2()">Add</a>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-save" onclick="submitInvoice()">Submit</a>
							<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-reload" onclick="reset()">Reset</a>
						</td>
					</tr>
				</table>
			</form>
		</div>
		
		<div title="Koreksi" style="padding:10px 40px 20px 40px">
			<table id="dg_correct" class="easyui-datagrid" style="height:330px" 
				data-options="
					url:'mst_correct.php',
					singleSelect:true,
					striped:true,
					autoRowHeight:false,
					toolbar:'#toolbar4'">
				<thead>
					<tr>
						<th field="correct_code">Code Trx</th>
						<th field="coa_description">Keterangan</th>
						<th field="correct_status">Status</th>
					</tr>
				</thead>
			</table>
			<div id="toolbar4">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="lov3()">Add</a>
			</div>
			</form>
		</div>
	</div>
	<!-- LOV -->
	<div id="dlg" class="easyui-dialog" style="width:470px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_coa" class="easyui-datagrid" style="height:330px" 
			data-options="
				singleSelect:true,
				url:'coa_code.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar2'">
			<thead>
				<tr>
					<th field="coa_code">Code Trx</th>
					<th field="coa_description">Keterangan</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar2">
			<div id="lov1" style="display:none"><a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-save' plain='true' onclick="ambilPenerimaan()">Save</a></div>
			<div id="lov2" style="display:none"><a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-save' plain='true' onclick="ambilInvoice()">Save</a></div>
			<div id="lov3" style="display:none"><a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-save' plain='true' onclick="ambilKoreksi()">Save</a></div>
		</div>
	</div>
</body>
</html>