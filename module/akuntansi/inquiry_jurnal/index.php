<!DOCTYPE html>
<html>
<head>
	<title>Inquiry Jurnal</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
		$(function(){
			var dg = $('#dg').datagrid();
		});
		
		//toolbar
		function doSearch(){
			$('#dg').datagrid('load',{
				start: $('#start').val(),
				end: $('#end').val(),
				proses: $('#proses').val()
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
	<table id="dg" fit="true"
		data-options="
			url:'load_data.php',
			autoRowHeight:false,
			toolbar:'#toolbar',
			striped:true,
			rownumbers:true,
			singleSelect:true,
			view:bufferview">
		<thead data-options="frozen:true">
			<tr>
				<th field="gl_no" width="150px">No Jurnal</th>
			</tr>
		</thead>
		<thead>
			<tr>
				<th field="gl_date" width="90px">Tanggal</th>
				<th field="gl_segment2" width="90px">Code Trx</th>
				<th field="coa_description" width="310px">Akun</th>
				<th field="gl_desc" width="600px">Keterangan</th>
				<th field="gl_dr" align="right" formatter="numberFormat" width="90px">Debet</th>
				<th field="gl_cr" align="right" formatter="numberFormat" width="90px">Credit</th>
			</tr>
		</thead>
	</table>
	
	<div id="toolbar" style="padding:3px">
		<span>Date:</span>
		<input id="start" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" style="border:1px solid #ccc">
		<span>S/D:</span>
		<input id="end" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" style="border:1px solid #ccc">
		<span>No:</span>
		<input id="proses" class="easyui-textbox" style="border:1px solid #ccc">
		<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Search</a>
	</div>
</body>
</html>