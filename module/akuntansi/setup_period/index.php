<!DOCTYPE html>
<html>
<head>
	<title>Setup Period</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter');
		});
		
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
		
		//toolbar
		var url;
		function add(){
			//$('#coa_code').textbox('enable');
			$('#dlg').dialog('open').dialog('setTitle','New Period');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#coa_code').textbox('disable');
				$('#dlg').dialog('open').dialog('setTitle','Edit Chart of Account');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.coa_code;
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
				<th field="period_id">Period ID</th>
				<th field="period_num" align="mid">Num</th>
				<th field="period_start_date">Start</th>
				<th field="period_end_date">End</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- new period dialog -->
	<div id="dlg" class="easyui-dialog" style="width:310px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Add Period</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Period ID</td>
					<td width="1px">:</td>
					<td><input id="period_id" name="period_id" class="easyui-textbox" style="width:60px" required="true"></td>
				</tr>
				<tr>
					<td>Num</td>
					<td width="1px">:</td>
					<td><input id="period_num" name="period_num" class="easyui-numberbox" style="width:30px" data-options="min:1,max:12,required:true"></td>
				</tr>
				<tr>
					<td>Start</td>
					<td width="1px">:</td>
					<td>
						<input id="period_start_date" name="period_start_date" class="easyui-datebox" style="width:100px" data-options="formatter:myformatter,parser:myparser,required:true">
					</td>
				</tr>
				<tr>
					<td>End</td>
					<td width="1px">:</td>
					<td>
						<input id="period_end_date" name="period_end_date" class="easyui-datebox" style="width:100px" data-options="formatter:myformatter,parser:myparser,required:true">
					</td>
				</tr>
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