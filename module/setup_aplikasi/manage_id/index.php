<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Manage ID</title>
	<script type="text/javascript">
		$(function(){
			$('#dg, #dg2, #dg3').datagrid('enableFilter');
		});
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
						$('#dg2').datagrid('options').url = 'load_role.php?id='+row.user_id;
						$('#dg2').datagrid('reload');
					}
				}
			});
		}
		function reset(){
			location.reload();
		}
		//lov
		function cari(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		//add lov
		function ambil(){
			$('#fm').form('clear');
			var row = $('#dg2').datagrid('getSelected');
			if (row){
				$('#user_name').textbox('enable');
				$('#user_description').textbox('enable');
				$('#chpass').switchbutton('enable');
				$('#status').switchbutton('enable');
				$('#hidden').textbox('setValue', row.user_id);
				$('#user_id').textbox('setValue', row.user_id);
				$('#user_name').textbox('setValue', row.user_name);
				$('#user_description').textbox('setValue', row.user_description);
				if(row.user_enable_sts == 'Y'){
					$('#status').switchbutton({checked: true});
				}
				$('#dg').datagrid('options').url = 'access.php?id='+row.user_id;
				$('#dg').datagrid('reload');
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih id terlebih dahulu.')
			}
		}
		//access
		function add(){
			var uid = $('#user_id').textbox('getValue');
			if(uid == ""){
				alert('Silahkan pilih user id terlebih dahulu');
			} else {
				$('#dg3').datagrid('options').url = 'outlet.php?id='+uid;
				$('#dg3').datagrid('reload');
				$('#dlg2').dialog('open').dialog('setTitle','List of Value');
			}
		}
		//add access
		function save(){
			var uid = $('#user_id').textbox('getValue');
			$('#ff').form('submit', {
				url:'save_access.php?id='+uid,
				onSubmit: function(param){
					// param disini sebagai tambahan parameter yang dikirim ke proses php
					// param.data bisa diganti dengan nama variable yang lain
					param.data = JSON.stringify($('#dg3').datagrid('getSelections'));
				},
				success:function(data){
					$('#ff').form('clear');
					$('#dg3').datagrid('clearSelections');
					alert(data);
					$('#dlg2').dialog('close');
					$('#dg3').datagrid('reload');
					$('#dg').datagrid('reload');
				}
			});
		}
		//remove role
		function remove(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to delete this outlet?',function(r){
					if (r){
						$.post('delete.php',{id:row.access_no},function(result){
							if (result.success){
								alert(result.success);
								$('#dg').datagrid('reload');	// reload the user data
								$('#dg3').datagrid('reload');
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
	</script>
</head>
<body>
<div class="easyui-panel" fit="true" border="false">
	<form id="fm" method="post">
		<table>
			<tr>
				<td>User ID</td>
				<td width="1px">:</td>
				<td width="300px">
					<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
					<input id="user_id" name="user_id" class="easyui-textbox" style="width:60px" data-options="validType:'length[4,6]',required:true" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="cari()"></a>
				</td>
			</tr>
			<tr>
				<td>User Name</td>
				<td width="1px">:</td>
				<td>
					<input id="user_name" name="user_name" class="easyui-textbox" data-options="required:true" disabled>
				</td>
			</tr>
			<tr>
				<td>User Description</td>
				<td width="1px">:</td>
				<td>
					<input id="user_description" name="user_description" class="easyui-textbox" disabled>
				</td>
			</tr>
			<tr>
				<td>Reset</td>
				<td width="1px">:</td>
				<td>
					<input id="chpass" name="chpass" class="easyui-switchbutton" data-options="onText:'YES',offText:'NO',value:'Y'" disabled>
				</td>
			</tr>
			<tr>
				<td>User Status</td>
				<td width="1px">:</td>
				<td>
					<input id="status" name="user_enable_sts" class="easyui-switchbutton" style="width:90px" data-options="onText:'ACTIVE',offText:'INACTIVE',value:'Y'" disabled>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table id="dg" title="Network Access" class="easyui-datagrid" style="width:100%;height:350px"
						data-options="
							autoRowHeight:false,
							toolbar:'#toolbar',
							striped:true,
							rownumbers:true,
							singleSelect:true">
						<thead>
							<tr>
								<!--<th field="ck" checkbox="true"></th>-->
								<th field="cabang_bmr" width="100px">Cabang</th>
								<th field="access_outlet">Outlet ID</th>
								<th field="nama_titik">Outlet Name</th>
								<!--<th field="access_status">Status</th>-->
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="add()">Add</a>
						<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-remove' plain='true' onclick="remove()">Delete</a>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submitForm()" style="width:90px">Save</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-reload" onclick="reset()" style="width:90px">Reset</a>
				</td>
			</tr>
		</table>
		<!-- LOV -->
		<div id="dlg" class="easyui-dialog" style="width:520px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg2" class="easyui-datagrid" style="height:330px" 
				data-options="
					singleSelect:true,
					url:'user.php',
					striped:true,
					autoRowHeight:false,
					toolbar:'#toolbar2',
					view:bufferview">
				<thead>
					<tr>
						<th field="user_id" width="60px">User ID</th>
						<th field="user_name" width="200px">User Name</th>
						<th field="user_description" width="200px">User Description</th>
					</tr>
				</thead>
			</table>
			<div id="toolbar2">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
			</div>
		</div>		
	</form>
	<!-- OUTLET -->
	<form id="ff" method="post">
		<div id="dlg2" class="easyui-dialog" style="width:500px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg3" class="easyui-datagrid" style="height:330px" 
				data-options="
					striped:true,
					autoRowHeight:false,
					toolbar:'#toolbar3',
					view:bufferview">
				<thead>
					<tr>
						<th field="ck" checkbox="true"></th>
						<th field="cabang_bmr" width="100px">Cabang</th>
						<th field="kode_titik">Outlet ID</th>
						<th field="nama_titik" width="250px">Outlet Name</th>
				</tr>
				</thead>
			</table>
			<div id="toolbar3">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-save' plain='true' onclick="save()">Save</a>
			</div>
		</div>
	</form>
</div>
</body>
</html>