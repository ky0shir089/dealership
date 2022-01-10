<!DOCTYPE html>
<html>
<head>
	<?php include ("../../../header.php"); ?>
	<title>Invoice</title>
	<script type="text/javascript">
		$(function(){
			$('#dg_lov, #dg_referensi').datagrid('enableFilter');
		});
		
		// lov
		function lov(){
			$('#dlg').dialog('open').dialog('setTitle','List of Value');
		}
		
		function ambil(){
			var row = $('#dg_lov').datagrid('getSelected');
			if (row){
				$('#hidden').textbox('setValue', row.gl_no);
				$('#gl_no').textbox('setValue', row.gl_no);
				$('#gl_date').textbox('setValue', row.gl_date);
				$('#gl_desc').textbox('setValue', row.gl_desc);
				$('#gl_create_by').textbox('setValue', row.gl_create_by);
				$('#ff').form('load','form.php?id='+row.gl_no);
				$('#gl_date').datebox('readonly', true);
				$('#gl_desc').textbox('readonly', true);
				$('#nama_titik').combobox('readonly', true);
				$('#jc_type').combobox('readonly', true);
				$('#coa_description').combobox('readonly', true);
				$('#nama_titik2').combobox('readonly', true);
				$('#jc_type2').combobox('readonly', true);
				$('#coa_description2').combobox('readonly', true);
				$('#search').linkbutton('disable');
				$('#search2').linkbutton('disable');
				$('#btnSubmit').linkbutton('disable');
				$('#dlg').dialog('close');
			}
		}
		
		// lov_referensi
		function lov_ref(){
			$('#dlg_referensi').dialog('open').dialog('setTitle','List of Value');
			$('#baris').textbox('setValue', 1);
		}
		
		function lov_ref2(){
			$('#dlg_referensi').dialog('open').dialog('setTitle','List of Value');
			$('#baris').textbox('setValue', 2);
		}
		
		function ambil_referensi(){
			var row = $('#dg_referensi').datagrid('getSelected');
			var baris = $('#baris').textbox('getValue');
			if (row){
				if(baris == 1){
					$('#invhdr_reff_no').textbox('setValue', row.rv_no);
					$('#invhdr_reff_amount').textbox('setValue', row.rv_amount);
				} else {
					$('#invhdr_reff_no2').textbox('setValue', row.rv_no);
					$('#invhdr_reff_amount2').textbox('setValue', row.rv_amount);
				}
				$('#dg_referensi').datagrid('options').url = 'referensi.php?id='+row.rv_no;
				$('#dg_referensi').datagrid('reload');
				$('#dlg_referensi').dialog('close');
			}
		}
		
		//combobox data
		var jc_type = [{label: 'BANK',value: 'BANK'},{label: 'LAINNYA',value: 'LAINNYA'}];

		function addRow(){
			var row = $('#row').numberbox('getValue');
			var index = $('#index').numberbox('getValue');
			if(row == 0){
				index = 0;
				row = $('#row').numberbox('setValue',parseInt(row)+1);
			} else {
				$('#row').numberbox('setValue',parseInt(row)+1);
				index = $('#index').numberbox('setValue',parseInt(index)+1);
			}
			$('#dg').edatagrid('addRow');
			$('#dg').edatagrid('checkAll');
		}
		
		// submit
		function submitForm(){
			var gl_dr = parseInt($('#gl_dr').numberbox('getValue'));
			var gl_cr = parseInt($('#gl_cr').numberbox('getValue'));
			var gl_dr2 = parseInt($('#gl_dr2').numberbox('getValue'));
			var gl_cr2 = parseInt($('#gl_cr2').numberbox('getValue'));
			var sum_dr = gl_dr + +gl_dr2;
			var sum_cr = gl_cr + +gl_cr2;
			if(sum_dr!=sum_cr){
				alert('Jurnal Unbalance');
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
							$('#hidden').textbox('setValue', result.jc_no);
							$('#gl_no').textbox('setValue', result.jc_no);
							$('#gl_create_by').textbox('setValue', result.create_by);
							$('#gl_date').datebox('readonly', true);
							$('#gl_desc').textbox('readonly', true);
							$('#nama_titik').combobox('readonly', true);
							$('#jc_type').combobox('readonly', true);
							$('#coa_description').combobox('readonly', true);
							$('#nama_titik2').combobox('readonly', true);
							$('#jc_type2').combobox('readonly', true);
							$('#coa_description2').combobox('readonly', true);
							$('#btnSubmit').linkbutton('disable');
						}
					}
				});
			}
		}
		
		function reset(){
			location.reload();
		}
	
		// formatter
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
	</script>
</head>
<body>
	<form id="ff" method="post">
		<table>
			<tr>
				<td>No Jurnal</td>
				<td width="1px">:</td>
				<td>
					<div style="display:none"><input id="hidden" name="hidden" class="easyui-textbox" style="width:60px"></div>
					<input id="gl_no" name="gl_no" class="easyui-textbox" disabled>
					<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov()"></a>
				</td>
			</tr>
			<tr>
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="gl_date" name="gl_date" class="easyui-datebox" style="width:100px" data-options="formatter:myformatter,parser:myparser,required:true">
				</td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td width="1px">:</td>
				<td>
					<input id="gl_desc" name="gl_desc" class="easyui-textbox" style="width:300px" required="true">
				</td>
			</tr>
			<tr>
				<td>Koreksi by</td>
				<td width="1px">:</td>
				<td>
					<input id="gl_create_by" name="gl_create_by" class="easyui-textbox" style="width:60px" editable="false">
					<div style="display:none">
						<input id="row" name="row" class="easyui-numberbox" style="width:20px" value=0 editable="false">
						<input id="index" name="index" class="easyui-numberbox" style="width:20px" value=0 editable="false">
					</div>
				</td>
			</tr>
			<tr>
				<td colspan=3>
					<table border=1 style="border-collapse:collapse">
						<thead>
							<tr>
								<th>Outlet</th>
								<th>Nama Outlet</th>
								<th>Tipe</th>
								<th>Code Trx</th>
								<th>Nama Akun</th>
								<th>No Reff</th>
								<th>Debit</th>
								<th>Credit</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input id="kode_titik" name="kode_titik" class="easyui-textbox" style="width:50px" editable="false">
								</td>
								<td>
									<input id="nama_titik" name="nama_titik" class="easyui-combobox" style="width:200px" validType="inList['#nama_titik']" data-options="url:'outlet.php',
									valueField:'nama_titik',
									textField:'nama_titik',
									onSelect:function(rec){
										$('#kode_titik').textbox('setValue',rec.kode_titik)
									},
									required:true">
								</td>
								<td>
									<input id="jc_type" name="jc_type" class="easyui-combobox" style="width:90px" validType="inList['#jc_type']" data-options="data:jc_type,
									valueField:'value',
									textField:'label',
									onSelect:function(rec){
										var url = 'type.php?id='+rec.value;
										$('#coa_code').textbox('clear');
										$('#coa_description').combobox('clear');
										$('#invhdr_reff_no').textbox('clear');
										$('#search_lov').css('display','none');
										$('#coa_description').combobox('reload',url);
										if(rec.value == 'LAINNYA'){
											$('#search_lov').css('display','inline');
										}
									},
									required:true">
								</td>
								<td>
									<input id="coa_code" name="coa_code" class="easyui-textbox" style="width:80px" editable="false">
								</td>
								<td>
									<input id="coa_description" name="coa_description" class="easyui-combobox" style="width:230px" data-options="
										valueField:'coa_description',
										textField:'coa_description',
										onSelect:function(rec){
											$('#coa_code').textbox('setValue',rec.coa_code);
										},
										required:true">
								</td>
								<td>
									<input id="invhdr_reff_no" name="invhdr_reff_no" class="easyui-textbox" style="width:130px" editable="false">
									<div style="display:none"><input id="invhdr_reff_amount" name="invhdr_reff_amount" class="easyui-textbox" style="width:130px" editable="false"></div>
									<div id="search_lov" style="display:none"><a href="javascript:void(0)" id="search" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov_ref()"></a></div>
								</td>
								<td>
									<input id="gl_dr" name="gl_dr" class="easyui-numberbox" style="width:80px" data-options="groupSeparator:',',required:true">
								</td>
								<td>
									<input id="gl_cr" name="gl_cr" class="easyui-numberbox" style="width:80px" data-options="groupSeparator:',',required:true">
								</td>
							</tr>
							<tr>
								<td>
									<input id="kode_titik2" name="kode_titik2" class="easyui-textbox" style="width:50px" editable="false">
								</td>
								<td>
									<input id="nama_titik2" name="nama_titik2" class="easyui-combobox" style="width:200px" validType="inList['#nama_titik']" data-options="url:'outlet.php',
									valueField:'nama_titik',
									textField:'nama_titik',
									onSelect:function(rec){
										$('#kode_titik2').textbox('setValue',rec.kode_titik)
									},
									required:true">
								</td>
								<td>
									<input id="jc_type2" name="jc_type2" class="easyui-combobox" style="width:90px" validType="inList['#jc_type2']" data-options="data:jc_type,
									valueField:'value',
									textField:'label',
									onSelect:function(rec){
										var url = 'type.php?id='+rec.value;
										$('#coa_code2').textbox('clear');
										$('#coa_description2').combobox('clear');
										$('#invhdr_reff_no2').textbox('clear');
										$('#search_lov2').css('display','none');
										$('#coa_description2').combobox('reload',url);
										if(rec.value == 'LAINNYA'){
											$('#search_lov2').css('display','inline');
										}
									},
									required:true">
								</td>
								<td>
									<input id="coa_code2" name="coa_code2" class="easyui-textbox" style="width:80px" editable="false">
								</td>
								<td>
									<input id="coa_description2" name="coa_description2" class="easyui-combobox" style="width:230px" data-options="
										valueField:'coa_description',
										textField:'coa_description',
										onSelect:function(rec){
											$('#coa_code2').textbox('setValue',rec.coa_code);
										},
										required:true">
								</td>
								<td>
									<input id="invhdr_reff_no2" name="invhdr_reff_no2" class="easyui-textbox" style="width:130px" editable="false">
									<div style="display:none"><input id="invhdr_reff_amount2" name="invhdr_reff_amount2" class="easyui-textbox" style="width:130px" editable="false"></div>
									<div id="search_lov2" style="display:none"><a href="javascript:void(0)" id="search2" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="lov_ref2()"></a></div>
								</td>
								<td>
									<input id="gl_dr2" name="gl_dr2" class="easyui-numberbox" style="width:80px" data-options="groupSeparator:',',required:true">
								</td>
								<td>
									<input id="gl_cr2" name="gl_cr2" class="easyui-numberbox" style="width:80px" data-options="groupSeparator:',',required:true">
								</td>
							</tr>
						</tbody>
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
	<div id="dlg" class="easyui-dialog" style="width:620px;height:400px;padding:10px 20px"
		closed="true" modal="true">
		<table id="dg_lov" class="easyui-datagrid" style="height:340px" 
			data-options="
				singleSelect:true,
				url:'lov.php',
				striped:true,
				autoRowHeight:false,
				toolbar:'#toolbar2',
				view:bufferview">
			<thead>
				<tr>
					<th field="gl_no" width="150px">No JC</th>
					<th field="gl_date" width="100px">Tanggal</th>
					<th field="gl_desc" width="300px">Keterangan</th>
				</tr>
			</thead>
		</table>
		<div id="toolbar2">
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-add' plain='true' onclick="ambil()">Ambil</a>
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
			<input id="baris" name="baris" class="easyui-textbox" style="width:20px" editable="false">
		</div>
	</div>
</body>
</html>