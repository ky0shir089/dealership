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
				field:'wf_hist_status',
				type:'combobox',
				options:{
					panelHeight:'auto',
					data:[{value:'',text:'ALL'},{value:'RUNNING',text:'RUNNING',selected:true},{value:'DONE',text:'DONE'}],
					onChange:function(value){
						if (value == ''){
							dg.datagrid('removeFilterRule', 'wf_hist_status');
						} else {
							dg.datagrid('addFilterRule', {
								field: 'wf_hist_status',
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
			window.location.href = "view_pln.php?id="+row.no_proses;
		}
	</script>
</head>
<body>
	<table id="dg" fit="true"
		data-options="
			url:'load_data.php',
			autoRowHeight:false,
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
				<th field="wf_hist_dept">Departemen</th>
				<th field="job_name">Jabatan</th>
				<th field="wf_hist_status">Status</th>
				<th field="wf_hist_date_process">Tanggal</th>
			</tr>
		</thead>
	</table>
</body>
</html>