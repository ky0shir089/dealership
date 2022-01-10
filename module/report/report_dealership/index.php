<!DOCTYPE html>
<html>
<head>
	<title>Report Dealership</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var outlet = <?= $_SESSION['outlet'] ?>;
			var out_name = '<?= $_SESSION['out_name'] ?>';
			if(outlet != '10000'){
				$('#kode_titik').textbox('setValue', outlet);
				$('#cabang').combobox('setValue', out_name);
				$('#cabang').combobox('readonly', true);
			}
		});
				
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
		
		// combobox data
		var report = [{label: 'REPORT DEALERSHIP',value: 'RD'},{label: 'REPORT BUYER',value: 'RS'},{label: 'REPORT RV',value: 'RV'},{label: 'REPORT INVOICE',value: 'RI'}];
		
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
		
		// download
		function submitForm(){
			var report = $('#id_report').textbox('getValue');
			if(report == 'RD'){
				var url = 'report_dealership.php';
			}
			if(report == 'RS'){
				var url = 'report_buyer.php';
			}
			if(report == 'RV'){
				var url = 'report_rv.php';
			}
			if(report == 'RI'){
				var url = 'report_invoice.php';
			}
			$('#ff').form('submit', {
				url: url,
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
					}
				}
			});
		}
	</script>
</head>
<body>      
	<form id="ff" method="post">
		<table cellpadding="5">
			<tr>
				<td>Report:</td>
				<td>
					<div style="display:none"><input id="id_report" name="id_report" class="easyui-textbox" style="width:30px" editable="false"></div>
					<input id="report" name="report" class="easyui-combobox" validType="inList['#report']" data-options="
							data:report,
							valueField: 'value',
							textField: 'label',
							onSelect: function(rec){
								$('#id_report').textbox('setValue',rec.value);
							},
							required:true"></input>
				</td>
			</tr>
			<tr>
				<td>Tanggal:</td>
				<td>
					<input id="start" name="start" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true" style="border:1px solid #ccc; width:100px"> s/d
					<input id="end" name="end" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true" style="border:1px solid #ccc; width:100px">
				</td>
			</tr>
			<tr>
				<td>Cabang:</td>
				<td>
					<input id="kode_titik" name="kode_titik" class="easyui-textbox" style="width:50px" editable="false">
					<input id="cabang" name="cabang_bmr" class="easyui-combobox" style="width:300px" data-options="
							valueField: 'nama_titik',
							textField: 'nama_titik',
							url: 'outlet.php',
							onSelect: function(rec){
								$('#kode_titik').textbox('setValue',rec.kode_titik);
							}"></input>
				</td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="submitForm()">Submit</a>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>