<!DOCTYPE html>
<html>
<head>
	<title>Inbox Workflow</title>
	<?php 
		include ("../../../header.php"); 
		
		if($_SESSION['outlet'] == 10000){
			$value = '';
			$editable = 'true';
		} else {
			$value = $_SESSION['out_name'];
			$editable = 'false';
		}
	?>
	<script type="text/javascript">
		//datagrid filter
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter',[
			{
				field:'status',
				type:'combobox',
				options:{
					panelHeight:'auto',
					data:[{value:'',text:'All'},{value:'APPROVE',text:'APPROVE'},{value:'REJECT',text:'REJECT'},{value:'REVISE',text:'REVISE'}],
					onChange:function(value){
						if (value == ''){
							dg.datagrid('removeFilterRule', 'status');
						} else {
							dg.datagrid('addFilterRule', {
								field: 'status',
								op: 'equal',
								value: value
							});
						}
						dg.datagrid('doFilter');
					},
					editable:false
				}
			},
			{
				field:'nama_titik',
				type:'textbox',
				options:{
					editable:<?= $editable; ?>
				}
			}
			]);
			
			dg.datagrid('addFilterRule', {
				field: 'nama_titik',
				op: 'contains',
				value: '<?= $value; ?>'
			});
		});
		
		//toolbar
		function view(){
			var row = $('#dg').datagrid('getSelected');
			if(row.wf_id == "WF01"){
				window.location.href = "view_spuk.php?id="+row.no_proses;
			}
			if(row.wf_id == "WF02"){
				window.location.href = "view_cancel.php?id="+row.no_proses;
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
				<th field="wf_name">Nama Workflow</th>
				<th field="no_proses">No Proses</th>
				<th field="nama_titik">Outlet</th>
				<th field="wf_hist_executor">Eksekutor</th>
				<th field="status">Status</th>
				<th field="wf_hist_date_process">Tanggal</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="view()">View</a>
	</div>
</body>
</html>