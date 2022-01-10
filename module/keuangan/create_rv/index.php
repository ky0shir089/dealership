<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Penerimaan Uang</title>
	<script type="text/javascript">
		//combobox data
		/* var type_trx = [{label: 'LAIN-LAIN',value: 'TRX01'}];
		var code_trx = [{label: 'TITIPAN LAIN-LAIN',value: '3310201'},{label: 'INT. A/R',value: '1510401'},{label: 'HUTANG BMR GROUP',value: '4110101'},{label: 'BANK TO BANK',value: '1510101'},{label: 'PENDAPATAN BUNGA BANK',value: '8110201'}]; */
		
		//save
		function submitForm(){
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
						$('#rv_no').textbox('setValue', result.rv_no);
						$('#dg').datagrid('reload');
						$('#btnSubmit').linkbutton('disable');
					}
				}
			});
		}
		
		function reset(){
			location.reload()
		}
		
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
				$('#hidden').textbox('setValue', row.rv_no);
				$('#rv_no').textbox('setValue', row.rv_no);
				$('#rv_received_date').textbox('setValue', row.rv_received_date);
				$('#rv_mst_code').textbox('setValue', row.rv_mst_code);
				$('#type_trx').combobox('setValue', row.rvmst_desc);
				$('#rv_received_from').textbox('setValue', row.rv_received_from);
				$('#bank_name').textbox('setValue',row.bank_name);
				$('#rekout_name').textbox('setValue',row.rekout_name);
				$('#rv_bank_rek').combobox('setValue', row.rv_bank_rek);
				$('#rv_segment2').textbox('setValue', row.rv_segment2);
				$('#coa_description').combobox('setValue', row.coa_description);
				$('#rv_amount').numberbox('setValue', row.rv_start);
				$('#rv_no').textbox('disable');
				$('#type_trx').combobox('readonly',true);
				$('#rv_received_from').textbox('readonly',true);
				$('#rv_bank_rek').combobox('readonly',true);
				$('#coa_description').combobox('readonly',true);
				$('#rv_amount').textbox('readonly',true);
				$('#btnSubmit').linkbutton('disable');
				$('#dlg').dialog('close');
			} else {
				alert('Silahkan pilih RV terlebih dahulu.')
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
			$('#type_trx').combobox('textbox').focus(function(){
				$('#type_trx').combobox('showPanel');
			});
			$('#coa_description').combobox('textbox').focus(function(){
				$('#coa_description').combobox('showPanel');
			});
			$('#rv_bank_rek').combobox('textbox').focus(function(){
				$('#rv_bank_rek').combobox('showPanel');
			});
		});
		
		// formatter
		function numberFormat(val,row){
			return val.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
		}
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
	</script>
</head>
<body>
	<form id="fm" method="post">
		<table>
			<tr>
				<td>No RV</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
					<input id="rv_no" name="rv_no" class="easyui-textbox" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_received_date" name="rv_received_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px">
				</td>
			</tr>
			<tr>
				<td>Type Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_mst_code" name="rv_mst_code" class="easyui-textbox" style="width:60px" editable="false">
					<input id="type_trx" name="type_trx" class="easyui-combobox" validType="inList['#type_trx']" data-options="
						url:'type_trx.php',
						valueField:'rvmst_desc',
						textField:'rvmst_desc',
						onSelect:function(rec){
							$('#rv_mst_code').textbox('setValue',rec.rvmst_code);
							$('#rv_segment2').textbox('clear');
							$('#coa_description').combobox('clear');
							var url = 'code_trx.php?id='+rec.rvmst_code;
							$('#coa_description').combobox('reload',url);
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_received_from" name="rv_received_from" class="easyui-textbox" style="width:400px" required="true">
				</td>
			</tr>
			<tr>
				<td>Kode Bank</td>
				<td width="1px">:</td>
				<td>
					<input id="bank_name" name="bank_name" class="easyui-textbox" style="width:60px" editable="false">
					<input id="rekout_name" name="rekout_name" class="easyui-textbox" editable="false">
					<input id="rv_bank_rek" name="rv_bank_rek" class="easyui-combobox" validType="inList['#rv_bank_rek']" data-options="
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
				<td>Code Trx</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_segment2" name="rv_segment2" class="easyui-textbox" style="width:60px" editable="false">
					<input id="coa_description" name="coa_description" class="easyui-combobox" style="width:200px" data-options="
						valueField:'coa_description',
						textField:'coa_description',
						onSelect:function(rec){
							$('#rv_segment2').textbox('setValue',rec.rvdtl_code);
						},
						required:true">
				</td>
			</tr>
			<tr>
				<td>Amount</td>
				<td width="1px">:</td>
				<td>
					<input id="rv_amount" name="rv_start" class="easyui-numberbox" data-options="groupSeparator:',',required:true">
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" iconCls="icon-save" onclick="submitForm()">Submit</a>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="reset()">Clear</a>
				</td>
			</tr>
		</table>
		<!-- LOV -->
		<div id="dlg" class="easyui-dialog" style="width:720px;height:400px;padding:10px 20px"
			closed="true" modal="true">
			<table id="dg" class="easyui-datagrid" style="height:340px" 
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
						<th field="rv_no" width="150px">No RV</th>
						<th field="rv_received_from" width="400px">Keterangan</th>
						<th field="rv_start" width="90px" align="right" formatter="numberFormat">Amount</th>
					</tr>
				</thead>
			</table>
			<div id="toolbar">
				<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
			</div>
		</div>
	</form>
</body>
</html>