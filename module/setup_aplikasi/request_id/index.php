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
		function add(){
			$('#dlg').dialog('open').dialog('setTitle','New Request');
			$('#fm').form('clear');
			$('#btnSubmit').linkbutton('enable');
			url = 'save.php';
		}
		function save(){
			var nama = $('#req_nama').textbox('getValue');
			var tempat = $('#req_tempat_lahir').textbox('getValue');
			var tgl = $('#req_tanggal_lahir').textbox('getValue');
			$.post("check_id.php", { nama: nama, tempat: tempat, tgl: tgl },
				function(result){
					if(result == 1){
						alert('Nama Telah terdaftar');
					} else {
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
						$('#btnSubmit').linkbutton('disable');
					}
				}
			)
		}
		
		// Date Format y-m-d
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
		
		//combobox data
		var category = [{label: 'KARYAWAN',value: 'K'},{label: 'MITRA',value: 'M'}];
		
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
			$('#req_tanggal_lahir').datebox('textbox').focus(function(){
				$('#req_tanggal_lahir').datebox('showPanel');
			});
			$('#nama_titik').combobox('textbox').focus(function(){
				$('#nama_titik').combobox('showPanel');
			});
			$('#category').combobox('textbox').focus(function(){
				$('#category').combobox('showPanel');
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
				<th field="req_id">User ID</th>
				<th field="req_nama">Nama</th>
				<th field="req_tempat_lahir">Tempat Lahir</th>
				<th field="req_tanggal_lahir">Tanggal Lahir</th>
				<th field="req_outlet">Outlet ID</th>
				<th field="nama_titik">Outlet Name</th>
				<th field="req_created">Tgl Request</th>
				<th field="req_updated">Tgl Proses</th>
				<th field="req_status">Status</th>
				<th field="req_reason">Reason</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="add()">New</a>
	</div>
	
	<div id="dlg" class="easyui-dialog" style="width:540px;height:300px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons" modal="true">
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Nama</td>
					<td width="1px">:</td>
					<td>
						<input id="req_nama" name="req_nama" class="easyui-textbox" data-options="required:true">
					</td>
				</tr>
				<tr>
					<td>Tempat Lahir</td>
					<td width="1px">:</td>
					<td>
						<input id="req_tempat_lahir" name="req_tempat_lahir" class="easyui-textbox" data-options="required:true">
					</td>
				</tr>
				<tr>
					<td>Tanggal Lahir</td>
					<td width="1px">:</td>
					<td>
						<input id="req_tanggal_lahir" name="req_tanggal_lahir" class="easyui-datebox" data-options="
							formatter:myformatter,
							parser:myparser,
							required:true">
						*YYYY-MM-DD
					</td>
				</tr>
				<tr>
					<td>Outlet</td>
					<td width="1px">:</td>
					<td>
						<input id="req_outlet" name="req_outlet" class="easyui-textbox" style="width:60px" editable="false">
						<input id="nama_titik" name="nama_titik" class="easyui-combobox" style="width:250px" validType="inList['#nama_titik']" data-options="
							url:'outlet.php',
							valueField:'nama_titik',
							textField:'nama_titik',
							onSelect:function(rec){
								$('#req_outlet').textbox('setValue',rec.kode_titik)
							},
							required:true">
					</td>
				</tr>
				<tr>
					<td>Category</td>
					<td width="1px">:</td>
					<td>
						<input id="cat_id" name="cat_id" class="easyui-textbox" style="width:60px" editable="false">
						<input id="category" name="category" class="easyui-combobox" validType="inList['#category']" data-options="
							data:category,
							valueField:'label',
							textField:'label',
							onSelect:function(rec){
								$('#cat_id').textbox('setValue',rec.value);
							},
							required:true">
					</td>
				</tr>
			</table>
			<div id="dlg-buttons">
				<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
			</div>
		</form>
	</div>
</body>
</html>