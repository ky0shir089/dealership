<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<meta name="viewport" content="initial-scale=0.5, maximum-scale=1.0, user-scalable=no">
	<title>View SPUK</title>
</head>
<body>
	<div class="easyui-panel" fit="true" border="false">
		<form id="fm" method="post">
			<table width="100%">
				<tr>
					<td width="30%">
						<table>
							<tr>
								<td>No SPUK</td>
								<td width="1px">:</td>
								<td>
									<input id="spuk_id" name="spuk_id" class="easyui-textbox" style="width:140px" editable="false">
								</td>
							</tr>
							<tr>
								<td>Tanggal</td>
								<td width="1px">:</td>
								<td>
									<input id="spuk_date" name="spuk_date" class="easyui-textbox" style="width:80px" editable="false">
								</td>
							</tr>
							<tr>
								<td>Tipe</td>
								<td width="1px">:</td>
								<td>
									<input id="type" name="type" class="easyui-textbox" editable="false">
								</td>
							</tr>
							<tr>
								<td>Buyer</td>
								<td width="1px">:</td>
								<td>
									<input id="supl_id" name="spuk_cust" class="easyui-textbox" style="width:90px" editable="false">
									<input id="supl_name" name="supl_name" class="easyui-textbox" style="width:200px" editable="false">
								</td>
							</tr>
							<tr>
								<td>Scheme</td>
								<td width="1px">:</td>
								<td>
									<input id="scheme_id" name="scheme_id" class="easyui-textbox" style="width:90px" editable="false">
									<input id="scheme_amount" name="scheme_amount" class="easyui-numberbox" style="width:90px" editable="false" data-options="groupSeparator:','">
								</td>
							</tr>
							<tr>
								<td>Status</td>
								<td width="1px">:</td>
								<td>
									<input id="spuk_status" name="spuk_status" class="easyui-textbox" style="width:80px" editable="false">
									<div style="display:none"><input id="reason2" name="reason" class="easyui-textbox" editable="false"></div>
								</td>
							</tr>
							<tr style="display:none">
								<td>Data</td>
								<td width="1px">:</td>
								<td>
									<input id="unit" name="spuk_jml_unit" class="easyui-numberbox" style="width:90px" editable="false">
									<input id="hutang" name="spuk_total_hutang" class="easyui-numberbox" style="width:90px" editable="false">
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
					<td width="20%">
						<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton c8" data-options="iconCls:'icon-ok'" onclick="approve()">Approve</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c8" data-options="iconCls:'icon-cancel'" onclick="reject()">Reject</a>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<table id="dg_list_unit" title="List Unit" class="easyui-datagrid" style="height:350px"
							data-options="
								autoRowHeight:false,
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
									<th field="scheme_amount" align="right" formatter="numberFormat">Scheme</th>
									<th field="spuk_dtl_total" align="right" formatter="numberFormat">Subtotal</th>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
			</table>
			<!-- Dlg Reject-->
			<div id="dlg_reject" class="easyui-dialog" style="width:300px;height:210px;padding:10px 20px"
				closed="true" buttons="#dlg-buttons" modal="true">
				<input id="reason" class="easyui-textbox" multiline="true" style="width:100%;height:100px" required="true">
			</div>
			<div id="dlg-buttons">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="submit_reject()" style="width:90px">Submit</a>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		// load form
		$(function(){
			$('#fm').form('load','view_form.php?id=<?= $_REQUEST['id']; ?>');
			$('#fm').form({
				onLoadSuccess: function(data){
					$('#dg_list_unit').datagrid('options').url = 'view_list_unit.php?id='+data.spuk_id;
					$('#dg_rv').datagrid('options').url = 'view_rv_detail.php?id='+data.spuk_id;
					$('#dg_list_unit, #dg_rv').datagrid('reload');
				}
			});
		});
		
		// submit
		function approve(){
			$('#btnSubmit').linkbutton('disable');
			$('#fm').form('submit', {
				url: 'pdo_approve_pln.php',
				success:function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(result.success);
						window.location.href='index.php';
					}
				}
			});
		}
		
		function reject(){
			$('#dlg_reject').dialog('open').dialog('setTitle','Reject Reason');
		}
		
		function submit_reject(){
			var reason = $('#reason').textbox('getValue');
			$('#reason2').textbox('setValue', reason);
			$('#fm').form('submit', {
				url: 'reject_pln.php',
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
						window.location.href='index.php';
					}
				}
			});
		}
		
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
	</script>
</body>
</html>