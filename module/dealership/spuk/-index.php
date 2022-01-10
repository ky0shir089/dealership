<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Create SPUK</title>
	<script type="text/javascript">
		$(function(){
			$('#fm').form('load','form.php');
			$('#fm').form({
				onLoadSuccess: function(data){
					$('#dg').datagrid('options').url = 'load_data.php?id='+data.spuk_id;
					$('#dg').datagrid('reload');
					$('#type').combobox('readonly',true);
					$('#supl_name').combobox('readonly',true);
				}
			});
			$('#dg2').datagrid('enableFilter');
		});
		
		//combobox data
		var cust_type = [{label: 'INDIVIDU',value: 'I'},{label: 'COMPANY',value: 'C'},{label: 'PEDAGANG',value: 'T'}];
		
		//lov
		function lov(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		
		//add lov
		function ambil(){
			$('#fm').form('clear');
			var row = $('#dg2').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.spuk_id);
				$('#spuk_id').textbox('setValue', row.spuk_id);
				$('#spuk_date').textbox('setValue', row.spuk_date);
				$('#supl_type').textbox('setValue', row.supl_type);
				$('#type').textbox('setValue', row.type);
				$('#supl_id').textbox('setValue', row.spuk_cust);
				$('#supl_name').textbox('setValue', row.supl_name);
				$('#spuk_status').textbox('setValue', row.spuk_status);
				if(row.spuk_status == 'APPROVE' || row.spuk_status == 'CANCEL' || row.spuk_status == 'PAID'){
					$('#type').combobox('readonly', true);
					$('#supl_name').combobox('readonly', true);
					$('#btnAdd').linkbutton('disable');
					$('#btnSubmit').linkbutton('disable');
					$('#btnDelete').linkbutton('disable');
				}
				if(row.spuk_status == 'NEW'){
					$('#btnAdd').linkbutton('enable');
					$('#btnSubmit').linkbutton('enable');
					$('#btnDelete').linkbutton('enable');
				}
				$('#dg').datagrid('options').url = 'load_data.php?id='+row.spuk_id;
				$('#dg').datagrid('reload');
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih id terlebih dahulu.')
			}
		}
		
		//add unit
		function add(){
			var hidden = $('#hidden').textbox('getValue');
			if(hidden == ''){
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
							$('#hidden').textbox('setValue',result.spuk_id);
							$('#spuk_id').textbox('setValue',result.spuk_id);
							$('#type').combobox('readonly',true);
							$('#supl_name').combobox('readonly',true);
							$('#id').textbox('setValue',result.spuk_id);
							$('#dlg2').dialog('open').dialog('setTitle','List of Value');
						}
					}
				});
			} else {
				$('#ff').form('clear');
				$('#id').textbox('setValue',hidden);
				$('#dlg2').dialog('open').dialog('setTitle','List of Value');
				$('#utj_nopol').combobox('readonly',false);
				$('#utj_nosin').combobox('readonly',false);
			}
		}
		
		//save unit
		function add2(){
			$('#ff').form('submit', {
				url: 'save2.php',
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
						$('#dlg2').dialog('close');
						$('#dg').datagrid('options').url = 'load_data.php?id='+result.spuk_id;
						$('#dg, #dg2').datagrid('reload');
						$('#utj_nopol').combobox('reload');
						$('#utj_nosin').combobox('reload');
					}
				}
			});
		}
		
		//submit
		function submitForm(){
			$('#fm').form('submit', {
				url: 'save3.php',
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
						$('#spuk_status').textbox('setValue','SAVED');
						$('#type').combobox('readonly',true);
						$('#supl_name').combobox('readonly',true);
						$('#btnAdd').linkbutton('disable');
						$('#btnSubmit').linkbutton('disable');
						$('#btnDelete').linkbutton('disable');
					}
				}
			});
		}
		
		//remove
		function remove(){
			var row = $('#dg').datagrid('getSelected');
			var spuk_id = $('#hidden').textbox('getValue');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to delete this?',function(r){
					if (r){
						$.post('delete.php',{id:row.spuk_dtl_utj},function(result){
							if (result.success){
								alert(result.success);
								$('#dg').datagrid('options').url = 'load_data.php?id='+spuk_id;
								$('#dg').datagrid('reload');
								$('#utj_nopol').combobox('reload');
							} else {
								$.messager.show({	// show error message
									title: 'Error',
									msg: result.errorMsg
								});
							}
						},'json');
					}
				});
			}
		}
		
		// validasi easyui combobox
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
			return val.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
		}
	</script>
</head>
<body>
<div class="easyui-panel" fit="true" border="false">
	<form id="fm" method="post">
		<table width="70%">
			<tr>
				<td>No SPUK</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="hidden" name="spuk_id" class="easyui-textbox" style="width:140px"></div>
					<input id="spuk_id" name="spuk_id" class="easyui-textbox" style="width:140px" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="spuk_date" name="spuk_date" class="easyui-textbox" value="<?= date('Y-m-d'); ?>" style="width:80px" editable="false">
				</td>
			</tr>
			<tr>
				<td>Tipe</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="supl_type" name="supl_type" class="easyui-textbox" style="width:60px" editable="false"></div>
					<input id="type" name="type" class="easyui-combobox" validType="inList['#type']" data-options="
						data:cust_type,
						valueField:'label',
						textField:'label',
						onSelect:function(rec){
							$('#supl_type').textbox('setValue',rec.value);
							var url = 'customer.php?id='+rec.value;
							$('#supl_id').textbox('clear');
							$('#supl_name').combobox('clear');
							$('#supl_name').combobox('reload',url);
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td>Customer</td>
				<td width="1px">:</td>
				<td>
					<input id="supl_id" name="supl_id" class="easyui-textbox" style="width:90px" editable="false">
					<input id="supl_name" name="supl_name" class="easyui-combobox" data-options="
						valueField:'supl_name',
						textField:'supl_name',
						onSelect:function(rec){
							$('#supl_id').textbox('setValue',rec.supl_id);
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td width="1px">:</td>
				<td>
					<input id="spuk_status" name="spuk_status" class="easyui-textbox" value="NEW" style="width:80px" editable="false">
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table id="dg" title="List Unit" class="easyui-datagrid" style="height:300px"
						data-options="
							autoRowHeight:false,
							toolbar:'#toolbar',
							striped:true,
							rownumbers:true,
							singleSelect:true,
							showFooter:true">
						<thead>
							<tr>
								<th field="spuk_dtl_utj">UTJ ID</th>
								<th field="utj_no_contract">No Contract</th>
								<th field="utj_nopol">No Polisi</th>
								<th field="utj_nosin">No Mesin</th>
								<th field="utj_type">Tipe</th>
								<th field="utj_tahun">Tahun</th>
								<th field="spuk_dtl_amount" align="right" formatter="numberFormat">Hutang Konsumen</th>
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						<a href='javascript:void(0)' id="btnAdd" class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="add()">Add</a>
						<a href="javascript:void(0)" id="btnDelete" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="remove()">Delete</a>
						<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="submitForm()">Save</a>
					</div>
				</td>
			</tr>
		</table>
	</form>
	<div id="dlg2" class="easyui-dialog" style="width:370px;height:300px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<form id="ff" method="post">
			<table>
				<tr style="display:none">
					<td>No SPUK</td>
					<td width="1px">:</td>
					<td><input id="id" name="id" class="easyui-textbox" editable="false"></td>
				</tr>
				<tr>
					<td>UTJ ID</td>
					<td width="1px">:</td>
					<td><input id="utj_id" name="utj_id" class="easyui-textbox" editable="false"></td>
				</tr>
				<tr>
					<td>No Contract</td>
					<td width="1px">:</td>
					<td><input id="utj_no_contract" name="utj_no_contract" class="easyui-textbox" editable="false"></td>
				</tr>
				<tr>
					<td>No Polisi</td>
					<td width="1px">:</td>
					<td><input id="utj_nopol" name="utj_nopol" class="easyui-combobox" validType="inList['#utj_nopol']" data-options="
						url:'unit.php',
						valueField:'utj_nopol',
						textField:'utj_nopol',
						onSelect:function(rec){
							$('#utj_id').textbox('setValue',rec.utj_id);
							$('#utj_no_contract').textbox('setValue',rec.utj_no_contract);
							$('#utj_nosin').textbox('setValue',rec.utj_nosin);
							$('#utj_type').textbox('setValue',rec.utj_type);
							$('#utj_tahun').textbox('setValue',rec.utj_tahun);
							$('#utj_nosin').combobox('readonly',true);
						},
						required:true"></td>
				</tr>
				<tr>
					<td>No Mesin</td>
					<td width="1px">:</td>
					<td><input id="utj_nosin" name="utj_nosin" class="easyui-combobox" validType="inList['#utj_nosin']" data-options="
						url:'unit.php',
						valueField:'utj_nosin',
						textField:'utj_nosin',
						onSelect:function(rec){
							$('#utj_id').textbox('setValue',rec.utj_id);
							$('#utj_no_contract').textbox('setValue',rec.utj_no_contract);
							$('#utj_nopol').textbox('setValue',rec.utj_nopol);
							$('#utj_type').textbox('setValue',rec.utj_type);
							$('#utj_tahun').textbox('setValue',rec.utj_tahun);
							$('#utj_nopol').combobox('readonly',true);
						},
						required:true"></td>
				</tr>
				<tr>
					<td>Tipe</td>
					<td width="1px">:</td>
					<td><input id="utj_type" name="utj_type" class="easyui-textbox" editable="false"></td>
				</tr>
				<tr>
					<td>Tahun</td>
					<td width="1px">:</td>
					<td><input id="utj_tahun" name="utj_tahun" class="easyui-textbox" editable="false"></td>
				</tr>
				<tr>
					<td>Hutang Konsumen</td>
					<td width="1px">:</td>
					<td><input id="spuk_dtl_amount" name="spuk_dtl_amount" class="easyui-numberbox" data-options="groupSeparator:',',required:true"></td>
				</tr>
			</table>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="add2()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg2').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<!-- LOV -->
		<div id="dlg" class="easyui-dialog" style="width:580px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg2" class="easyui-datagrid" style="height:330px" 
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
						<th field="supl_name" width="200px">Nama Customer</th>
						<th field="spuk_status" width="70px">Status</th>
					</tr>
				</thead>
			</table>
			<div id="toolbar2">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
			</div>
		</div>	
</div>
</body>
</html>