<!DOCTYPE html>
<html>
<head>
	<title>List UTJ</title>
	<?php include ("../../../header.php"); ?>
	<script type="text/javascript">
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
		
		//toolbar
		function doSearch(){
			$('#dg').datagrid('load',{
				start: $('#start').val(),
				end: $('#end').val()
			});
		}
	</script>
</head>
<body>
	<table id="dg" class="easyui-datagrid" fit="true"
	data-options="
		url:'load_data.php',
		autoRowHeight:false,
		toolbar:'#toolbar',
		striped:true,
		pagination:true,
		rownumbers:true,
		singleSelect:true,
		pageSize:20,
		pageList:[20],
		remoteFilter:true">
		<thead>
			<tr>
				<th field="utj_created">Tgl Pengajuan</th>
				<th field="nama_titik">Nama Cabang</th>
				<th field="a">No Distribusi</th>
				<th field="utj_no_contract">No Kontrak</th>
				<th field="utj_type">Type</th>
				<th field="utj_tahun">Tahun</th>
				<th field="spuk_dtl_total" align="right" formatter="numberFormat">HJR</th>
				<th field="utj_hutang_konsumen" align="right" formatter="numberFormat">HBM</th>
				<th field="spuk_dtl_scheme" align="right" formatter="numberFormat">Profit</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar" style="padding:3px">
		<span>Date:</span>
		<input id="start" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" style="border:1px solid #ccc">
		<span>S/D:</span>
		<input id="end" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" style="border:1px solid #ccc">
		<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doSearch()">Search</a>
	</div>
</body>
</html>