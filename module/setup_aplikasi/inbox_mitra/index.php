<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Request ID</title>
	<script type="text/javascript">
		// datagrid filter
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
				$('#dlg').dialog('open').dialog('setTitle','Edit Request');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.req_seq;
			} else {
				alert('Silahkan pilih request id terlebih dahulu');
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
						$('#fm').form('clear');
					}
				}
			});
		}
		function reject(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#fm2').form('clear');
				$('#dlg2').dialog('open').dialog('setTitle','Edit Request');
				$('#fm').form('load',row);
				url = 'reject.php?id='+row.req_seq;
			} else {
				alert('Silahkan pilih request id terlebih dahulu');
			}
		}
		function save2(){
			$('#fm2').form('submit',{
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
						$('#dlg2').dialog('close');
						alert(result.success);
						$('#dg').datagrid('reload');
						$('#fm').form('clear');
					}
				}
			});
		}
		
		// validasi easyui combobox
		$.extend($.fn.validatebox.defaults.rules,{
		    inList:{
				validator:function(value,param){
					var c = $(param[0]);
					var opts = c.combobox('options');
					var data = c.combobox('getData');
					var exists = false;
					for(var i=0; i<data.length; i++){
						if (value == data[i][opts.textField]){
							exists = true;
							break;
						}
					}
						return exists;
				},
				message:'invalid value.'
		    }
		});
		
		//auto show combobox
		$(function(){
			$('#dept_name').combobox('textbox').focus(function(){
				$('#dept_name').combobox('showPanel');
			});
			$('#job_name').combobox('textbox').focus(function(){
				$('#job_name').combobox('showPanel');
			});
		});
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
				<th field="req_seq">Request ID</th>
				<th field="req_id">NPM</th>
				<th field="req_nama">Nama</th>
				<th field="req_tempat_lahir">Tempat Lahir</th>
				<th field="req_tanggal_lahir">Tanggal Lahir</th>
				<th field="dept_name">Departemen</th>
				<th field="job_name">Jabatan</th>
				<th field="nama_titik">Outlet</th>
				<th field="req_created">Tgl Request</th>
				<th field="req_status">Status</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="reject()">Reject</a>
	</div>
	
	<div id="dlg" class="easyui-dialog" style="width:540px;height:280px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons" modal="true">
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Nama</td>
					<td width="1px">:</td>
					<td>
						<input id="req_nama" name="req_nama" class="easyui-textbox" editable="false">
					</td>
				</tr>
					<td>Outlet</td>
					<td width="1px">:</td>
					<td>
						<input id="req_outlet" name="req_outlet" class="easyui-textbox" style="width:60px" editable="false">
						<input id="nama_titik" name="nama_titik" class="easyui-textbox" editable="false">
					</td>
				</tr>
				<tr>
					<td>NPM</td>
					<td width="1px">:</td>
					<td>
						<input id="req_id" name="req_id" class="easyui-textbox" style="width:60px" data-options="validType:'length[4,6]',required:true">
					</td>
				</tr>
				<tr>
					<td>Departemen</td>
					<td width="1px">:</td>
					<td>
						<input id="person_dept" name="person_dept" class="easyui-textbox" style="width:60px" editable="false">
						<input id="dept_name" name="dept_name" class="easyui-combobox" style="width:250px" validType="inList['#dept_name']" data-options="
							url:'dept.php',
							valueField:'dept_name',
							textField:'dept_name',
							onSelect:function(rec){
								$('#person_dept').textbox('setValue',rec.dept_id)
							},
							required:true">
					</td>
				</tr>
				<tr>
					<td>Jabatan</td>
					<td width="1px">:</td>
					<td>
						<input id="person_job" name="person_job" class="easyui-textbox" style="width:60px" editable="false">
						<input id="job_name" name="job_name" class="easyui-combobox" validType="inList['#job_name']" data-options="
							url:'job.php',
							valueField:'job_name',
							textField:'job_name',
							onSelect:function(rec){
								$('#person_job').textbox('setValue',rec.job_id)
							},
							required:true">
					</td>
				</tr>
			</table>
			<div id="dlg-buttons">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
			</div>
		</form>
	</div>
	
	<div id="dlg2" class="easyui-dialog" style="width:320px;height:150px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons2" modal="true">
		<form id="fm2" method="post">
			<table>
				<tr>
					<td>Reason</td>
					<td width="1px">:</td>
					<td>
						<input id="req_reason" name="req_reason" class="easyui-textbox" style="width:200px" data-options="required:true">
					</td>
				</tr>
			</table>
			<div id="dlg-buttons2">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save2()" style="width:90px">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg2').dialog('close')" style="width:90px">Cancel</a>
			</div>
		</form>
	</div>
</body>
</html>