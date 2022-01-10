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
		
		//toolbar
		var url;
		function add(){
			$('#job_id').textbox('enable');
			$('#dlg').dialog('open').dialog('setTitle','New Job');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#job_id').textbox('disable');
				$('#dlg').dialog('open').dialog('setTitle','Edit Job');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.job_id;
			} else {
				alert('Silahkan pilih job terlebih dahulu');
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
				<th field="job_id">Job ID</th>
				<th field="job_name">Job Name</th>
				<th field="job_level">Job Level</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- new job dialog -->
	<div id="dlg" class="easyui-dialog" style="width:480px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Add Departemen</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Job ID</td>
					<td width="1px">:</td>
					<td><input id="job_id" name="job_id" class="easyui-textbox" style="width:60px" required="true"></td>
				</tr>
				<tr>
					<td>Job Name</td>
					<td width="1px">:</td>
					<td><input name="job_name" class="easyui-textbox" required="true"></td>
				</tr>
				<tr>
					<td>Job Level</td>
					<td width="1px">:</td>
					<td>
						<input id="job_level" name="job_level" class="easyui-numberbox" style="width:60px" required="true">
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