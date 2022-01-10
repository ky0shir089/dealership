<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Invoice</title>
	<script type="text/javascript">
		//combobox data
		var bayar = [{label: 'SUPPLIER',value: 'S'},{label: 'CUSTOMERS',value: 'C'}];
		
		$(function(){
			$('#dg, #dg_buyer, #dg_referensi').datagrid('enableFilter');
			$('#type_trx').combobox('textbox').focus(function(){
				$('#type_trx').combobox('showPanel');
			});
			$('#coa_description').combobox('textbox').focus(function(){
				$('#coa_description').combobox('showPanel');
			});
			$('#rek_no').combobox('textbox').focus(function(){
				$('#rek_no').combobox('showPanel');
			});
		});
		
		//save
		function submitForm(){
			var ref_amount = parseInt($('#rv_amount').numberbox('getValue'));
			var amount = parseInt($('#invhdr_amount').numberbox('getValue'));
			var type_trx = $('#invhdr_mst_code').textbox('getValue');
			if(type_trx == 'TRX03'){
				$('#dlg_rv').dialog('open').dialog('setTitle','List of Value');
			} else {
				if(amount > ref_amount){
					alert('tidak bisa');
				} else {
					$('#ff').form('submit', {
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
								$('#invhdr_status').textbox('setValue', 'REQUEST');
								$('#btnSubmit').linkbutton('disable');
								$('#hidden').textbox('setValue', result.inv_no);
								$('#invhdr_no').textbox('setValue', result.inv_no);
								$('#dg').datagrid('reload');
							}
						}
					});
				}
			}
			
		}
		
		function reset(){
			location.reload()
		}
		
		//lov
		function lov(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		
		//add lov
		function ambil(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.invhdr_no);
				$('#invhdr_no').textbox('setValue', row.invhdr_no);
				$('#invhdr_mst_code').textbox('setValue', row.invhdr_mst_code);
				$('#type_trx').combobox('setValue', row.type_trx);
				$('#invhdr_created').textbox('setValue', row.invhdr_created);
				$('#invhdr_status').textbox('setValue', row.invhdr_status);
				$('#supl_type').textbox('setValue',row.supl_type);
				$('#invhdr_supplier').textbox('setValue',row.invhdr_supplier);
				$('#supl_name').textbox('setValue', row.supl_name);
				$('#bank_name').textbox('setValue', row.bank_name);
				$('#rek_name').textbox('setValue', row.rek_name);
				$('#invhdr_rek_no').combobox('setValue', row.invhdr_rek_no);
				$('#invhdr_segment2').textbox('setValue', row.invhdr_segment2);
				$('#coa_description').combobox('setValue', row.coa_description);
				$('#invhdr_reff_no').textbox('setValue', row.invhdr_reff_no);
				$('#rv_amount').numberbox('setValue', row.rv_amount);
				$('#invhdr_desc').textbox('setValue', row.invhdr_desc);
				$('#invhdr_amount').numberbox('setValue', row.invhdr_amount);
				$('#type_trx').combobox('readonly',true);
				$('#invhdr_rek_no').textbox('readonly',true);
				$('#coa_description').combobox('readonly',true);
				$('#lov_ref').css('display','none');
				$('#search_lov').css('display','none');
				$('#list_rv').css('display','none');
				if(row.invhdr_mst_code == 'TRX03'){
					$('#dg_rv').datagrid('options').url = 'rv_detail.php?id='+row.invhdr_no;
					$('#dg_rv').datagrid('reload');
					$('#list_rv').css('display','table-cell');
				}
				if(row.invhdr_mst_code == 'TRX01' || row.invhdr_mst_code == 'TRX06'){
					$('#lov_ref').css('display','table-row');
				}
				$('#btnSubmit').linkbutton('disable');
				$('#dlg').dialog('close');
			}
		}
		
		// lov_buyer
		function lov_buyer(){
			$('#dlg_buyer').dialog('open').dialog('setTitle','List of Value');
		}
		
		function ambil_buyer(){
			var row = $('#dg_buyer').datagrid('getSelected');
			var url = 'bank_account.php?id='+row.supl_id;
			if (row){
				$('#invhdr_supplier').textbox('setValue', row.supl_id);
				$('#supl_name').textbox('setValue', row.supl_name);
				$('#invhdr_rek_no').combobox('reload', url);
				$('#dlg_buyer').dialog('close');
			} else {
				alert('Silahkan pilih buyer terlebih dahulu.');
			}
		}
		
		// lov_referensi
		function lov_ref(){
			$('#dlg_referensi').dialog('open').dialog('setTitle','List of Value');
		}
		
		function ambil_referensi(){
			var row = $('#dg_referensi').datagrid('getSelected');
			if (row){
				$('#invhdr_reff_no').textbox('setValue', row.rv_no);
				$('#rv_amount').numberbox('setValue', row.rv_amount);
				$('#dlg_referensi').dialog('close');
			}
		}
		
		// save_rv
		function save_rv(){
			var sum_rv = 0;
			var rows_rv = $('#dg_list_rv').datagrid('getSelections');
			for(var i=0; i<rows_rv.length; i++){
				sum_rv += parseInt(rows_rv[i].rv_scheme);
            }
			
			var subtotal = parseInt($('#invhdr_amount').numberbox('getValue'));
			
			if(subtotal > sum_rv){
				alert('Nominal RV kurang');
			} else {
				if(confirm('YAKIN AKAN DI SUBMIT?') == true){
					$('#btnSubmit').linkbutton('disable');
					$('#ff').form('submit', {
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
								$('#invhdr_status').textbox('setValue', 'REQUEST');
								$('#btnSubmit').linkbutton('disable');
								$('#hidden').textbox('setValue', result.inv_no);
								$('#invhdr_no').textbox('setValue', result.inv_no);
								$('#dg_rv').datagrid('options').url = 'rv_detail.php?id='+result.inv_no;
								$('#dg, #dg_rv').datagrid('reload');
								$('#list_rv').css('display','table-cell');
								$('#dlg_rv').dialog('close');
							}
						}
					});
				} 
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
		
		// onclick change footer
		function footer_rv(){			
			var sum = 0;
			var rows = $('#dg_list_rv').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				sum += parseInt(rows[i].rv_scheme);
            }
			$('#dg_list_rv').datagrid('reloadFooter', [{
				rv_no:'Total',
				rv_scheme:sum
			}]);
		}
	</script>
</head>
<body>
	<form id="ff" method="post">
		<table border=0>
			<tr>
				<td>No Invoice</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
					<input id="invhdr_no" name="invhdr_no" class="easyui-textbox" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
				<td rowspan=9 valign="top" style="display:none" id="list_rv">
					<table id="dg_rv" class="easyui-datagrid" style="width:300px;height:290px"
						data-options="
							striped:true,
							singleSelect:true,
							autoRowHeight:false,
							showFooter:true">
						<thead>
							<tr>
								<th field="used_rv_no" width="150px">No RV</th>
								<th field="used_rv_amount" width="90px" align="right" formatter="numberFormat">RV Amount</th>
							</tr>
						</thead>
					</table>
				</td>
			</tr>
			<tr>
				<td>Type Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_mst_code" name="invhdr_mst_code" class="easyui-textbox" style="width:60px" data-options="editable:false">
					<input id="type_trx" name="invmst_desc" class="easyui-combobox" style="width:220px" validType="inList['#type_trx']" data-options="
						url:'type_trx.php',
						valueField:'invmst_desc',
						textField:'invmst_desc',
						onSelect:function(rec){
							$('#invhdr_mst_code').textbox('setValue',rec.invmst_code);
							$('#invhdr_segment2').textbox('clear');
							$('#coa_description').combobox('clear');
							var url = 'code_trx.php?id='+rec.invmst_code;
							$('#coa_description').combobox('reload',url);
							$('#lov_ref').css('display','none');
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_created" name="invhdr_created" class="easyui-textbox" style="width:80px" value="<?= date('Y-m-d'); ?>" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_status" name="invhdr_status" class="easyui-textbox" style="width:80px" value="NEW" data-options="editable:false">
				</td>
			</tr>
			<tr>
				<td>Bayar Ke</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="bayar1" name="bayar1" class="easyui-textbox" style="width:60px" data-options="editable:false"></div>
					<input id="supl_type" name="supl_type" class="easyui-combobox" validType="inList['#supl_type']" data-options="
						data:bayar,
						valueField:'label',
						textField:'label',
						onSelect:function(rec){
							$('#bayar1').textbox('setValue',rec.value);
							$('#invhdr_supplier').textbox('clear');
							$('#supl_name').textbox('clear');
							$('#lov_buyer').css('display','inline');
							var url = 'supplier.php?id='+rec.value;
							$('#dg_buyer').datagrid('options').url = url;
							$('#dg_buyer').datagrid('reload');
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td>Nama</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_supplier" name="invhdr_supplier" class="easyui-textbox" style="width:60px" data-options="editable:false">
					<input id="supl_name" name="supl_name" class="easyui-textbox" style="width:200px" data-options="editable:false">
					<div id="lov_buyer" style="display:none"><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov_buyer()"></a></div>
				</td>
			</tr>
			<tr>
				<td>Kode Bank</td>
				<td width="1px">:</td>
				<td>
					<input id="bank_name" name="bank_name" class="easyui-textbox" style="width:70px" data-options="editable:false">
					<input id="rek_name" name="rek_name" class="easyui-textbox" style="width:250px" data-options="editable:false">
					<input id="invhdr_rek_no" name="invhdr_rek_no" class="easyui-combobox" style="width:120px" data-options="
						valueField:'rek_no',
						textField:'rek_no',
						onSelect:function(rec){
							$('#bank_name').textbox('setValue',rec.bank_name);
							$('#rek_name').textbox('setValue',rec.rek_name);
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td>Code Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_segment2" name="invhdr_segment2" class="easyui-textbox" style="width:60px" data-options="editable:false">
					<input id="coa_description" name="coa_description" class="easyui-combobox" style="width:200px" data-options="
						valueField:'coa_description',
						textField:'coa_description',
						onSelect:function(rec){
							$('#invhdr_segment2').textbox('setValue',rec.invdtl_code);
							var type_trx = $('#invhdr_mst_code').textbox('getValue');
						if(type_trx == 'TRX01' || type_trx == 'TRX06'){
								$('#lov_ref').css('display','table-row');
							}
						},
						required:true">
				</td>
			</tr>
			<tr id="lov_ref" style="display:none">
				<td>No Referensi</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_reff_no" name="invhdr_reff_no" class="easyui-textbox" style="width:130px" data-options="editable:false">
					<input id="rv_amount" name="rv_amount" class="easyui-numberbox" style="width:80px;text-align:right" data-options="groupSeparator:',',editable:false">
					<a href="javascript:void(0)" id="search_lov" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov_ref()"></a>
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_desc" name="invhdr_desc" class="easyui-textbox" style="width:300px" data-options="required:true">
				</td>
			</tr>
			<tr>
				<td>Amount</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_amount" name="invhdr_amount" style="width:80px;text-align:right" class="easyui-numberbox" data-options="groupSeparator:',',required:true">
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()">Submit</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="reset()">Reset</a>
				</td>
			</tr>
		</table>
	</form>
	<!-- LOV -->
	<div id="dlg" class="easyui-dialog" style="width:740px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg" class="easyui-datagrid" style="height:340px" 
			data-options="
				singleSelect:true,
				url:'lov.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar',
				view:bufferview">
			<thead>
				<tr>
					<th field="invhdr_no" width="150px">No Invoice</th>
					<th field="invhdr_mst_code" width="60px">Type Trx</th>
					<th field="invhdr_status" width="70px">Status</th>
					<th field="invhdr_created" width="90px">Tanggal</th>
					<th field="invhdr_supplier" width="100px">Kode Supl</th>
					<th field="supl_name" width="200px">Nama Supl</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
		</div>
	</div>
	<!-- LOV Buyer-->
	<div id="dlg_buyer" class="easyui-dialog" style="width:720px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_buyer" class="easyui-datagrid" style="height:340px" 
			data-options="
				singleSelect:true,
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar2',
				view:bufferview">
			<thead>
				<tr>
					<th field="supl_id" width="100px">ID</th>
					<th field="supl_name" width="200px">Nama</th>
					<th field="cust_ktp" width="140px">KTP</th>
					<th field="cust_owner" width="200px">Pemilik</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar2">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil_buyer()">Ambil</a>
		</div>
	</div>
	<!-- LOV Referensi-->
	<div id="dlg_referensi" class="easyui-dialog" style="width:380px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_referensi" class="easyui-datagrid" style="height:340px" 
			data-options="
				singleSelect:true,
				url:'referensi.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar3',
				view:bufferview">
			<thead>
				<tr>
					<th field="rv_no" width="200px">No RV</th>
					<th field="rv_amount" width="100px" formatter="numberFormat" align="right">Amount</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar3">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil_referensi()">Ambil</a>
		</div>
	</div>
	<!-- LOV RV -->
	<div id="dlg_rv" class="easyui-dialog" style="width:350px;height:400px;padding:10px 20px"
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
					<th field="rv_scheme" width="90px" align="right" formatter="numberFormat">RV Amount</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar4">
			<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="save_rv()">Submit</a>
		</div>
	</div>
</body>
</html>