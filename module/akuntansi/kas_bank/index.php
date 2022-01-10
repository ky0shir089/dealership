<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Kas dan Bank</title>
	<script type="text/javascript">
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
		
		//save
		function submitForm(){
			var hidden = $('#hidden').textbox('getValue');
			if(hidden == ""){
				var url = 'save.php'
			} else {
				var url = 'update.php'
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
						$('#dg').datagrid('reload');
						$('#hidden').textbox('setValue', result.rekout_no);
					}
				}
			});
		}
		
		//reset
		function reset(){
			location.reload();
		}
		
		//lov
		function lov(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		
		//add lov
		function ambil(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.rekout_no);
				$('#rekout_no').textbox('setValue', row.rekout_no);
				$('#rekout_id').textbox('setValue', row.rekout_id);
				$('#bank_name').textbox('setValue', row.bank_name);
				$('#rekout_name').textbox("setValue", row.rekout_name);
				$('#rekout_outlet').textbox('setValue', row.rekout_outlet);
				$('#nama_titik').textbox('setValue', row.nama_titik);
				$('#rekout_segment').textbox('setValue',row.rekout_segment);
				$('#status').css("display", "table-row");
				$('#user_ipaddress').textbox('setValue',row.user_ipaddress);
				$('#user_agent').textbox('setValue',row.user_agent);
				if(row.rekout_status == 'Y'){
					$('#rekout_status').switchbutton({checked: true});
				}
				$('#rekout_no').textbox('readonly',true);
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih no rekening terlebih dahulu.')
			}
		}
	</script>
</head>
<body>
	<div id="tt" class="easyui-tabs" plain="true">
		<div title="Bank" style="padding:10px 40px 20px 40px">
			<form id="fm" method="post">
				<table>
					<tr>
						<td>No Rekening</td>
						<td width="1px">:</td>
						<td>
							<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
							<input id="rekout_no" name="rekout_no" class="easyui-textbox" data-options="required:true">
							<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
						</td>
					</tr>
					<tr>
						<td>Bank</td>
						<td width="1px">:</td>
						<td>
							<input id="rekout_id" name="rekout_id" class="easyui-textbox" style="width:40px" editable="false">
							<input id="bank_name" name="bank_name" class="easyui-combobox" data-options="
								url:'bank.php',
								valueField:'bank_name',
								textField:'bank_name',
								onSelect:function(rec){
									$('#rekout_id').textbox('setValue',rec.bank_id)
								},
								required:true">
						</td>
					</tr>
					<tr>
						<td>Nama Rekening</td>
						<td width="1px">:</td>
						<td>
							<input id="rekout_name" name="rekout_name" class="easyui-textbox" data-options="required:true">
						</td>
					</tr>
					<tr>
						<td>Outlet</td>
						<td width="1px">:</td>
						<td>
							<input id="rekout_outlet" name="rekout_outlet" class="easyui-textbox" style="width:60px" editable="false">
							<input id="nama_titik" name="nama_titik" class="easyui-combobox" style="width:250px" validType="inList['#nama_titik']" data-options="
								url:'outlet.php',
								valueField:'nama_titik',
								textField:'nama_titik',
								onSelect:function(rec){
									$('#rekout_outlet').textbox('setValue',rec.kode_titik)
								},
								required:true">
						</td>
					</tr>
					<tr>
						<td>Segmen</td>
						<td width="1px">:</td>
						<td>
							<input id="rekout_segment" name="rekout_segment" class="easyui-numberbox" data-options="required:true">
						</td>
					</tr>
					<tr id="status" style="display:none">
						<td>Status</td>
						<td width="1px">:</td>
						<td>
							<input id="rekout_status" name="rekout_status" class="easyui-switchbutton" style="width:90px" data-options="onText:'ACTIVE',offText:'INACTIVE',value:'Y'">
						</td>
					</tr>
					<tr>
						<td colspan="3" align="right">
							<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-save" onclick="submitForm()">Submit</a>
							<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-reload" onclick="reset()">Reset</a>
						</td>
					</tr>
				</table>
				<!-- LOV -->
				<div id="dlg" class="easyui-dialog" style="width:720px;height:400px;padding:10px 20px"
					closed="true" modal="true">
					<table id="dg" class="easyui-datagrid" style="height:330px" 
						data-options="
							singleSelect:true,
							url:'outlet_account.php',
							striped:true,
							autoRowHeight:false,
							toolbar:'#toolbar',
							view:bufferview">
						<thead>
							<tr>
								<th field="rekout_no" width="160px">No Rekening</th>
								<th field="bank_name" width="90px">Bank</th>
								<th field="rekout_name" width="160px">Nama Rekening</th>
								<th field="nama_titik" width="200px">Outlet</th>
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
					</div>
				</div>
			</form>
		</div>
		<div title="Kas" style="padding:10px 40px 20px 40px">
		
		</div>
	</div>
</body>
</html>