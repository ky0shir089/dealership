<!DOCTYPE html>
<html>
<head>
	<title>Setup Job</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter');
		});
		
		//combobox data
		var type_name = [{label: 'ASSET',value: 'A'},{label: 'LIABILITIES',value: 'L'},{label: 'OWNER EQUITY',value: 'O'},{label: 'REVENUE',value: 'R'},{label: 'EXPENSE',value: 'E'}];
		
		//toolbar
		var url;
		function add(){
			$('#coa_code').textbox('enable');
			$('#dlg').dialog('open').dialog('setTitle','New Chart of Account');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#coa_code').textbox('disable');
				$('#dlg').dialog('open').dialog('setTitle','Edit Chart of Account');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.coa_code;
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
						$('#parent_name').combobox('reload');
					}
				}
			});
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
				<th field="coa_code">Kode</th>
				<th field="coa_description">Keterangan</th>
				<th field="type_name">Tipe</th>
				<th field="coa_parent">Parent</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- new coa dialog -->
	<div id="dlg" class="easyui-dialog" style="width:610px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Add Chart of Account</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Kode</td>
					<td width="1px">:</td>
					<td><input id="coa_code" name="coa_code" class="easyui-textbox" required="true"></td>
				</tr>
				<tr>
					<td>Keterangan</td>
					<td width="1px">:</td>
					<td><input name="coa_description" class="easyui-textbox" style="width:300px" required="true"></td>
				</tr>
				<tr>
					<td>Tipe</td>
					<td width="1px">:</td>
					<td>
						<input id="coa_type" name="coa_type" class="easyui-textbox" style="width:20px" editable="false">
						<input id="type_name" name="type_name" class="easyui-combobox" validType="inList['#type_name']" data-options="
							data:type_name,
							valueField:'label',
							textField:'label',
							onSelect:function(rec){
								$('#coa_type').textbox('setValue',rec.value);
							},
							required:true">
					</td>
				</tr>
				<tr>
					<td>Parent</td>
					<td width="1px">:</td>
					<td>
						<input id="coa_parent" name="coa_parent" class="easyui-textbox" style="width:70px" editable="false">
						<input id="parent_name" name="parent_name" class="easyui-combobox" style="width:320px" data-options="
							url:'coa.php',
							valueField:'coa_description',
							textField:'coa_description',
							onSelect:function(rec){
								$('#coa_parent').textbox('setValue',rec.coa_code)
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