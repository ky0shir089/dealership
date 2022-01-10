<!DOCTYPE html>
<html>
<head>
	<title>Setup Job</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		//datagrid filter
		$(function(){
			$('#dg').datagrid({
				view: detailview,
				idField:'wf_id',
				detailFormatter:function(index,row){
					return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
				},
				onExpandRow: function(index,row){
					$('#ddv-'+index).edatagrid({
						url:'load_data2.php?id='+row.wf_id,
						idField:'module_id',
						singleSelect:true,
						columns:[[
							{field:'wf_dtl_no',title:'ID'},
							{field:'wf_dtl_urutan',title:'Urutan'},
							{field:'wf_dtl_dept',title:'Departemen',width:'250px',editor:{type:'combobox',options:{
								url:'dept.php',
								valueField:'dept_id',
								textField:'dept_name',
								required:true}}},
							{field:'wf_dtl_job',title:'Jabatan',width:'150px',editor:{type:'combobox',options:{
								url:'job.php',
								valueField:'job_id',
								textField:'job_name',
								required:true}}}
						]],
						toolbar:[{
							iconCls: 'icon-add',
							text: 'New',
							handler: function(){
								$('#ddv-'+index).edatagrid('addRow');
							}
						},{
							iconCls: 'icon-save',
							text: 'Save',
							handler: function(){
								$('#ddv-'+index).edatagrid('saveRow')
							}
						},{
							iconCls: 'icon-undo',
							text: 'Cancel',
							handler: function(){
								$('#ddv-'+index).edatagrid('cancelRow')
							}
						}],
						saveUrl: 'save2.php?id='+row.wf_id,
						updateUrl: 'update2.php',
						onSuccess: function(index,row){
							alert(row.success);
						},
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
		var url;
		function add(){
			$('#wf_id').textbox('enable');
			$('#dlg').dialog('open').dialog('setTitle','New Workflow');
			$('#fm').form('clear');
			url = 'save.php';
		}
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#wf_id').textbox('disable');
				$('#status').css("display", "table-row");
				if(row.wf_status == 'Y'){
					$('#wf_status').switchbutton({checked: true});
				}
				$('#dlg').dialog('open').dialog('setTitle','Edit Workflow');
				$('#fm').form('load',row);
				url = 'update.php?id='+row.wf_id;
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
				<th field="wf_id">WF ID</th>
				<th field="wf_name">WF Name</th>
				<th field="menu_name">WF Form</th>
				<th field="status">WF Status</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit()">Edit</a>
	</div>
	<!-- new wf dialog -->
	<div id="dlg" class="easyui-dialog" style="width:480px;height:280px;padding:10px 20px"
		closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Add Workflow</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>WF ID</td>
					<td width="1px">:</td>
					<td><input id="wf_id" name="wf_id" class="easyui-textbox" style="width:60px" required="true"></td>
				</tr>
				<tr>
					<td>WF Name</td>
					<td width="1px">:</td>
					<td><input name="wf_name" class="easyui-textbox" style="width:200px" required="true"></td>
				</tr>
				<tr>
					<td>WF Form</td>
					<td width="1px">:</td>
					<td>
						<input id="wf_form" name="wf_form" class="easyui-textbox" style="width:70px" editable="false">
						<input id="menu_name" name="menu_name" class="easyui-combobox" validType="inList['#menu_name']" data-options="
							url:'menu.php',
							valueField:'menu_name',
							textField:'menu_name',
							onSelect:function(rec){
								$('#wf_form').textbox('setValue', rec.menu_id);
							},">
					</td>
				</tr>
				<tr id="status"style="display:none">
					<td>WF Status</td>
					<td width="1px">:</td>
					<td><input id="wf_status" name="wf_status" class="easyui-switchbutton" style="width:90px" data-options="onText:'ACTIVE',offText:'INACTIVE',value:'Y'"></td>
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