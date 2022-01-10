<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Jurnal Koreksi</title>
	<script type="text/javascript">
		$(function(){
			$('#dg_lov').datagrid('enableFilter');
			$('#dg').datagrid({
				onBeginEdit: function(index,row){
					var editors = $('#dg').edatagrid('getEditors', index);
					var v1 = $(editors[0].target);
					var v2 = $(editors[1].target);
					var v3 = $(editors[2].target);
					var v4 = $(editors[3].target);
					var v5 = $(editors[4].target);
					var v6 = $(editors[5].target);
					var v7 = $(editors[6].target);
					var v8 = $(editors[7].target);
					v2.combobox({
						url:'outlet.php',
						valueField:'nama_titik',
						textField:'nama_titik',
						required:true,
						onSelect:function(rec){
							v1.textbox('setValue',rec.kode_titik);
							v1.textbox('readonly',true);
						}
					});
					v3.combobox({
						data:jc_type,
						valueField:'value',
						textField:'label',
						required:true,
						onSelect:function(rec){
							v4.textbox('clear');
							v5.combobox('clear');
							v6.textbox('clear');
							var url = 'type.php?id='+rec.value;
							v5.combobox('reload',url);
						}
					});
					v5.combobox({
						valueField:'coa_description',
						textField:'coa_description',
						required:true,
						onSelect:function(rec){
							v4.textbox('setValue',rec.coa_code);
							v4.textbox('readonly',true);
						}
					});
					v6.combogrid({
						panelWidth: 300,
						idField: 'rv_no',
						textField: 'rv_no',
						url: 'referensi.php',
						method: 'get',
						mode: 'remote',
						columns: [[
							{field:'rv_no',title:'No RV',width:70},
							{field:'rv_amount',title:'Amount',width:30,align:'right',formatter:numberFormat}
						]],
						fitColumns: true,
						view: bufferview,
						pageSize: 50
					});
					v7.numberbox({
						groupSeparator:',',
						required:true
					});
					v8.numberbox({
						groupSeparator:',',
						required:true
					});
				}
			});
		});
		
		function addRow(){
			$('#dg').edatagrid('addRow');
			$('#dg').datagrid('selectAll');
		}
		
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
				$('#dg').datagrid('options').url = 'detail.php?id='+row.gl_no;
				$('#dg').datagrid('reload');
				$('#btnSubmit').linkbutton('disable');
				$('#dlg').dialog('close');
			}
		}
			
		//combobox data
		var jc_type = [{label: 'BANK',value: 'BANK'},{label: 'LAINNYA',value: 'LAINNYA'}];

		// submit
		function submitForm(){
			$('#dg').edatagrid('saveRow');
			$('#ff').form('submit', {
				url: 'save.php',
				onSubmit: function(param){
					param.data = JSON.stringify($('#dg').datagrid('getSelections'));
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
						$('#hidden').textbox('setValue', result.jc_no);
						$('#gl_no').textbox('setValue', result.jc_no);
						$('#gl_create_by').textbox('setValue', result.create_by);
						$('#btnSubmit').linkbutton('disable');
					}
				}
			});
		}
		
		function reset(){
			location.reload();
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
	<form id="ff" method="post">
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
		</table>
		<br>
		<table id="dg" class="easyui-edatagrid" style="width:100%;height:210px"
			data-options="
				toolbar:'#tb',
				rownumbers:true,
				fitColumns:true,
				autoRowHeight:false,
				striped:true">
			<thead>
				<tr>
					<th field="kode_titik" width="20" editor="{type:'textbox'}">Outlet</th>
					<th field="nama_titik" width="80" editor="{type:'combobox'}">Nama Outlet</th>
					<th field="jc_type" width="30" editor="{type:'combobox'}">Tipe</th>
					<th field="coa_code" width="30" editor="{type:'textbox'}">Code Trx</th>
					<th field="coa_description" width="100" editor="{type:'combobox'}">Nama Akun</th>
					<th field="invhdr_reff_no" width="60" editor="{type:'combogrid'}">No Reff</th>
					<th field="gl_dr" width="40" align="right" editor="{type:'numberbox'}" formatter="numberFormat">Debit</th>
					<th field="gl_cr" width="40" align="right" editor="{type:'numberbox'}" formatter="numberFormat">Credit</th>
				</tr>
			</thead>
		</table>
		<div id="tb">
			<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="addRow()">New</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Done</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>
		</div>
		<br>
		<div align="center">
			<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()">Submit</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="reset()">Reset</a>
		</div>
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
				view:bufferview,
				pageSize:50">
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