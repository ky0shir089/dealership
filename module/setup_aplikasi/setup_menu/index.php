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
				idField:'module_id',
				detailFormatter:function(index,row){
					return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
				},
				onExpandRow: function(index,row){
					$('#ddv-'+index).edatagrid({
						url:'load_data2.php?id='+row.module_id,
						idField:'module_id',
						singleSelect:true,
						columns:[[
							{field:'menu_id',title:'Menu ID'},
							{field:'menu_icon',title:'Icon',editor:{type:'textbox',options:{required:true}}},
							{field:'menu_name',title:'Menu',editor:{type:'textbox',options:{required:true}}},
							{field:'menu_page',title:'Page',editor:{type:'textbox',options:{required:true}}},
							{field:'is_aktif',title:'is Aktif',editor:{type:'textbox',options:{required:true}}},
							{field:'seq',title:'Seq',editor:{type:'textbox',options:{required:true}}}
						]],
						toolbar:[{
							iconCls: 'icon-add',
							text: 'New',
							handler: function(){
								$('#ddv-'+index).edatagrid('addRow');
							}
						},{
							iconCls: 'icon-save',
							text: 'Save',
							handler: function(){
								$('#ddv-'+index).edatagrid('saveRow')
							}
						},{
							iconCls: 'icon-undo',
							text: 'Cancel',
							handler: function(){
								$('#ddv-'+index).edatagrid('cancelRow')
							}
						}],
						saveUrl: 'save2.php?id='+row.module_id,
						updateUrl: 'update2.php',
						onSuccess: function(index,row){
							alert(row.success);
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
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter',[{
				field:'is_aktif',
				type:'combobox',
				options:{
					panelHeight:'auto',
					data:[{value:'',text:'All'},{value:'1',text:'ACTIVE'},{value:'0',text:'NOT ACTIVE'}],
					onChange:function(value){
						if (value == ''){
							dg.datagrid('removeFilterRule', 'is_aktif');
						} else {
							dg.datagrid('addFilterRule', {
								field: 'is_aktif',
								op: 'equal',
								value: value
							});
						}
						dg.datagrid('doFilter');
					},
					editable:false
				}
			}]);
		});
		
		//toolbar1
		var url;
		function add(){
			$('#module_id').textbox('enable');
			$('#dlg').dialog('open').dialog('setTitle','Add Module');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#module_id').textbox('disable');
				$('#dlg').dialog('open').dialog('setTitle','Edit Module');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.module_id;
			} else {
				alert('Silahkan pilih module terlebih dahulu.')
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
				<th field="module_id" width="38%">Module ID</th>
				<th field="module_name" width="38%">Module Name</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- add module dialog layout -->
	<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">New Module</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Module ID</td>
					<td width="1px">:</td>
					<td><input id="module_id" name="module_id" class="easyui-textbox" required="true"></td>
				</tr>
				<tr>
					<td>Module Name</td>
					<td width="1px">:</td>
					<td><input name="module_name" class="easyui-textbox" required="true"></td>
				</tr>
			</table>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
</body>
</html>