<script
	src="https://code.jquery.com/jquery-3.4.1.min.js"
	integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	crossorigin="anonymous"></script>
<script type="text/javascript" src="<?= path; ?>/assets/jquery-easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?= path; ?>/assets/jquery-easyui/jquery.easyui.mobile.js"></script>
<script type="text/javascript" src="<?= path; ?>/js/datagrid-filter.js"></script>
<script type="text/javascript" src="<?= path; ?>/js/jquery.edatagrid.js"></script>
<script type="text/javascript" src="<?= path; ?>/js/datagrid-detailview.js"></script>
<script type="text/javascript" src="<?= path; ?>/js/datagrid-bufferview.js"></script>
<script type="text/javascript" src="<?= path; ?>/js/datagrid-scrollview.js"></script>
<script type="text/javascript">
	// validate password
	$.extend($.fn.validatebox.defaults.rules, {
		equals: {
			validator: function(value,param){
				return value == $(param[0]).val();
			},
			message: 'Field do not match.'
		},
		minLength: {
			validator: function(value, param){
				return value.length >= param[0];
			},
			message: 'Please enter at least {0} characters.'
		},
		maxLength: {
			validator: function(value, param){
				return value.length <= param[0];
			},
			message: 'Max {0} characters.'
		},
		age: {
			validator: function(value,param){
				return value >= param[0];
			},
			message: 'Umur Harus > 17 Tahun'
		},
		notEquals: {
			validator: function(value,param){
				return value != $(param[0]).val();
			},
			message: 'Telpon Tidak Boleh Sama.'
		}
	});
		
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
		
	// date format easyui
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
	
	// date format indo
	function dateFormat(date){
		var monthNames = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL","AUG", "SEP", "OCT","NOV", "DEC"];
		var n = new Date(date);
		var d = n.getDate();
		var m = n.getMonth();
		var y = n.getFullYear();
		return (d<10?('0'+d):d)+'-'+monthNames[m]+'-'+y;
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