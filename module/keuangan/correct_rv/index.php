<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Correct RV</title>
	<script type="text/javascript">
		//save
		function remove(){
			var row = $('#dg').datagrid('getSelected');
			$('#fm').form('submit', {
				url: 'delete.php',
				onSubmit: function(param){
					param.data = JSON.stringify($('#dg').datagrid('getSelections'));
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
						location.reload();
					}
				}
			});
		}
		
		function reset(){
			location.reload()
		}
		
		//lov
		function lov(){
			var rv_no = $('#rv_no').combobox('getValue');
			$('#dg').datagrid('options').url = 'rv_no.php?id='+rv_no;
			$('#dg').datagrid('reload');
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
	</script>
</head>
<body>
	<form id="fm" method="post">
		<table>
			<tr>
				<td>No RV</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_no" name="rv_no" class="easyui-combobox" validType="inList['#rv_no']" style="width:160px" data-options="
						url:'lov.php',
						valueField:'rv_no',
						textField:'rv_no',
						required:true">
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
		</table>
		<br>
		<table id="dg" class="easyui-datagrid" style="height:80px" 
			data-options="
				singleSelect:true,
				url:'rv_no.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar'">
			<thead>
				<tr>
					<th field="rv_received_date">Tanggal</th>
					<th field="rv_no">No RV</th>
					<th field="rv_received_from">Keterangan</th>
					<th field="bank_name">Bank</th>
					<th field="rv_bank_rek">No Rekening</th>
					<th field="rv_amount" align="right" formatter="numberFormat">Amount</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-cancel' plain='true' onclick="remove()">Delete</a>
		</div>
	</form>
</body>
</html>