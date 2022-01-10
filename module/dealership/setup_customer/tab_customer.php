<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Data Karyawan</title>
	<script type="text/javascript">
		//combobox data
		var cust_type = [{label: 'INDIVIDU',value: 'I'},{label: 'COMPANY',value: 'C'},{label: 'PEDAGANG',value: 'T'}];
		var category = [{label: 'CUSTOMER',value: 'C'},{label: 'SUPPLIER',value: 'S'}];
		
		//save
		function submitForm(){
			var ktp = $('#cust_ktp').textbox('getValue');
			var id = $('#hidden').textbox('getValue');
			if(id == ""){
				$.post("check_ktp.php", { cust_ktp: ktp },
					function(result){
						if(result == 1){
							if(confirm('KTP sudah terdaftar\napakah anda ingin tetap memasukan customer ini?') == true){
								$('#fm').form('submit', {
									url: 'save.php',
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
											$('#cust_id').textbox('setValue',result.id);
										}
									}
								});
							} else {
								$('#cust_ktp').textbox('clear');
								$('#cust_ktp').textbox('textbox').focus();
							}
						}
					}
				)
			} else {
				$('#fm').form('submit', {
					url: 'update.php',
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
						}
					}
				});
			}
		}
		
		//clear form
		function reset(){
			location.reload();
		}
		
		//auto show combobox
		$(function(){
			$('#type').combobox('textbox').focus(function(){
				$('#type').combobox('showPanel');
			});
		});
		
		//lov
		function lov(){
			$(function(){
				$('#dg').datagrid('enableFilter');
			});
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		
		//add lov
		function ambil(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.cust_id);
				$('#cust_id').textbox('setValue', row.cust_id);
				$('#cust_name').textbox('setValue', row.cust_name);
				$('#cust_ktp').textbox('setValue', row.cust_ktp);
				$('#cust_owner').textbox('setValue', row.cust_owner);
				$('#cust_address').textbox('setValue', row.cust_address);
				$('#hp1').textbox('setValue',row.cust_hp);
				$('#hp2').textbox('setValue',row.cust_hp2);
				$('#cust_type').textbox('setValue', row.cust_type);
				$('#type').textbox('setValue', row.type);
				$('#cust_id').textbox('disable');
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih ID terlebih dahulu.')
			}
		}
		
		//next tab
		function next(){
			var category = $('#category').combobox('getValue');
			if(category == 'C'){
				$('#tt').tabs('enableTab',1);
				$('#tt').tabs('select', 1);
			} else {
				$('#tt').tabs('enableTab',2);
				$('#tt').tabs('select', 2);
			}
        }
	</script>
</head>
<body>
	<div class="easyui-panel" fit="true">
		<form id="fm" method="post">
			<div id="tt" class="easyui-tabs" plain="true">
				<div title="Personal Info" style="padding:10px 40px 20px 40px">
					<input id="category" name="category" class="easyui-combobox" validType="type['#category']" data-options="
							data:category,
							valueField:'value',
							textField:'label',
							required:true">
					<a id="cek" href="javascript:void(0)" class="easyui-linkbutton" onclick="next()">Next >></a>
				</div>
				<div title="Customer Info" style="padding:10px 40px 20px 40px" disabled>
					<table>
						<tr>
							<td>Customer ID</td>
							<td width="1px">:</td>
							<td>
								<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:90px"></div>
								<input id="cust_id" name="cust_id" class="easyui-textbox" style="width:90px" data-options="validType:'length[10,10]'" disabled>
								<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" plain="true" onclick="lov()"></a>
							</td>
						</tr>
						<tr>
							<td>Nama</td>
							<td width="1px">:</td>
							<td>
								<input id="cust_name" name="cust_name" class="easyui-textbox" data-options="required:true">
							</td>
						</tr>
						<tr>
							<td>No KTP</td>
							<td width="1px">:</td>
							<td>
								<input id="cust_ktp" name="cust_ktp" class="easyui-numberbox" data-options="validType:'length[16,16]',required:true">
							</td>
						</tr>
						<tr>
							<td>Pemilik</td>
							<td width="1px">:</td>
							<td>
								<input id="cust_owner" name="cust_owner" class="easyui-textbox" data-options="required:true">
							</td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td width="1px">:</td>
							<td>
								<input id="cust_address" name="cust_address" class="easyui-textbox" multiline="true" style="width:200px;height:80px" data-options="required:true">
							</td>
						</tr>
						<tr>
							<td>HP 1</td>
							<td width="1px">:</td>
							<td>
								<input id="hp1" name="cust_hp" class="easyui-textbox" data-options="validType:'length[12,13]',required:true">
							</td>
						</tr>
						<tr>
							<td>HP 2</td>
							<td width="1px">:</td>
							<td>
								<input id="hp2" name="cust_hp2" class="easyui-textbox" data-options="validType:'length[12,13]'">
							</td>
						</tr>
						<tr>
							<td>Customer Tipe</td>
							<td width="1px">:</td>
							<td>
								<div style="display:none"><input id="cust_type" name="cust_type" class="easyui-textbox" style="width:60px" editable="false"></div>
								<input id="type" name="type" class="easyui-combobox" validType="type['#type']" data-options="
									data:cust_type,
									valueField:'label',
									textField:'label',
									onSelect:function(rec){
										$('#cust_type').textbox('setValue',rec.value)
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
					<div id="dlg" class="easyui-dialog" style="width:720px;height:400px;padding:10px 20px"
						closed="true" modal="true">
						<table id="dg" class="easyui-datagrid" style="height:340px" 
							data-options="
								singleSelect:true,
								url:'customer.php',
								striped:true,
								autoRowHeight:false,
								toolbar:'#toolbar',
								view:bufferview">
							<thead>
								<tr>
									<th field="cust_id" width="100px">ID</th>
									<th field="cust_name" width="200px">Nama</th>
									<th field="cust_ktp" width="140px">KTP</th>
									<th field="cust_owner" width="200px">Pemilik</th>
								</tr>
							</thead>
						</table>
						<div id="toolbar">
							<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>