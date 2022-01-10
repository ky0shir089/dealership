<!DOCTYPE html>
<html>
<head>
	<title>List Tagihan</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		//datagrid filter
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter',[{
				field:'invhdr_status',
				type:'combobox',
				options:{
					panelHeight:'auto',
					data:[{value:'',text:'All'},{value:'REQUEST',text:'REQUEST',selected:true},{value:'APPROVE',text:'APPROVE'},{value:'REJECT',text:'REJECT'},{value:'CANCEL',text:'CANCEL'},{value:'PAID',text:'PAID'}],
					onChange:function(value){
						if (value == ''){
							dg.datagrid('removeFilterRule', 'invhdr_status');
						} else {
							dg.datagrid('addFilterRule', {
								field: 'invhdr_status',
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
		function view(){
			var row = $('#dg').datagrid('getSelected');
			window.location.href = "view_tagihan.php?id="+row.invhdr_no;
		}
		
		function cancel(){
			var row = $('#dg').datagrid('getSelected');
			if(row.invhdr_status != 'APPROVE'){
				alert('Hanya status approve yang bisa di cancel');
			} else {
				if (row){
					$.messager.confirm('Confirm','Are you sure you want to cancel?',function(r){
						if (r){
							$.post('cancel.php',{id:row.invhdr_no},function(result){
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
				<th field="invhdr_no">No Invoice</th>
				<th field="nama_titik">Outlet</th>
				<th field="invhdr_amount" formatter="numberFormat">Amount</th>
				<th field="bank_name">Bank</th>
				<th field="invhdr_rek_no">No Rekening</th>
				<th field="invhdr_status">Status</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="view()">View</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancel()">Cancel</a>
	</div>
</body>
</html>