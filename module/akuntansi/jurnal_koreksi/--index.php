<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Invoice</title>
	<script type="text/javascript">
		// lov
		function lov(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		
		function ambil(){
			var row = $('#dg_lov').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.gl_no);
				$('#gl_no').textbox('setValue', row.gl_no);
				$('#gl_date').textbox('setValue', row.gl_date);
				$('#gl_desc').textbox('setValue', row.gl_desc);
				$('#gl_create_by').textbox('setValue', row.gl_create_by);
				$('#dlg').dialog('close');
			}
		}
		
		//combobox data
		var jc_type = [{label: 'BANK',value: 'BANK'},{label: 'LAINNYA',value: 'LAINNYA'}];

		function addRow(){
			var row = $('#row').numberbox('getValue');
			var index = $('#index').numberbox('getValue');
			if(row == 0){
				index = 0;
				row = $('#row').numberbox('setValue',parseInt(row)+1);
			} else {
				$('#row').numberbox('setValue',parseInt(row)+1);
				index = $('#index').numberbox('setValue',parseInt(index)+1);
			}
			$('#dg').edatagrid('addRow');
			$('#dg').edatagrid('checkAll');
		}
		
		// submit
		function submitForm(){
			var sum_dr = 0;
			var sum_cr = 0;
			var rows = $('#dg').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				sum_dr += parseInt(rows[i].gl_dr);
				sum_cr += parseInt(rows[i].gl_cr);
            }
			if(sum_dr!=sum_cr){
				alert('Jurnal Unbalance');
				location.reload();
			} else {
				$('#fm').form('submit', {
					url: 'save.php',
					onSubmit: function(param){
						param.data = JSON.stringify($('#dg').datagrid('getSelections'));
					},
					success:function(result){
						var result = eval('('+result+')');
						if (result.errorMsg){
							$.messager.show({
								title: 'Error',
								msg: result.errorMsg
							});
						} else {
							alert(result.success);
							$('#hidden').textbox('setValue', result.jc_no);
							$('#gl_no').textbox('setValue', result.jc_no);
							$('#gl_create_by').textbox('setValue', result.create_by);
							$('#btnSubmit').linkbutton('disable');
						}
					}
				});
			}
		}
	
		// formatter
		function myformatter(date){
            var y = date.getFullYear();
            var m = date.getMonth()+1;
            var d = date.getDate();
            return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
        }
        function myparser(s){
            if (!s) return new Date();
            var ss = (s.split('-'));
            var y = parseInt(ss[0],10);
            var m = parseInt(ss[1],10);
            var d = parseInt(ss[2],10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
                return new Date(y,m-1,d);
            } else {
                return new Date();
            }
        }
	</script>
</head>
<body>
	<form id="fm" method="post">
		<table>
			<tr>
				<td>No Jurnal</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
					<input id="gl_no" name="gl_no" class="easyui-textbox" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="gl_date" name="gl_date" class="easyui-datebox" style="width:100px" data-options="formatter:myformatter,parser:myparser,required:true">
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="gl_desc" name="gl_desc" class="easyui-textbox" style="width:300px" required="true">
				</td>
			</tr>
			<tr>
				<td>Koreksi by</td>
				<td width="1px">:</td>
				<td>
					<input id="gl_create_by" name="gl_create_by" class="easyui-textbox" style="width:60px" editable="false">
					<div style="display:none">
						<input id="row" name="row" class="easyui-numberbox" style="width:20px" value=0 editable="false">
						<input id="index" name="index" class="easyui-numberbox" style="width:20px" value=0 editable="false">
					</div>
				</td>
			</tr>
			<tr>
				<td colspan=3>
					<table id="dg" class="easyui-edatagrid" style="width:1000px;height:400px"
						data-options="
							striped:true,
							autoRowHeight:false,
							toolbar:'#toolbar'">
						<thead>
							<tr>
								<th field="ck" checkbox="true"></th>
								<th field="gl_segment1" width="300px" editor="{type:'combobox',options:{
									url:'outlet.php',
									valueField:'kode_titik',
									textField:'nama_titik',
									required:true}}">Outlet</th>
								<th field="jc_type" width="90px" editor="{type:'combobox',options:{
									data:jc_type,
									valueField:'value',
									textField:'label',
									onSelect:function(rec){
										var index = $('#index').numberbox('getValue');
										var editor = $('#dg').edatagrid('getEditor', {index:index,field:'gl_segment2'});
										var url = 'type.php?id='+rec.value;
										$(editor.target).combobox('clear');
										$(editor.target).combobox('reload',url);
									},
									required:true}}">Tipe</th>
								<th field="gl_segment2" width="200px" editor="{type:'combobox',options:{
									valueField:'coa_code',
									textField:'coa_description',
									required:true}}">Kode</th>
								<th field="gl_dr" width="90px" align="right" editor="{type:'numberbox',options:{groupSeparator:',',required:true}}">Debit</th>
								<th field="gl_cr" width="90px" align="right" editor="{type:'numberbox',options:{groupSeparator:',',required:true}}">Credit</th>
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addRow()">New</a>
						<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Save</a>
						<a href="#" id="btnSubmit" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="submitForm()">Submit</a>
						<a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>
					</div>
				</td>
			</tr>
		</table>
	</form>
	<!-- LOV -->
	<div id="dlg" class="easyui-dialog" style="width:620px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_lov" class="easyui-datagrid" style="height:340px" 
			data-options="
				singleSelect:true,
				url:'lov.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar2',
				view:bufferview">
			<thead>
				<tr>
					<th field="gl_no" width="150px">No JC</th>
					<th field="gl_date" width="100px">Tanggal</th>
					<th field="gl_desc" width="300px">Keterangan</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar2">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
		</div>
	</div>
</body>
</html>