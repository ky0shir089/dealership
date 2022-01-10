<!DOCTYPE html>
<html>
<head>
	<title>Setup Margin</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter');
		});

		//toolbar
		var url;
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Edit Margin');
				$('#fm').form('load',row);
				url = "update.php?id="+row.margin_id;
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
				<th field="margin_id">ID</th>
				<th field="margin_desc">Description</th>
				<th field="margin_amount" align="right" formatter="numberFormat">Amount</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- new Margin dialog -->
	<div id="dlg" class="easyui-dialog" style="width:350px;height:250px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Add Margin</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>ID</td>
					<td width="1px">:</td>
					<td><input id="margin_id" name="margin_id" class="easyui-textbox" style="width:30px" disabled></td>
				</tr>
				<tr>
					<td>Description</td>
					<td width="1px">:</td>
					<td><input id="margin_desc" name="margin_desc" class="easyui-textbox" editable="false"></td>
				</tr>
				<tr>
					<td>Amount</td>
					<td width="1px">:</td>
					<td><input id="margin_amount" name="margin_amount" class="easyui-numberbox" style="width:70px" data-options="groupSeparator:',',required:true"></td>
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