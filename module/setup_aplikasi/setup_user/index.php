<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Setup User</title>
	<script type="text/javascript">
		$(function(){
			$('#dg2').datagrid('enableFilter');
		});
		//save
		function submitForm(){
			var uid = $('#hidden').textbox('getValue');
			if(uid == ""){
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
						$('#dg2').datagrid('reload');
						$('#hidden').textbox('setValue',result.uid);
						$('#dg').datagrid({
							url: 'load_role.php?id='+result.uid
						});
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
			var row = $('#dg2').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.user_id);
				$('#user_id').textbox('setValue', row.user_id);
				$('#user_name').textbox('setValue', row.user_name);
				$('#password').css("display", "table-row");
				$('#user_password').textbox("setValue", row.user_password);
				$('#user_description').textbox('setValue', row.user_description);
				$('#lastlogin').textbox('setValue',row.user_lastlogin);
				$('#last_password').textbox('setValue',row.user_lastpassword);
				$('#user_ipaddress').textbox('setValue',row.user_ipaddress);
				$('#user_agent').textbox('setValue',row.user_agent);
				if(row.user_enable_sts == 'Y'){
					$('#status').switchbutton({checked: true});
				}
				$('#person_id').textbox('setValue', row.user_personid);
				$('#person_name').textbox('setValue', row.person_name);
				$('#user_outlet').textbox('setValue', row.user_outlet);
				$('#nama_titik').textbox('setValue', row.nama_titik);
				$('#uid').textbox('setValue', row.user_id);
				$('#user_id').textbox('disable');
				$('#person_name').textbox('disable');
				$('#dg').datagrid({
					url: 'load_role.php?id='+row.user_id
				});
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih id terlebih dahulu.')
			}
		}
		//role
		function add(){
			var uid = $('#user_id').textbox('getValue');
			if(uid == ""){
				alert('Silahkan pilih user id terlebih dahulu');
			} else {
				$('#dlg2').dialog('open').dialog('setTitle','List of Value');
			}
		}
		//add role
		function save(){
			var uid = $('#user_id').textbox('getValue');
			$('#ff').form('submit', {
				url:'save_role.php?id='+uid,
				onSubmit: function(param){
					// param disini sebagai tambahan parameter yang dikirim ke proses php
					// param.data bisa diganti dengan nama variable yang lain
					param.data = JSON.stringify($('#dg3').datagrid('getSelections'));
				},
				success:function(data){
					$('#ff').form('reset');
					$('#dg3').datagrid('clearSelections');
					alert(data);
					$('#dlg2').dialog('close');
					$('#dg').datagrid('reload');
				}
			});
		}
		//remove role
		function remove(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to delete this role?',function(r){
					if (r){
						$.post('delete.php',{id:row.user_role_id},function(result){
							if (result.success){
								alert(result.success);
								$('#dg').datagrid('reload');	// reload the user data
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
		<table border="0" width="100%">
			<tr>
				<td width="55%">
					<table>
						<tr>
							<td>User ID</td>
							<td width="1px">:</td>
							<td>
								<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
								<input id="user_id" name="user_id" class="easyui-textbox" style="width:60px" data-options="validType:'length[4,6]',required:true">
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="cari()"></a>
							</td>
						</tr>
						<tr>
							<td>User Name</td>
							<td width="1px">:</td>
							<td>
								<input id="user_name" name="user_name" class="easyui-textbox" style="width:200px" data-options="required:true">
							</td>
						</tr>
						<tr id="password" style="display:none">
							<td>Chpass</td>
							<td width="1px">:</td>
							<td>
								<input name="chpass" class="easyui-switchbutton" data-options="onText:'YES',offText:'NO',value:'Y'">
								<input id="user_password" name="user_password" class="easyui-textbox" type="password">
							</td>
						</tr>
						<tr>
							<td>User Description</td>
							<td width="1px">:</td>
							<td>
								<input id="user_description" name="user_description" class="easyui-textbox">
							</td>
						</tr>
						<tr>
							<td>IP Address</td>
							<td width="1px">:</td>
							<td>
								<input id="user_ipaddress" name="user_ipaddress" class="easyui-textbox" disabled>
							</td>
						</tr>
						<tr>
							<td>User Agent</td>
							<td width="1px">:</td>
							<td>
								<input id="user_agent" name="user_agent" class="easyui-textbox" style="width:450px" disabled>
							</td>
						</tr>
						<tr>
							<td>Status</td>
							<td width="1px">:</td>
							<td>
								<input id="status" name="user_enable_sts" class="easyui-switchbutton" style="width:90px" data-options="onText:'ACTIVE',offText:'INACTIVE',value:'Y'">
							</td>
						</tr>
					</table>
				</td>
				<td width="45%" valign="top">
					<table>
						<tr>
							<td>Person ID</td>
							<td width="1px">:</td>
							<td>
								<input id="person_id" name="user_personid" class="easyui-textbox" style="width:60px" editable="false">
								<input id="person_name" name="person_name" class="easyui-combobox" style="width:250px" validType="inList['#person_name']" data-options="
									url:'personid.php',
									valueField:'person_name',
									textField:'person_name',
									onSelect:function(rec){
										$('#person_id').textbox('setValue', rec.person_id);
										$('#user_id').textbox('setValue', rec.person_id);
										$('#user_name').textbox('setValue', rec.person_name);
										$('#user_outlet').textbox('setValue', rec.person_outlet);
										$('#nama_titik').textbox('setValue', rec.nama_titik);
									},
									required:true">
							</td>
						</tr>
						<tr>
							<td>Outlet</td>
							<td width="1px">:</td>
							<td>
								<input id="user_outlet" name="user_outlet" class="easyui-textbox" style="width:60px" editable="false">
								<input id="nama_titik" name="nama_titik" class="easyui-combobox" style="width:250px" validType="inList['#nama_titik']" data-options="
									url:'outlet.php',
									valueField:'nama_titik',
									textField:'nama_titik',
									onSelect:function(rec){
										$('#user_outlet').textbox('setValue',rec.kode_titik)
									},
									required:false">
							</td>
						</tr>
						<tr>
							<td>Last Login</td>
							<td width="1px">:</td>
							<td>
								<input id="lastlogin" name="user_lastlogin" class="easyui-textbox" disabled>
							</td>
						</tr>
						<tr>
							<td>Last Change Password</td>
							<td width="1px">:</td>
							<td>
								<input id="last_password" name="user_lastpassword" class="easyui-textbox" disabled>
							</td>
						</tr>
						<!--
						<tr>
							<td>Role</td>
							<td width="1px">:</td>
							<td>
								<input id="role_id" name="role_id" class="easyui-textbox" style="width:60px" editable="false">
								<input id="role_name" name="role_name" class="easyui-combobox" style="width:200px" data-options="
									url:'role.php',
									valueField:'role_name',
									textField:'role_name',
									onSelect:function(rec){
										$('#role_id').textbox('setValue', rec.role_id);
									},
									required:false">
							</td>
						</tr>
						-->
						<tr>
							<td colspan="3" align="center">
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()">Submit</a>
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="reset()">Reset</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table id="dg" title="List Role" class="easyui-datagrid" style="height:340px"
						data-options="
							autoRowHeight:false,
							toolbar:'#toolbar',
							striped:true,
							rownumbers:true,
							singleSelect:true">
						<thead>
							<tr>
								<th field="role_id">Role ID</th>
								<th field="role_name">Role Name</th>
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="add()">Add</a>
						<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-remove' plain='true' onclick="remove()">Delete</a>
					</div>
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
	<!-- ROLE -->
	<form id="ff" method="post">
		<div id="dlg2" class="easyui-dialog" style="width:420px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg3" title="Role Menu" class="easyui-datagrid" style="height:330px" 
				data-options="
					url:'role.php',
					striped:true,
					autoRowHeight:false,
					toolbar:'#toolbar3'">
				<thead>
					<tr>
						<th field="ck" checkbox="true"></th>
						<th field="role_id">Role ID</th>
						<th field="role_name">Role Name</th>
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