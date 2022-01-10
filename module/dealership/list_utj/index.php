<!DOCTYPE html>
<html>
<head>
	<title>List UTJ</title>
	<?php include ("../../../header.php"); ?>
</head>
<body>
	<table id="dg"
	data-options="
		fit:true,
		border:false,
		url:'load_data.php',
		autoRowHeight:false,
		toolbar:'#toolbar',
		striped:true,
		pagination:true,
		rownumbers:true,
		pageSize:20,
		remoteFilter:true">
		<thead>
			<tr>
				<th field="ck" checkbox="true"></th>
				<th field="utj_id">ID</th>
				<th field="utj_name">Nama</th>
				<th field="utj_no_paket">No Paket</th>
				<th field="utj_no_contract">No Kontrak</th>
				<th field="utj_bpkb_name">Nama Kontrak</th>
				<th field="utj_nopol">No Polisi</th>
				<th field="utj_noka">No Rangka</th>
				<th field="utj_nosin">No Mesin</th>
				<th field="utj_type">Type</th>
				<th field="utj_stnk" align="center">STNK</th>
				<!--<th field="utj_tgl_stnk">Tgl STNK</th>-->
				<th field="utj_grade" align="center">Grade</th>
				<th field="utj_tahun">Tahun</th>
				<th field="utj_hutang_konsumen" align="right" formatter="numberFormat">Hutang Konsumen</th>
				<th field="utj_ct_date">Tgl CT</th>
				<th field="utj_rv_fif">RV FIF</th>
				<th field="utj_status" width="90px">Status</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-cancel' plain='true' onclick="remove()">Delete</a>
		<?php if($_SESSION['uid'] == 'admin' or $_SESSION['uid'] == '1054' or $_SESSION['uid'] == '1618' or $_SESSION['uid'] == '1631' or $_SESSION['uid'] == '1783'){ ?>
			<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-edit' plain='true' onclick="edit()">Edit</a>
		<?php } ?>
		<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-coupon' plain='true' onclick="rvfif()">RV FIF</a>
	</div>
	
	<!-- EDIT -->
	<div id="dlg" class="easyui-dialog" style="width:400px;height:500px;padding:10px 20px"
		data-options="
			closed:true,
			buttons:'#dlg-buttons',
			modal:true">
		<div class="ftitle">Unit Information</div>
		<form id="fm" method="post">
			<table>
				<tr>
					<td>Nama</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_name" data-options="required:true"></td>
				</tr>
				<tr>
					<td>No Paket</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_no_paket" data-options="required:true"></td>
				</tr>
				<tr>
					<td>No Kontrak</td>
					<td width="1px">:</td>
					<td><input class="easyui-numberbox" name="utj_no_contract" data-options="required:true"></td>
				</tr>
				<tr>
					<td>Nama Kontrak</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_bpkb_name" data-options="required:true"></td>
				</tr>
				<tr>
					<td>No Polisi</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_nopol" data-options="required:true"></td>
				</tr>
				<tr>
					<td>No Rangka</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_noka" data-options="required:true"></td>
				</tr>
				<tr>
					<td>No Mesin</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_nosin" data-options="required:true"></td>
				</tr>
				<tr>
					<td>Type</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_type" data-options="required:true"></td>
				</tr>
				<tr>
					<td>STNK</td>
					<td width="1px">:</td>
					<td><input class="easyui-combobox" id="utj_stnk" name="utj_stnk" validType="inList['#utj_stnk']" data-options="
							data:stnk,
							valueField:'value',
							textField:'label',
							required:true"></td>
				</tr>
				<tr>
					<td>Grade</td>
					<td width="1px">:</td>
					<td><input class="easyui-combobox" id="utj_grade" name="utj_grade" validType="inList['#utj_grade']" data-options="
							data:grade,
							valueField:'value',
							textField:'label',
							required:true"></td>
				</tr>
				<tr>
					<td>Tahun</td>
					<td width="1px">:</td>
					<td><input class="easyui-numberbox" name="utj_tahun" data-options="required:true"></td>
				</tr>
				<tr id="hbm">
					<td>Hutang Konsumen</td>
					<td width="1px">:</td>
					<td><input class="easyui-numberbox" name="utj_hutang_konsumen" data-options="groupSeparator:',',required:true"></td>
				</tr>
				<tr>
					<td>Tgl CT</td>
					<td width="1px">:</td>
					<td><input class="easyui-datebox" name="utj_ct_date" data-options="formatter:myformatter,parser:myparser,required:true"></td>
				</tr>
			</table>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	
	<!-- RV FIF -->
	<div id="dlg2" class="easyui-dialog" style="width:400px;height:170px;padding:10px 20px"
		data-options="
			closed:true,
			buttons:'#dlg-buttons2',
			modal:true">
		<div class="ftitle">Unit Information</div>
		<form id="ff" method="post">
			<table>
				<tr>
					<td>RV FIF</td>
					<td width="1px">:</td>
					<td><input class="easyui-textbox" name="utj_rv_fif" data-options="required:true"></td>
				</tr>
			</table>
		</form>
	</div>
	<div id="dlg-buttons2">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save2()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg2').dialog('close')" style="width:90px">Cancel</a>
	</div>
	
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg');
			dg.datagrid();
			dg.datagrid('enableFilter',[{
				field:'utj_status',
				type:'combobox',
				options:{
					panelHeight:'auto',
					data:[{value:'',text:'All'},{value:'N',text:'NEW',selected:true},{value:'D',text:'DRAFT'},{value:'R',text:'REQUEST'},{value:'A',text:'APPROVED'},{value:'C',text:'CANCEL'},{value:'P',text:'PAID'}],
					onChange:function(value){
						if (value == ''){
							dg.datagrid('removeFilterRule', 'utj_status');
						} else {
							dg.datagrid('addFilterRule', {
								field: 'utj_status',
								op: 'equal',
								value: value
							});
						}
						dg.datagrid('doFilter');
					},
					editable:false
				}
			}]);
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
		
		// remove
		function remove(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				if(row.utj_status != 'NEW'){
					alert('Hanya unit dengan status NEW yang bisa di hapus');
				} else {
					$.messager.confirm('Confirm','Are you sure you want to delete this?',function(r){
						if (r){
							$.post('delete.php',{id:JSON.stringify($('#dg').datagrid('getSelections'))},function(result){
								if (result.success){
									alert(result.success);
									$('#dg').datagrid('reload');
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
		}
		
		// edit
		function edit(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Edit Information');
				$('#fm').form('load',row);
				url = 'save.php?id='+row.utj_id;
				
				if(row.utj_status != "NEW"){
					$('#hbm').css('display','none');
				} else {
					$('#hbm').css('display','');
				}
			}
		}
		
		// rv fif
		function rvfif(){
			var row = $('#dg').datagrid('getSelected');
			if (row.utj_status == "PAID"){
				$('#dlg2').dialog('open').dialog('setTitle','Edit Information');
				$('#ff').form('load',row);
				url = 'rvfif.php?id='+row.utj_id;
			}
		}
		
		// save
		function save(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(result.success);
						$('#dlg').dialog('close');
						$('#dg').datagrid('reload');
					}
				}
			});
		}
		function save2(){
			$('#ff').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						alert(result.success);
						$('#dlg2').dialog('close');
						$('#dg').datagrid('reload');
					}
				}
			});
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
		
		var stnk = [{label: 'Y',value: 'Y'},{label: 'N',value: 'N'}];
		var grade = [{label: 'A',value: 'A'},{label: 'B',value: 'B'},{label: 'C',value: 'C'},{label: 'D',value: 'D'}];
		
		// validasi easyui combobox
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
</body>
</html>