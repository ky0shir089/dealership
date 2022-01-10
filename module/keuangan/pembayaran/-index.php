<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Pembayaran</title>
	<script type="text/javascript">
		//save
		function submitForm(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				if(row.type_trx == 'TRX02'){
					var url = 'save.php';
				} else {
					var url = 'save_tagihan.php';
				}
				$('#btnSubmit').linkbutton('disable');
				$('#fm').form('submit', {
					url: url,
					onSubmit: function(param){
						param.data = JSON.stringify($('#dg').datagrid('getSelections'));
						return $(this).form('enableValidation').form('validate');
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
							$('#pv_no').textbox('setValue', result.pv_no);
							$('#dg').datagrid('options').url = 'lov_pv.php?id='+result.pv_no;
							$('#dg, #dg2').datagrid('reload');
						}
					}
				});
			}
		}
		
		function reset(){
			location.reload()
		}
		
		//lov
		function lov(){
			$(function(){
				$('#dg2').datagrid('enableFilter');
			});
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		
		//add lov
		function ambil(){
			var row = $('#dg2').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.pv_no);
				$('#pv_no').textbox('setValue', row.pv_no);
				$('#pv_paid_date').textbox('setValue', row.pv_paid_date);
				$('#pv_desc').textbox('setValue', row.pv_desc);
				$('#bank_name').textbox('setValue', row.bank_name);
				$('#rekout_name').textbox('setValue', row.rekout_name);
				$('#pv_bank_rek').combobox('setValue',row.rekout_no);
				$('#pv_bank_rek').combobox('readonly',true);
				$('#btnSubmit').linkbutton('disable');
				$('#dg').datagrid('options').url = 'lov_pv.php?id='+row.pv_no;
				$('#dg').datagrid('reload');
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih PV terlebih dahulu.')
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
			$('#pv_bank_rek').combobox('textbox').focus(function(){
				$('#pv_bank_rek').combobox('showPanel');
			});
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
		
		function dtl(value,row,index){
			var url = 'view_inv.php?id='+row.pv_proses_id;
			if(row.type_trx != 'TRX02'){
				return '<a target="_blank" href="' + url + '"><img src="../../../images/more.png"></a>';
			}
		}
	</script>
</head>
<body>
	<form id="fm" method="post">
		<table width="100%">
			<tr>
				<td>No PV</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
					<input id="pv_no" name="pv_no" class="easyui-textbox" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="pv_paid_date" name="pv_paid_date" class="easyui-textbox" style="width:80px" value="<?= date('Y-m-d'); ?>" editable="false">
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="pv_desc" name="pv_desc" class="easyui-textbox" style="width:300px" required="true">
				</td>
			</tr>
			<tr>
				<td>Kode Bank</td>
				<td width="1px">:</td>
				<td>
					<input id="bank_name" name="bank_name" class="easyui-textbox" style="width:70px" editable="false">
					<input id="rekout_name" name="rekout_name" class="easyui-textbox" editable="false">
					<input id="pv_bank_rek" name="pv_bank_rek" class="easyui-combobox" validType="inList['#pv_bank_rek']" data-options="
						url:'bank_account.php',
						valueField:'rekout_no',
						textField:'rekout_no',
						onSelect:function(rec){
							$('#bank_name').textbox('setValue',rec.bank_name);
							$('#rekout_name').textbox('setValue',rec.rekout_name);
							$('#rekout_segment').textbox('setValue',rec.rekout_segment);
						},
						required:true">
					<div style="display:none"><input id="rekout_segment" name="rekout_segment" class="easyui-textbox" editable="false"></div>
				</td>
			</tr>
			<tr>
				<td colspan=3>
					<table id="dg" class="easyui-datagrid" style="height:340px" 
						data-options="
							singleSelect:true,
							url:'list_tagihan.php',
							striped:true,
							autoRowHeight:false,
							toolbar:'#toolbar2',
							view:bufferview,
							pageSize:50">
						<thead>
							<tr>
								<th field="ck" checkbox="true"></th>
								<th field="pv_outlet" width="50px">Outlet</th>
								<th field="nama_titik" width="200px">Outlet Name</th>
								<th field="type_trx" width="60px">Type Trx</th>
								<th field="pv_proses_id" width="150px">No Pelunasan</th>
								<th field="utj_no_paket" width="150px">No Paket</th>
								<th field="supl_name" width="100px">Nama Supplier</th>
								<th field="bank_name" width="80px">Bank</th>
								<th field="pv_paid_rek" width="110px">No Rekening</th>
								<th field="pv_amount" width="90px" align="right" formatter="numberFormat">Amount</th>
								<th field="action" width="50px" formatter="dtl">action</th>
							</tr>
						</thead>
					</table>
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
	<div id="dlg" class="easyui-dialog" style="width:720px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg2" class="easyui-datagrid" style="height:340px" 
			data-options="
				singleSelect:true,
				url:'lov.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar',
				view:bufferview,
				pageSize:50,
				remoteFilter:true">
			<thead>
				<tr>
					<th field="pv_no" width="150px">No PV</th>
					<th field="pv_desc" width="300px">Bayar Kepada</th>
					<th field="pv_amount" width="90px" align="right" formatter="numberFormat">Amount</th>
					<th field="pv_paid_date" width="90px">Tgl Bayar</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
		</div>
	</div>
</body>
</html>