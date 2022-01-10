<!DOCTYPE html>
<html>
<head>
	<title>Setup Job</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		//datagrid filter
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter',[{
				field:'status',
				type:'combobox',
				options:{
					panelHeight:'auto',
					data:[{value:'',text:'All'},{value:'Y',text:'ACTIVE'},{value:'N',text:'NOT ACTIVE'}],
					onChange:function(value){
						if (value == ''){
							dg.datagrid('removeFilterRule', 'wf_status');
						} else {
							dg.datagrid('addFilterRule', {
								field: 'wf_status',
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
		
		//toolbar
		function cancel(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to Cancel?',function(r){
					if (r){
						$.post('cancel.php',{id:row.no_proses},function(result){
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
				<th field="wf_name">Nama Workflow</th>
				<th field="no_proses">No Proses</th>
				<th field="nama_titik">Outlet</th>
				<th field="spuk_subtotal" formatter="numberFormat" align="right">Nominal</th>
				<th field="wf_hist_date_create">Tanggal</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel()">Cancel</a>
	</div>
</body>
</html>