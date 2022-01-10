<!DOCTYPE html>
<html>
<head>
	<title>Setup Scheme</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter');
		});
		
		var stats = [{label: 'ACTIVE',value: 'Y'},{label: 'INACTIVE',value: 'N'}];
		
		//toolbar
		var url;
		function add(){
			$('#scheme_id').textbox('enable');
			$('#scheme_amount').numberbox('enable');
			$('#dlg').dialog('open').dialog('setTitle','New Scheme');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#scheme_id').textbox('disable');
				$('#scheme_amount').numberbox('disable');
				$('#dlg').dialog('open').dialog('setTitle','Edit Scheme');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.scheme_id;
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
		
		//auto show combobox
		$(function(){
			$('#status').combobox('textbox').focus(function(){
				$('#status').combobox('showPanel');
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
				<th field="scheme_id">ID</th>
				<th field="scheme_amount" align="right" formatter="numberFormat">Amount</th>
				<th field="status">Status</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- new scheme dialog -->
	<div id="dlg" class="easyui-dialog" style="width:350px;height:250px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Add Scheme</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>ID</td>
					<td width="1px">:</td>
					<td><input id="scheme_id" name="scheme_id" class="easyui-textbox" style="width:70px" required="true"></td>
				</tr>
				<tr>
					<td>Amount</td>
					<td width="1px">:</td>
					<td><input id="scheme_amount" name="scheme_amount" class="easyui-numberbox" style="width:70px" data-options="groupSeparator:',',required:true"></td>
				</tr>
				<tr>
					<td>Status</td>
					<td width="1px">:</td>
					<td>
						<div style="display:none"><input id="scheme_status" name="scheme_status" class="easyui-textbox" style="width:20px" editable="false"></div>
						<input id="status" name="status" class="easyui-combobox" style="width:90px" validType="inList['#status']" data-options="
							data:stats,
							valueField:'label',
							textField:'label',
							onSelect:function(rec){
								$('#scheme_status').textbox('setValue',rec.value);
							}"></input>
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