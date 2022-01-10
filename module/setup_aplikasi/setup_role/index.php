<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Setup Menu</title>
	<script type="text/javascript">
		//datagrid filter
		$(function(){
			$('#dg').datagrid({
				view: detailview,
				idField:'role_id',
				detailFormatter:function(index,row){
					return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
				},
				onExpandRow: function(index,row){
					$('#ddv-'+index).edatagrid({
						url:'load_data2.php?id='+row.role_id,
						idField:'menu_id',
						singleSelect:true,
						rownumbers:true,
						columns:[[
							{field:'menu_id',title:'Menu ID',editor:{type:'textbox',options:{editable:false}}},
							{field:'menu_name',title:'Menu'},
							{field:'status',title:'Status',width:'100px',editor:{type:'combobox',options:{data:[{label: 'ACTIVE',value: 1},{label: 'INACTIVE',value: 0}],valueField:'value',textField:'label',required:true}}}
						]],
						toolbar:[{
							iconCls: 'icon-save',
							text: 'Save',
							handler: function(){
								$('#ddv-'+index).edatagrid('saveRow')
							}
						}],
						updateUrl: 'save3.php?id='+row.role_id,
						onSuccess: function(index,row){
							alert(row.success);
							$('#dg').datagrid('reload');
						},
						onResize:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},
						onLoadSuccess:function(){
							setTimeout(function(){
								$('#dg').datagrid('fixDetailRowHeight',index);
							},0);
						},
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});
			
			var dg = $('#dg, #dg2');
			dg.datagrid();
			dg.datagrid('enableFilter');
		});
		
		//toolbar1
		var url;
		function add(){
			$('#role_id').textbox('enable');
			$('#dlg').dialog('open').dialog('setTitle','Add Role');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#role_id').textbox('disable');
				$('#dlg').dialog('open').dialog('setTitle','Edit Role');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.role_id;
			} else {
				alert('Silahkan pilih role terlebih dahulu.')
			}
		}
		function save(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						$('#dlg').dialog('close');
						alert(result.success);
						$('#dg').datagrid('reload');
					}
				}
			});
		}
		//toolbar2
		function add2(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				$('#dlg2').dialog('open').dialog('setTitle','Add Role Menu');
				$('#fm2').form('load',row);
				url = 'save2.php?id='+row.role_id;
			} else {
				alert('Silahkan pilih role terlebih dahulu.')
			}
		}
		function edit2(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg2').dialog('open').dialog('setTitle','Edit Role Menu');
				$('#fm2').form('load',row);
				url = 'update2.php?id='+row.role_id;
			}
		}
		function submitForm(){
			$('#fm2').form('submit', {
				url:'save2.php',
				onSubmit: function(param){
					// param disini sebagai tambahan parameter yang dikirim ke proses php
					// param.data bisa diganti dengan nama variable yang lain
					param.data = JSON.stringify($('#dg2').datagrid('getSelections'));
				},
				success:function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						$('#dg2').datagrid('clearSelections');
						$('#dlg2').dialog('close');
						alert(result.success);
						$('#dg').datagrid('reload');
					}
				}
			});
		}
	</script>
</head>
<body>
	<table id="dg" fit="true"
		data-options="
			url:'load_data.php',
			autoRowHeight:false,
			toolbar:'#toolbar',
			striped:true,
			pagination:true,
			rownumbers:true,
			singleSelect:true,
			pageSize:50,
			pageList:[50]">
		<thead>
			<tr>
				<th field="role_id" width="15%">Role ID</th>
				<th field="role_name" width="15%">Role Name</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">Add Role</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit Role</a> |
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add2()">Add Role Menu</a>
		<!--<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:alert(row.menu_id)">Edit Role Menu</a>-->
	</div>
	<!-- add module dialog layout -->
	<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">New Role</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Role ID</td>
					<td width="1px">:</td>
					<td><input id="role_id" name="role_id" class="easyui-textbox" required="true"></td>
				</tr>
				<tr>
					<td>Role Name</td>
					<td width="1px">:</td>
					<td><input name="role_name" class="easyui-textbox" required="true"></td>
				</tr>
			</table>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<!-- add form dialog layout -->
	<div id="dlg2" class="easyui-dialog" style="width:380px;height:500px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons2" modal="true">
		<form id="fm2" method="post">
			<table border="1" width="100%">
				<thead>
					<tr align="center">
						<th>Role ID</th>
						<th>Role Name</th>
					</tr>
					<tr>
						<td><input id="id_role" name="role_id" class="easyui-textbox" editable="false"></input></td>
						<td><input id="role_name" name="role_name" class="easyui-textbox" style="width:160px" editable="false"></input></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><b>LIST MENU</b></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">
							<table id="dg2" style="width:100%;height:320px" data-options="
								url:'menu.php',striped:true,autoRowHeight:false">
								<thead>
									<tr>
										<th field="ck" checkbox="true"></th>
										<th field="menu_id">ID Menu</th>
										<th field="menu_name">Menu Name</th>
									</tr>
								</thead>
							</table>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2" align="center"><a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()">Submit</a></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</body>
</html>