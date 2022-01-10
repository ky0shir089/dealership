<!DOCTYPE html>
<html>

<head>
	<?php include("../../../header.php"); ?>
	<title>Master Supplier</title>
</head>

<body>
	<div id="tt" class="easyui-tabs" plain="true">
		<div title="Category" style="padding:10px 40px 20px 40px">
			<input id="category" name="category" class="easyui-combobox" validType="inList['#category']" data-options="
				data:category,
				valueField:'value',
				textField:'label',
				required:true">
			<a id="cek" href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-next" iconAlign="right" onclick="next()">Next</a>
		</div>
		<div title="Buyer" style="padding:10px 40px 20px 40px" disabled>
			<table width="100%">
				<tr>
					<td width="40%">
						<form id="fm" method="post">
							<table>
								<tr>
									<td>Buyer ID</td>
									<td width="1px">:</td>
									<td>
										<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:90px"></div>
										<input id="cust_id" name="cust_id" class="easyui-textbox" style="width:90px" disabled>
										<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
									</td>
								</tr>
								<tr>
									<td>Nama</td>
									<td width="1px">:</td>
									<td>
										<input id="supl_name" name="supl_name" class="easyui-textbox" data-options="required:true">
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
									<td>Provinsi</td>
									<td width="1px">:</td>
									<td>
										<div style="display:none"><input id="province_id" name="province_id" class="easyui-textbox" style="width:20px" editable="false"></div>
										<input id="province_name" name="province_name" class="easyui-combobox" style="width:200px" validType="inList['#province_name']" data-options="
											url:'province.php',
											valueField:'province_name',
											textField:'province_name',
											onSelect:function(rec){
												$('#province_id').textbox('setValue',rec.province_id);
												$('#regency_id').textbox('clear');
												$('#regency_name').combobox('clear');
												var url = 'regency.php?id='+rec.province_id;
												$('#regency_name').combobox('reload', url);
											},
											required:true">
									</td>
								</tr>
								<tr>
									<td>Kabupaten</td>
									<td width="1px">:</td>
									<td>
										<div style="display:none"><input id="regency_id" name="regency_id" class="easyui-textbox" style="width:40px" editable="false"></div>
										<input id="regency_name" name="regency_name" class="easyui-combobox" style="width:200px" data-options="
											valueField:'regency_name',
											textField:'regency_name',
											onSelect:function(rec){
												$('#regency_id').textbox('setValue',rec.regency_id);
											},
											required:true">
									</td>
								</tr>
								<tr>
									<td>HP 1</td>
									<td width="1px">:</td>
									<td>
										<input id="hp1" name="cust_hp" class="easyui-textbox" data-options="validType:'length[10,13]',required:true">
									</td>
								</tr>
								<tr>
									<td>HP 2</td>
									<td width="1px">:</td>
									<td>
										<input id="hp2" name="cust_hp2" class="easyui-textbox" data-options="validType:'length[10,13]'">
									</td>
								</tr>
								<tr>
									<td>Customer Tipe</td>
									<td width="1px">:</td>
									<td>
										<div style="display:none"><input id="supl_type" name="supl_type" class="easyui-textbox" style="width:60px" editable="false"></div>
										<input id="type" name="type" class="easyui-combobox" validType="inList['#type']" data-options="
											data:cust_type,
											valueField:'label',
											textField:'label',
											onSelect:function(rec){
												$('#supl_type').textbox('setValue',rec.value)
											},
											required:true">
									</td>
								</tr>
								<tr>
									<td>Status</td>
									<td width="1px">:</td>
									<td>
										<input id="supl_status" name="supl_status" class="easyui-combobox" validType="inList['#supl_status']" data-options="
											data:stats,
											valueField:'value',
											textField:'label'">
									</td>
								</tr>
								<tr>
									<td colspan="3" align="right">
										<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-save" onclick="submitForm()">Submit</a>
										<a href="javascript:void(0)" class="easyui-linkbutton c8" iconCls="icon-reload" onclick="reset()">Reset</a>
									</td>
								</tr>
							</table>
						</form>
					</td>
					<td width="60%" valign="top">
						<table id="dg2" title="Rekening" class="easyui-edatagrid" style="height:auto" data-options="
								autoRowHeight:false,
								toolbar:'#toolbar2',
								striped:true,
								rownumbers:true,
								singleSelect:true">
							<thead>
								<tr>
									<th field="rek_bank" width="100px" editor="{type:'combobox',options:{
										url:'bank.php',
										valueField:'bank_id',
										textField:'bank_name',
										required:true}}">ID</th>
									<th field="bank_name" width="60px">Bank</th>
									<th field="rek_no" width="150px" editor="{type:'textbox',options:{required:true}}">No Rekening</th>
									<th field="rek_name" width="200px" editor="{type:'textbox',options:{required:true}}">Nama Rekening</th>
								</tr>
							</thead>
						</table>
						<div id="toolbar2">
							<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="add()">Add</a>
							<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-save' plain='true' onclick="javascript:$('#dg2').edatagrid('saveRow')">Save</a>
							<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg2').edatagrid('cancelRow')">Cancel</a>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div title="Supplier" style="padding:10px 40px 20px 40px" disabled>
			<table width="100%">
				<tr>
					<td width="40%">
						<form id="ff" method="post">
							<table>
								<tr>
									<td>Supplier ID</td>
									<td width="1px">:</td>
									<td>
										<div style="display:none"><input id="hidden2" name="hidden2" class="easyui-textbox" style="width:90px"></div>
										<input id="supl_id" name="supl_id" class="easyui-textbox" style="width:90px" data-options="validType:'length[10,10]'" disabled>
										<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" plain="true" onclick="lov2()"></a>
									</td>
								</tr>
								<tr>
									<td>Nama</td>
									<td width="1px">:</td>
									<td>
										<input id="supl_name2" name="supl_name2" class="easyui-textbox" data-options="required:true">
									</td>
								</tr>
								<tr>
									<td>Supplier Tipe</td>
									<td width="1px">:</td>
									<td>
										<div style="display:none"><input id="supl_type2" name="supl_type2" class="easyui-textbox" style="width:60px" editable="false"></div>
										<input id="type2" name="type2" class="easyui-combobox" validType="inList['#type2']" data-options="
											data:cust_type,
											valueField:'label',
											textField:'label',
											onSelect:function(rec){
												$('#supl_type2').textbox('setValue',rec.value)
											},
											required:true">
									</td>
								</tr>
								<tr>
									<td>Status</td>
									<td width="1px">:</td>
									<td>
										<input id="supl_status2" name="supl_status2" class="easyui-combobox" validType="inList['#supl_status2']" data-options="
											data:stats,
											valueField:'value',
											textField:'label'">
									</td>
								</tr>
								<tr>
									<td colspan="3" align="right">
										<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm2()">Submit</a>
										<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="reset()">Reset</a>
									</td>
								</tr>
							</table>
						</form>
					</td>
					<td width="60%" valign="top">
						<table id="dg3" title="Rekening" class="easyui-edatagrid" style="height:auto" data-options="
								autoRowHeight:false,
								toolbar:'#toolbar3',
								striped:true,
								rownumbers:true,
								singleSelect:true">
							<thead>
								<tr>
									<th field="rek_bank" width="100px" editor="{type:'combobox',options:{
										url:'bank.php',
										valueField:'bank_id',
										textField:'bank_name',
										required:true}}">ID</th>
									<th field="bank_name" width="60px">Bank</th>
									<th field="rek_no" width="150px" editor="{type:'textbox',options:{required:true}}">No Rekening</th>
									<th field="rek_name" width="250px" editor="{type:'textbox',options:{required:true}}">Nama Rekening</th>
								</tr>
							</thead>
						</table>
						<div id="toolbar3">
							<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="add2()">Add</a>
							<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-save' plain='true' onclick="javascript:$('#dg3').edatagrid('saveRow')">Save</a>
							<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg3').edatagrid('cancelRow')">Cancel</a>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<!-- LOV -->
	<div id="dlg" class="easyui-dialog" style="width:790px;height:400px;padding:10px 20px" data-options="
			closed:true,
			modal:true">
		<table id="dg" class="easyui-datagrid" style="height:340px" data-options="
				singleSelect:true,
				url:'customer.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar',
				view:bufferview,
				pageSize:50,
				remoteFilter:true">
			<thead>
				<tr>
					<th field="cust_id" width="100px">ID</th>
					<th field="supl_name" width="200px">Nama</th>
					<th field="cust_ktp" width="140px">KTP</th>
					<th field="cust_owner" width="200px">Pemilik</th>
					<th field="supl_status" width="70px">Status</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-cancel' plain='true' onclick="remove()">Delete</a>
		</div>
	</div>
	<!-- LOV2 -->
	<div id="dlg2" class="easyui-dialog" style="width:440px;height:400px;padding:10px 20px" closed="true" modal="true">
		<table id="dg4" class="easyui-datagrid" style="height:340px" data-options="
				singleSelect:true,
				url:'customer2.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar4',
				view:bufferview">
			<thead>
				<tr>
					<th field="supl_id" width="100px">ID</th>
					<th field="supl_name" width="200px">Nama</th>
					<th field="supl_status" width="70px">Status</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar4">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil2()">Ambil</a>
		</div>
	</div>

	<script type="text/javascript">
		//combobox data
		var cust_type = [{
			label: 'INDIVIDU',
			value: 'I'
		}, {
			label: 'COMPANY',
			value: 'C'
		}, {
			label: 'PEDAGANG',
			value: 'T'
		}];
		var category = [{
			label: 'BUYER',
			value: 'C'
		}, {
			label: 'SUPPLIER',
			value: 'S'
		}];
		var stats = [{
			label: 'ACTIVE',
			value: 'Y'
		}, {
			label: 'INACTIVE',
			value: 'N'
		}];

		$(function() {
			$('#dg').datagrid('enableFilter');
			$('#dg4').datagrid('enableFilter');
		});

		//save
		function submitForm() {
			var id = $('#hidden').textbox('getValue');
			if (id == "") {
				$.post("check_ktp.php", {
						outlet_location: id
					},
					function(result) {
						if (result == 1) {
							if (confirm('KTP sudah terdaftar\napakah anda ingin tetap memasukan customer ini?') == true) {
								$('#fm').form('submit', {
									url: 'save.php',
									onSubmit: function(param) {
										return $(this).form('validate');
									},
									success: function(result) {
										var result = eval('(' + result + ')');
										if (result.errorMsg) {
											$.messager.show({
												title: 'Error',
												msg: result.errorMsg
											});
										} else {
											alert(result.success);
											$('#cust_id').textbox('setValue', result.id);
											$('#hidden').textbox('setValue', result.id);
											$('#dg').datagrid('reload');
											$('#dg2').edatagrid({
												url: 'bank_account.php?id=' + result.id,
												saveUrl: 'save_rek.php?id=' + result.id,
												updateUrl: 'update_rek.php',
												onSuccess: function(index, row) {
													alert(row.success);
												}
											});
										}
									}
								});
							} else {
								$('#cust_ktp').textbox('clear');
								$('#cust_ktp').textbox('textbox').focus();
							}
						} else {
							$('#fm').form('submit', {
								url: 'save.php',
								onSubmit: function(param) {
									return $(this).form('validate');
								},
								success: function(result) {
									var result = eval('(' + result + ')');
									if (result.errorMsg) {
										$.messager.show({
											title: 'Error',
											msg: result.errorMsg
										});
									} else {
										alert(result.success);
										$('#cust_id').textbox('setValue', result.id);
										$('#hidden').textbox('setValue', result.id);
										$('#dg').datagrid('reload');
										$('#dg2').edatagrid({
											url: 'bank_account.php?id=' + result.id,
											saveUrl: 'save_rek.php?id=' + result.id,
											updateUrl: 'update_rek.php',
											onSuccess: function(index, row) {
												alert(row.success);
											}
										});
									}
								}
							});
						}
					}
				)
			} else {
				$('#fm').form('submit', {
					url: 'update.php',
					onSubmit: function(param) {
						return $(this).form('validate');
					},
					success: function(result) {
						var result = eval('(' + result + ')');
						if (result.errorMsg) {
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
		}

		function submitForm2() {
			var id = $('#hidden2').textbox('getValue');
			if (id == "") {
				var url = 'save2.php';
			} else {
				var url = 'update2.php';
			}
			$('#ff').form('submit', {
				url: url,
				onSubmit: function(param) {
					return $(this).form('validate');
				},
				success: function(result) {
					var result = eval('(' + result + ')');
					if (result.errorMsg) {
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(result.success);
						$('#supl_id').textbox('setValue', result.id);
						$('#hidden2').textbox('setValue', result.id);
						$('#dg4').datagrid('reload');
						$('#dg3').edatagrid({
							url: 'bank_account.php?id=' + result.id,
							saveUrl: 'save_rek.php?id=' + result.id,
							updateUrl: 'update_rek.php',
							onSuccess: function(index, row) {
								alert(row.success);
							}
						});
					}
				}
			});
		}

		//clear form
		function reset() {
			location.reload();
		}

		//auto show combobox
		$(function() {
			$('#type').combobox('textbox').focus(function() {
				$('#type').combobox('showPanel');
			});
		});

		//lov
		function lov() {
			$('#dlg').dialog('open').dialog('setTitle', 'List of Value');
		}

		function lov2() {
			$('#dlg2').dialog('open').dialog('setTitle', 'List of Value');
		}

		//add lov
		function ambil() {
			var row = $('#dg').datagrid('getSelected');
			if (row) {
				$('#hidden').textbox('setValue', row.cust_id);
				$('#cust_id').textbox('setValue', row.cust_id);
				$('#supl_name').textbox('setValue', row.supl_name);
				$('#cust_ktp').textbox('setValue', row.cust_ktp);
				$('#cust_owner').textbox('setValue', row.cust_owner);
				$('#cust_address').textbox('setValue', row.cust_address);
				$('#province_id').textbox('setValue', row.province_id);
				$('#province_name').textbox('setValue', row.province_name);
				$('#regency_id').textbox('setValue', row.regency_id);
				$('#regency_name').textbox('setValue', row.regency_name);
				$('#hp1').textbox('setValue', row.cust_hp);
				$('#hp2').textbox('setValue', row.cust_hp2);
				$('#supl_type').textbox('setValue', row.supl_type);
				$('#type').textbox('setValue', row.type);
				$('#supl_status').textbox('setValue', row.supl_status);
				$('#cust_id').textbox('disable');
				$('#dlg').dialog('close');
				$('#dg2').edatagrid({
					url: 'bank_account.php?id=' + row.cust_id,
					saveUrl: 'save_rek.php?id=' + row.cust_id,
					updateUrl: 'update_rek.php',
					onSuccess: function(index, row) {
						alert(row.success);
					}
				});
			} else {
				alert('Silahkan pilih ID terlebih dahulu.');
			}
		}
		// remove
		function remove() {
			var row = $('#dg').datagrid('getSelected');
			if (row) {
				$.messager.confirm('Confirm', 'Are you sure you want to delete this?', function(r) {
					if (r) {
						$.post('delete.php', {
							id: row.cust_id
						}, function(result) {
							if (result.success) {
								alert(result.success);
								$('#dg').datagrid('reload');
							} else {
								$.messager.show({ // show error message
									title: 'Error',
									msg: result.errorMsg
								});
							}
						}, 'json');
					}
				});
			}
		}

		function ambil2() {
			var row = $('#dg4').datagrid('getSelected');
			if (row) {
				$('#hidden2').textbox('setValue', row.supl_id);
				$('#supl_id').textbox('setValue', row.supl_id);
				$('#supl_name2').textbox('setValue', row.supl_name);
				$('#supl_type2').textbox('setValue', row.supl_type);
				$('#type2').textbox('setValue', row.type);
				$('#supl_status2').textbox('setValue', row.supl_status);
				$('#supl_id').textbox('disable');
				$('#dlg2').dialog('close');
				$('#dg3').edatagrid({
					url: 'bank_account.php?id=' + row.supl_id,
					saveUrl: 'save_rek.php?id=' + row.supl_id,
					updateUrl: 'update_rek.php',
					onSuccess: function(index, row) {
						alert(row.success);
					}
				});
			} else {
				alert('Silahkan pilih ID terlebih dahulu.');
			}
		}

		//add rekening
		function add() {
			var id = $('#hidden').textbox('getValue');
			if (id == "") {
				alert('Silahkan pilih id terlebih dahulu');
			} else {
				$('#dg2').edatagrid('addRow');
			}
		}

		function add2() {
			var id = $('#hidden2').textbox('getValue');
			if (id == "") {
				alert('Silahkan pilih id terlebih dahulu');
			} else {
				$('#dg3').edatagrid('addRow');
			}
		}

		function next() {
			var category = $('#category').combobox('getValue');
			if (category == 'C') {
				$('#tt').tabs('enableTab', 1);
				$('#tt').tabs('select', 1);
				$('#tt').tabs('disableTab', 0);
			}
			if (category == 'S') {
				$('#tt').tabs('enableTab', 2);
				$('#tt').tabs('select', 2);
				$('#tt').tabs('disableTab', 0);
			}

		}

		// validasi easyui combobox
		$.extend($.fn.validatebox.defaults.rules, {
			inList: {
				validator: function(value, param) {
					var c = $(param[0]);
					var opts = c.combobox('options');
					var data = c.combobox('getData');
					var exists = false;
					for (var i = 0; i < data.length; i++) {
						if (value == data[i][opts.textField]) {
							exists = true;
							break;
						}
					}
					return exists;
				},
				message: 'invalid value.'
			}
		});
	</script>
</body>

</html>