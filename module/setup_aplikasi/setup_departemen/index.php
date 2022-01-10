<!DOCTYPE html>
<html>
<head>
	<title>Setup Departemen</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter');
		});
		
		//toolbar
		var url;
		function add(){
			$('#dlg').dialog('open').dialog('setTitle','New Departemen');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dept_id').textbox('disable');
				$('#dlg').dialog('open').dialog('setTitle','Edit Departemen');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.dept_id;
			} else {
				alert('Silahkan pilih departemen terlebih dahulu');
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
		
		//auto show combobox
		$(function(){
			$('#parent').combobox('textbox').focus(function(){
				$('#parent').combobox('showPanel');
			});
		});
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
				<th field="dept_id">ID Departemen</th>
				<th field="dept_name">Nama Departemen</th>
				<th field="dept_parent_id">Parent ID</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- new dept dialog -->
	<div id="dlg" class="easyui-dialog" style="width:530px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Add Departemen</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>ID Departemen</td>
					<td width="1px">:</td>
					<td><input id="dept_id" name="dept_id" class="easyui-textbox" style="width:60px" required="true"></td>
				</tr>
				<tr>
					<td>Nama Departemen</td>
					<td width="1px">:</td>
					<td><input name="dept_name" class="easyui-textbox" style="width:200px" required="true"></td>
				</tr>
				<tr>
					<td>Parent ID</td>
					<td width="1px">:</td>
					<td>
						<input id="dept_parent_id" name="dept_parent_id" class="easyui-textbox" style="width:60px" editable="false">
						<input id="parent" class="easyui-combobox" style="width:200px" validType="inList['#parent']" data-options="
							url:'dept.php',
							valueField:'dept_name',
							textField:'dept_name',
							onSelect:function(rec){
								$('#dept_parent_id').textbox('setValue',rec.dept_id);
							}">
					</td>
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