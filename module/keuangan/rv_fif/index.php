<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Setup Menu</title>
</head>
<body>
	<table id="dg"
		data-options="
			fit:true,
			border:false,
			autoRowHeight:false,
			toolbar:'#toolbar',
			striped:true,
			pagination:true,
			rownumbers:true,
			singleSelect:true,
			pageSize:20,
			remoteFilter:true">
		<thead>
			<tr>
				<th field="pv_no">PV NO</th>
				<th field="pv_paid_date">Tanggal</th>
				<th field="pv_desc">Keterangan</th>
				<th field="rekening">Rekening</th>
				<th field="pv_amount" align="right" formatter="numberFormat">Nominal</th>
				<th field="pv_rv_fif" editor="{type:'textbox',options:{required:true}}">RV FIF</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>
	</div>
	
	<script type="text/javascript">
		//datagrid filter
		$(function(){
			$('#dg').edatagrid({
				view: detailview,
				idField:'pv_no',
				url: 'load_data.php',
				updateUrl: 'save.php',
				onSuccess: function(index,row){
					alert(row.success);
					dg.datagrid('reload');
				},
				detailFormatter:function(index,row){
					return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
				},
				onExpandRow: function(index,row){
					$('#ddv-'+index).edatagrid({
						url:'load_data2.php?id='+row.pv_no,
						idField:'spuk_id',
						singleSelect:true,
						rownumbers:true,
						columns:[[
							{field:'spuk_id',title:'NO SPUK'},
							{field:'spuk_date',title:'Tanggal'},
							{field:'spuk_cust',title:'Kode Supplier'},
							{field:'supl_name',title:'Nama Supplier'},
							{field:'spuk_jml_unit',title:'Unit',align:'center'},
							{field:'spuk_total_hutang',title:'HBM',align:'right',formatter:numberFormat},
							{field:'spuk_total_scheme',title:'Scheme',align:'right',formatter:numberFormat},
							{field:'spuk_subtotal',title:'Total',align:'right',formatter:numberFormat},
							
						]],
						onResize:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},
						onLoadSuccess:function(){
							setTimeout(function(){
								$('#dg').datagrid('fixDetailRowHeight',index);
							},0);
						},
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
			});
			
			var dg = $('#dg');
			dg.edatagrid();
			dg.datagrid('enableFilter');
		});
		
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
</body>
</html>