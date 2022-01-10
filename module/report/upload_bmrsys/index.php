<!DOCTYPE html>
<html>
<head>
	<title>Upload GL</title>
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
		
		// combobox data
		var report = [{label: 'UPLOAD',value: 'UP'}];
		
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
			$('#ff').form('submit', {
				url: 'upload_bmrsys.php',
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
				<td>Period:</td>
				<td>
					<input id="period_id" name="period_id" class="easyui-combobox" style="width:70px" validType="inList['#period_id']" 
					data-options="
						url:'period.php',
						valueField: 'period_id',
						textField: 'period_id',
						required:true,
						onSelect:function(rec){
							$('#period_month').textbox('setValue',rec.period_month);
							$('#period_year').textbox('setValue',rec.period_year);
							$('#period_end_date').textbox('setValue',rec.period_end_date);
						}"></input>
					<div style="display:none">
						<input id="period_month" name="period_month" class="easyui-textbox" style="width:40px" editable="false">
						<input id="period_year" name="period_year" class="easyui-textbox" style="width:30px" editable="false">
						<input id="period_end_date" name="period_end_date" class="easyui-textbox" style="width:80px" editable="false">
					</div>
				</td>
			</tr>
			<tr style="display:none">
				<td>Cabang:</td>
				<td>
					<input id="kode_titik" name="kode_titik" class="easyui-textbox" style="width:50px" editable="false">
					<input id="cabang" name="cabang_bmr" class="easyui-combobox" style="width:300px" validType="inList['#cabang']" data-options="
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