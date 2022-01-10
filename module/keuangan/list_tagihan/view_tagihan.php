<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>View Tagihan</title>
	<script type="text/javascript">
		// load form
		$(function(){
			$('#fm').form('load','view_form.php?id=<?= $_REQUEST['id']; ?>');
			$('#fm').form({
				onLoadSuccess: function(data){
					if(data.invhdr_mst_code == 'TRX05'){
						$('#dg_rv').datagrid('options').url = 'rv_detail.php?id='+data.invhdr_no;
						$('#dg_rv').datagrid('reload');
						$('#list_rv').css('display','table-cell');
					}
					if(data.invhdr_mst_code == 'TRX03'){
						$('#lov_ref').css('display','table-row');
					}
					if(data.invhdr_status == 'REQUEST'){
						$('#action').css('display','table-row');
					}
				}
			});
		});
		
		// submit
		function approve(){
			$('#btnSubmit').linkbutton('disable');
			$('#fm').form('submit', {
				url: 'approve_tagihan.php',
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
			$('#btnSubmit').linkbutton('disable');
			$('#fm').form('submit', {
				url: 'reject_tagihan.php',
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
</head>
<body>
	<form id="fm" method="post">
		<table border=0>
			<tr>
				<td>No Invoice</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_no" name="invhdr_no" class="easyui-textbox" editable="false">
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
					<input id="invhdr_mst_code" name="invhdr_mst_code" class="easyui-textbox" style="width:50px" editable="false">
					<input id="type_trx" name="type_trx" class="easyui-textbox" style="width:220px" editable="false">
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_created" name="invhdr_created" class="easyui-textbox" style="width:80px" editable="false">
				</td>
			</tr>
			<tr>
				<td>Status</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_status" name="invhdr_status" class="easyui-textbox" style="width:80px" value="REQUEST" editable="false">
				</td>
			</tr>
			<tr>
				<td>Bayar Ke</td>
				<td width="1px">:</td>
				<td>
					<input id="supl_type" name="supl_type" class="easyui-textbox" editable="false">
				</td>
			</tr>
			<tr>
				<td>Nama</td>
				<td width="1px">:</td>
				<td>
					<input id="supl_id" name="supl_id" class="easyui-textbox" style="width:60px" editable="false">
					<input id="supl_name" name="supl_name" class="easyui-textbox" style="width:200px" editable="false">
				</td>
			</tr>
			<tr>
				<td>Kode Bank</td>
				<td width="1px">:</td>
				<td>
					<input id="bank_name" name="bank_name" class="easyui-textbox" style="width:60px" editable="false">
					<input id="rek_name" name="rek_name" class="easyui-textbox" style="width:240px" editable="false">
					<input id="invhdr_rek_no" name="invhdr_rek_no" class="easyui-textbox" style="width:120px" editable="false">
				</td>
			</tr>
			<tr>
				<td>Code Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_segment2" name="invhdr_segment2" class="easyui-textbox" style="width:60px" editable="false">
					<input id="coa_description" name="coa_description" class="easyui-textbox" style="width:200px" editable="false">
				</td>
			</tr>
			<tr id="lov_ref" style="display:none">
				<td>No Referensi</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_reff_no" name="invhdr_reff_no" class="easyui-textbox" style="width:130px" editable="false">
					<input id="rv_amount" name="rv_amount" class="easyui-numberbox" style="width:80px;text-align:right" data-options="groupSeparator:','" editable="false">
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_desc" name="invhdr_desc" class="easyui-textbox" style="width:200px" editable="false">
				</td>
			</tr>
			<tr>
				<td>Amount</td>
				<td width="1px">:</td>
				<td>
					<input id="invhdr_amount" name="invhdr_amount" class="easyui-numberbox" style="width:80px;text-align:right" data-options="groupSeparator:','" editable="false">
				</td>
			</tr>
			<tr id="action" align="center" style="display:none">
				<td colspan=3>
					<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton c8" data-options="iconCls:'icon-ok'" onclick="approve()">Approve</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c8" data-options="iconCls:'icon-cancel'" onclick="reject()">Reject</a>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>