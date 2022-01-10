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
		function cancel(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to Cancel?',function(r){
					if (r){
						$.post('cancel.php',{id:row.spuk_id},function(result){
							if (result.success){
								alert(result.success);
								$('#dg').datagrid('reload');
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
				<th field="spuk_id">No SPUK</th>
				<th field="spuk_date">Tanggal</th>
				<th field="supl_name">Customer</th>
				<th field="spuk_status">Status</th>
				<th field="spuk_create_by">Create by</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel()">Cancel</a>
	</div>
</body>
</html>