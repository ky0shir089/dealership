<!DOCTYPE html>
<html>
<head>
	<title>Report Bank</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
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

		// download
		function submitForm(){
			$('#ff').form('submit', {
				url: 'report_bank.php',
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
				<td>Tanggal</td>
				<td width="1px">:</td>
				<td>
					<input id="start" name="start" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" style="border:1px solid #ccc; width:100px"> s/d
					<input id="end" name="end" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" style="border:1px solid #ccc; width:100px">
				</td>
			</tr>
			<tr>
				<td>Kode Bank</td>
				<td width="1px">:</td>
				<td>
					<input id="bank_name" name="bank_name" class="easyui-textbox" style="width:70px" editable="false">
					<input id="rekout_name" name="rekout_name" class="easyui-textbox" editable="false">
					<input id="bank_acctno" name="bank_acctno" class="easyui-combobox" validType="inList['#bank_acctno']" data-options="
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
				<td colspan=3 align="center">
					<a href="javascript:void(0)" id="btnSubmit" class="easyui-linkbutton" data-options="iconCls:'icon-save'" onclick="submitForm()">Submit</a>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>