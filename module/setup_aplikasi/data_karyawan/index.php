<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Data Karyawan</title>
	<script type="text/javascript">
		$(function(){
			$('#dg').datagrid('enableFilter');
		});
		
		//save
		function submitForm(){
			var id = $('#hidden').textbox('getValue');
			if(id == ""){
				var url = 'save.php'
			} else {
				var url = 'update.php'
			}
			$('#fm').form('submit', {
				url: url,
				onSubmit: function(param){
					return $(this).form('validate');
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
						$('#dg').datagrid('reload');
					}
				}
			});
		}
		function reset(){
			$('#fm').form('clear');
			$('#person_id').textbox('enable');
		}
		//lov
		function lov(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		//add lov
		function ambil(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.person_id);
				$('#person_id').textbox('setValue', row.person_id);
				$('#person_name').textbox('setValue', row.person_name);
				$('#person_dept').textbox('setValue', row.person_dept);
				$('#dept_name').textbox('setValue', row.dept_name);
				$('#person_job').textbox('setValue', row.person_job);
				$('#job_name').textbox('setValue',row.job_name);
				$('#person_outlet').textbox('setValue',row.person_outlet);
				$('#nama_titik').textbox('setValue', row.nama_titik);
				$('#person_id').textbox('disable');
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih npk terlebih dahulu.')
			}
		}
		//validasi easyui combobox
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
			$('#nama_titik').combobox('textbox').focus(function(){
				$('#nama_titik').combobox('showPanel');
			});
		});
	</script>
</head>
<body>
	<form id="fm" method="post">
		<table>
			<tr>
				<td>NPK</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
					<input id="person_id" name="person_id" class="easyui-textbox" style="width:60px" data-options="validType:'length[4,6]',required:true">
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
			<tr>
				<td>Nama</td>
				<td width="1px">:</td>
				<td>
					<input id="person_name" name="person_name" class="easyui-textbox" data-options="required:true">
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
			<tr>
				<td>Outlet</td>
				<td width="1px">:</td>
				<td>
					<input id="person_outlet" name="person_outlet" class="easyui-textbox" style="width:60px" editable="false">
					<input id="nama_titik" name="nama_titik" class="easyui-combobox" style="width:250px" validType="inList['#nama_titik']" data-options="
						url:'outlet.php',
						valueField:'nama_titik',
						textField:'nama_titik',
						onSelect:function(rec){
							$('#person_outlet').textbox('setValue',rec.kode_titik)
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()">Submit</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="reset()">Reset</a>
				</td>
			</tr>
		</table>
		<!-- LOV -->
		<div id="dlg" class="easyui-dialog" style="width:480px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg" class="easyui-datagrid" style="height:340px" 
				data-options="
					singleSelect:true,
					url:'person.php',
					striped:true,
					autoRowHeight:false,
					toolbar:'#toolbar',
					view:bufferview">
				<thead>
					<tr>
						<th field="person_id" width="60px">NPK</th>
						<th field="person_name" width="200px">Nama</th>
					</tr>
				</thead>
			</table>
			<div id="toolbar">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
			</div>
		</div>
	</form>
</body>
</html>