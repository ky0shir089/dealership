<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Create SPUK</title>
	<script type="text/javascript">
		// load form
		$(function(){
			$('#fm').form('load','form.php');
			$('#fm').form({
				onLoadSuccess: function(data){
					$('#dg_list_unit').datagrid('options').url = 'list_unit.php?id='+data.spuk_id;
					$('#dg_list_unit').datagrid('reload');
					$('#type').combobox('readonly', true);
					$('#supl_name').combobox('readonly', true);
					$('#scheme_amount').combobox('readonly',true);
				}
			});
			$('#dg_spuk, #dg_unit').datagrid('enableFilter');
		});
		
		// combobox data
		var cust_type = [{label: 'INDIVIDU',value: 'I'},{label: 'COMPANY',value: 'C'},{label: 'PEDAGANG',value: 'T'}];
		
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
		function formatItem(row){
            var s = row.scheme_amount.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            return s;
        }
		
		// lov
		function lov(){
			$('#dlg_spuk').dialog('open').dialog('setTitle','List of Value');
		}
		
		// load lov
		function ambil(){
			$('#fm').form('clear');
			var row = $('#dg_spuk').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.spuk_id);
				$('#spuk_id').textbox('setValue', row.spuk_id);
				$('#spuk_date').textbox('setValue', row.spuk_date);
				$('#supl_type').textbox('setValue', row.supl_type);
				$('#type').combobox('setValue', row.type);
				$('#supl_id').textbox('setValue', row.spuk_cust);
				$('#supl_name').textbox('setValue', row.supl_name);
				$('#scheme_id').textbox('setValue', row.scheme_id);
				$('#scheme_amount').textbox('setValue', row.scheme_amount);
				$('#spuk_status').textbox('setValue', row.spuk_status);
				if(row.spuk_status == 'REQUEST' || row.spuk_status == 'APPROVED' || row.spuk_status == 'CANCEL' || row.spuk_status == 'PAID' || row.spuk_status == 'REJECT'){
					$('#type').combobox('readonly', true);
					$('#supl_name').combobox('readonly', true);
					$('#scheme_amount').combobox('readonly',true);
					$('#btnAdd').linkbutton('disable');
					$('#btnDelete').linkbutton('disable');
					$('#btnReload').linkbutton('disable');
					$('#btnRV').linkbutton('disable');
				}
				if(row.spuk_status == 'SAVED'){
					$('#type').combobox('readonly', true);
					$('#supl_name').combobox('readonly', true);
					$('#scheme_amount').combobox('readonly',true);
					$('#btnAdd').linkbutton('disable');
					$('#btnDelete').linkbutton('disable');
					$('#btnReload').linkbutton('disable');
					$('#btnRV').linkbutton('enable');
				}
				if(row.spuk_status == 'NEW'){
					$('#btnAdd').linkbutton('enable');
					$('#btnDelete').linkbutton('enable');
					$('#btnReload').linkbutton('disable');
					$('#btnRV').linkbutton('enable');
				}
				$('#unit').numberbox('setValue', row.spuk_jml_unit);
				$('#scheme').numberbox('setValue', row.spuk_total_scheme);
				$('#subtotal').numberbox('setValue', row.spuk_subtotal);
				$('#dg_list_unit').datagrid('options').url = 'list_unit.php?id='+row.spuk_id;
				$('#dg_rv').datagrid('options').url = 'rv_detail.php?id='+row.spuk_id;
				$('#dg_list_unit, #dg_rv').datagrid('reload');
				$('#dlg_spuk').dialog('close');
			} else {
				alert('Silahkan pilih id terlebih dahulu.')
			}
		}
		
		// add unit
		function add(){
			var hidden = $('#hidden').textbox('getValue');
			var scheme = $('#scheme_id').textbox('getValue');
			if(hidden == ''){
				$('#fm').form('submit', {
					url: 'save_hdr.php',
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
							$('#hidden').textbox('setValue',result.spuk_id);
							$('#spuk_id').textbox('setValue',result.spuk_id);
							$('#type').combobox('readonly', true);
							$('#supl_name').combobox('readonly', true);
							$('#scheme_amount').combobox('readonly',true);
							$('#dlg_unit').dialog('open').dialog('setTitle','List of Value');
						}
					}
				});
			} else {
				$('#dlg_unit').dialog('open').dialog('setTitle','List of Value');
			}
		}
		
		// ambil unit
		function ambil_unit(){
			var unit = parseInt($('#unit').numberbox('getValue'));
			$('#fm').form('submit', {
				url: 'save_unit.php',
				onSubmit: function(param){
					param.data = JSON.stringify($('#dg_unit').datagrid('getSelections'));
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
						$('#dlg_unit').dialog('close');
						$('#dg_list_unit').datagrid('options').url = 'list_unit.php?id='+result.spuk_id;
						$('#dg_unit, #dg_list_unit, #dg_spuk').datagrid('reload');
						$('#unit').numberbox('setValue', unit + +1);
					}
				}
			});
		}
		
		// remove
		function remove(){
			var row = $('#dg_list_unit').datagrid('getSelected');
			var spuk_id = $('#hidden').textbox('getValue');
			var unit = parseInt($('#unit').numberbox('getValue'));
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to delete this?',function(r){
					if (r){
						$.post('delete.php',{id:row.spuk_dtl_utj},function(result){
							if (result.success){
								alert(result.success);
								$('#dg_list_unit').datagrid('options').url = 'list_unit.php?id='+spuk_id;
								$('#dg_list_unit, #dg_unit').datagrid('reload');
								$('#unit').numberbox('setValue', unit-1);
							} else {
								$.messager.show({	// show error message
									title: 'Error',
									msg: result.errorMsg
								});
							}
						},'json');
					}
				});
			}
		}
		
		// reload
		/* function reload(){
			$('#fm').form('submit', {
				url: 'reload.php',
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
						$('#dg_list_unit').datagrid('options').url = 'list_unit.php?id='+result.spuk_id;
						$('#dg_list_unit, #dg_spuk').datagrid('reload');
					}
				}
			});
		} */
		
		// lov rv
		function add_rv(){
			var unit = parseInt($('#unit').numberbox('getValue'));
			if(unit == 0){
				alert('Silahkan pilih unit terlebih dahulu');
			} else {
				if(confirm('APAKAH UNIT SUDAH SELESAI DITAMBAHKAN?') == true){
					$('#fm').form('submit', {
						url: 'save_fix.php',
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
								$('#spuk_status').textbox('setValue','SAVED');
								$('#subtotal').textbox('setValue', result.subtotal);
								$('#type').combobox('readonly',true);
								$('#supl_name').combobox('readonly',true);
								$('#scheme_amount').combobox('readonly',true);
								$('#btnAdd').linkbutton('disable');
								$('#btnDelete').linkbutton('disable');
								$('#btnReload').linkbutton('disable');
								$('#dlg_rv').dialog('open').dialog('setTitle','List of Value');
							}
						}
					});
				}
			}
		}
		
		// submit
		function submitForm(){
			var sum_rv = 0;
			var rows_rv = $('#dg_list_rv').datagrid('getSelections');
			for(var i=0; i<rows_rv.length; i++){
				sum_rv += parseInt(rows_rv[i].rv_amount);
            }
			
			var subtotal = parseInt($('#subtotal').numberbox('getValue'));
			
			if(sum_rv < subtotal){
				alert('Nominal RV kurang');
			} else {
				if(confirm('YAKIN AKAN DI SUBMIT?') == true){
					$('#btnSubmit').linkbutton('disable');
					//var spuk_id = $('#hidden').textbox('getValue');
					$('#fm').form('submit', {
						url: 'submit.php',
						onSubmit: function(param){
							param.data = JSON.stringify($('#dg_list_rv').datagrid('getSelections'));
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
								$('#spuk_status').textbox('setValue', 'REQUEST');
								$('#btnSubmit').linkbutton('disable');
								$('#btnRV').linkbutton('disable');
								$('#dg_spuk').datagrid('options').url = 'spuk.php';
								$('#dg_rv').datagrid('options').url = 'rv_detail.php?id='+result.spuk_id;
								$('#dg_spuk, #dg_rv').datagrid('reload');
								$('#dlg_rv').dialog('close');
							}
						}
					});
				} 
			}
		}
		
		// onclick change footer
		function footer_rv(){			
			var sum = 0;
			var rows = $('#dg_list_rv').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				sum += parseInt(rows[i].rv_amount);
            }
			$('#dg_list_rv').datagrid('reloadFooter', [{
				rv_no:'Total',
				rv_amount:sum
			}]);
		}
	</script>
</head>
<body>
<div class="easyui-panel" fit="true" border="false">
	<form id="fm" method="post">
		<table width="100%">
			<tr>
				<td width="75%">
					<table>
						<tr>
							<td>No SPUK</td>
							<td width="1px">:</td>
							<td>
								<div style="display:none"><input id="hidden" name="spuk_id" class="easyui-textbox" style="width:140px"></div>
								<input id="spuk_id" name="spuk_id" class="easyui-textbox" style="width:140px" disabled>
								<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
							</td>
						</tr>
						<tr>
							<td>Tanggal</td>
							<td width="1px">:</td>
							<td>
								<input id="spuk_date" name="spuk_date" class="easyui-textbox" value="<?= date('Y-m-d'); ?>" style="width:80px" editable="false">
							</td>
						</tr>
						<tr>
							<td>Tipe</td>
							<td width="1px">:</td>
							<td>
								<div style="display:none"><input id="supl_type" name="supl_type" class="easyui-textbox" style="width:60px" editable="false"></div>
								<input id="type" name="type" class="easyui-combobox" validType="inList['#type']" data-options="
									data:cust_type,
									valueField:'label',
									textField:'label',
									onSelect:function(rec){
										$('#supl_type').textbox('setValue',rec.value);
										var url = 'customer.php?id='+rec.value;
										$('#supl_id').textbox('clear');
										$('#supl_name').combobox('clear');
										$('#supl_name').combobox('reload',url);
									},
									required:true">
							</td>
						</tr>
						<tr>
							<td>Buyer</td>
							<td width="1px">:</td>
							<td>
								<input id="supl_id" name="supl_id" class="easyui-textbox" style="width:90px" editable="false">
								<input id="supl_name" name="supl_name" class="easyui-combobox" style="width:200px" data-options="
									valueField:'supl_name',
									textField:'supl_name',
									onSelect:function(rec){
										$('#supl_id').textbox('setValue',rec.supl_id);
										var hidden = $('#hidden').textbox('getValue');
									},
									required:true">
							</td>
						</tr>
						<tr>
							<td>Scheme</td>
							<td width="1px">:</td>
							<td>
								<input id="scheme_id" name="scheme_id" class="easyui-textbox" style="width:90px" editable="false">
								<input id="scheme_amount" name="scheme_amount" class="easyui-combobox" style="width:90px" data-options="
									url:'scheme.php',
									valueField:'scheme_amount',
									textField:'scheme_amount',
									onSelect:function(rec){
										$('#scheme_id').textbox('setValue',rec.scheme_id);
										var hidden = $('#hidden').textbox('getValue');
									},
									formatter: formatItem,
									required:true">
							</td>
						</tr>
						<tr>
							<td>Status</td>
							<td width="1px">:</td>
							<td>
								<input id="spuk_status" name="spuk_status" class="easyui-textbox" value="NEW" style="width:80px" editable="false">
							</td>
						</tr>
						<tr style="display:none">
							<td>Data</td>
							<td width="1px">:</td>
							<td>
								<input id="unit" name="spuk_jml_unit" class="easyui-numberbox" value=0 style="width:90px" editable="false">
								<input id="scheme" name="spuk_total_scheme" class="easyui-numberbox" style="width:90px" editable="false">
								<input id="subtotal" name="spuk_subtotal" class="easyui-numberbox" style="width:90px" editable="false">
							</td>
						</tr>
					</table>
				</td>
				<td width="25%" valign="top">
					<table id="dg_rv" class="easyui-datagrid" style="height:180px" 
						data-options="
							striped:true,
							singleSelect:true,
							autoRowHeight:false,
							showFooter:true">
						<thead>
							<tr>
								<th field="rrv_no_rv" width="150px">No RV</th>
								<th field="rv_amount" width="90px" align="right" formatter="numberFormat">RV Amount</th>
							</tr>
						</thead>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<table id="dg_list_unit" title="List Unit" class="easyui-datagrid" style="height:370px"
						data-options="
							autoRowHeight:false,
							toolbar:'#toolbar',
							striped:true,
							rownumbers:true,
							singleSelect:true,
							showFooter:true">
						<thead>
							<tr>
								<th field="spuk_dtl_utj">UTJ ID</th>
								<th field="utj_no_contract">No Contract</th>
								<th field="utj_nopol">No Polisi</th>
								<th field="utj_nosin">No Mesin</th>
								<th field="utj_type">Tipe</th>
								<th field="utj_tahun">Tahun</th>
								<th field="utj_hutang_konsumen" align="right" formatter="numberFormat">Hutang Konsumen</th>
								<th field="spuk_dtl_scheme" align="right" formatter="numberFormat">Scheme</th>
								<th field="spuk_dtl_total" align="right" formatter="numberFormat">Subtotal</th>
							</tr>
						</thead>
					</table>
					<div id="toolbar">
						<a href='javascript:void(0)' id="btnAdd" class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="add()">Add</a>
						<a href="javascript:void(0)" id="btnDelete" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="remove()">Delete</a>
						<!--<a href="javascript:void(0)" id="btnReload" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true" onclick="reload()">Reload</a>-->
						<a href="javascript:void(0)" id="btnRV" class="easyui-linkbutton" data-options="iconCls:'icon-money',plain:true" onclick="add_rv()">RV</a>
					</div>
				</td>
			</tr>
		</table>
		<!-- LOV UNIT-->
		<div id="dlg_unit" class="easyui-dialog" style="width:830px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg_unit" class="easyui-datagrid" style="height:330px" 
				data-options="
					singleSelect:true,
					url:'unit.php',
					striped:true,
					autoRowHeight:false,
					toolbar:'#toolbar3',
					view:bufferview">
				<thead>
					<tr>
						<th field="ck" checkbox="true"></th>
						<th field="utj_id" width="130px">UTJ ID</th>
						<th field="utj_no_contract" width="110px">No Contract</th>
						<th field="utj_nopol" width="80px">No Polisi</th>
						<th field="utj_nosin" width="110px">No Mesin</th>
						<th field="utj_type" width="120px">Tipe</th>
						<th field="utj_tahun" width="50px">Tahun</th>
						<th field="utj_hutang_konsumen" width="120px" align="right" formatter="numberFormat">Hutang Konsumen</th>
					</tr>
				</thead>
			</table>
			<div id="toolbar3">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil_unit()">Ambil</a>
			</div>
		</div>
	</form>
	<!-- LOV SPUK-->
	<div id="dlg_spuk" class="easyui-dialog" style="width:580px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_spuk" class="easyui-datagrid" style="height:330px" 
			data-options="
				singleSelect:true,
				url:'spuk.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar2',
				view:bufferview">
			<thead>
				<tr>
					<th field="spuk_id" width="160px">SPUK ID</th>
					<th field="spuk_date" width="90px">Tanggal</th>
					<th field="supl_name" width="200px">Nama Buyer</th>
					<th field="spuk_status" width="70px">Status</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar2">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
		</div>
	</div>
	<!-- LOV RV -->
	<div id="dlg_rv" class="easyui-dialog" style="width:330px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_list_rv" class="easyui-datagrid" style="height:330px" 
			data-options="
				url:'rv.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar4',
				showFooter:true,
				onSelect:footer_rv,
				onUnselect:footer_rv,
				onCheckAll:footer_rv,
				onUncheckAll:footer_rv">
			<thead>
				<tr>
					<th field="ck" checkbox="true"></th>
					<th field="rv_no" width="150px">No RV</th>
					<th field="rv_amount" width="90px" align="right" formatter="numberFormat">RV Amount</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar4">
			<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="submitForm()">Submit</a>
		</div>
	</div>
</div>
</body>
</html>