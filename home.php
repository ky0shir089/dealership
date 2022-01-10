<html>
<head>
	<?php include "header.php"; $useragent = $_SERVER['HTTP_USER_AGENT']; ?>
	<title>DEALERSHIP</title>
	<script type="text/javascript">
		//menu
		function newPanel(title, url){
			<?php
				if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
			?>
			window.location.href=url;
			<?php } ?>
			var content = '<iframe scrolling="auto" frameborder="0" src="'+url+'" style="width:100%;height:100%;"></iframe>';
			$('#mainPanel').panel({
				title:title,
				content: content
			});
		}

		//dialog change password
		var url;
		function reset(){
			$('#dlg').dialog('open').dialog('setTitle','Change Password');
			$('#fm').form('clear');
			url = 'changepass2.php';
		}
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
						$('#dlg').dialog('close');
						alert(result.success);
					}
					
				}
			});
		}
		
		//dialog pindah outlet
		function pindah(){
			$('#dlg2').dialog('open').dialog('setTitle','Pindah Outlet');
			url = 'do_pindah.php';
		}
		function save2(){
			$('#ff').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					$('#dlg2').dialog('close');		// close the dialog
					alert(data);
					location.reload();
				}
			});
		}
		
		//validate password
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
			}
		});
		
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
	<!--<script>
		var myEvent = window.attachEvent || window.addEventListener;
		var chkevent = window.attachEvent ? 'onbeforeunload' : 'beforeunload'; /// make IE7, IE8 compitable

			myEvent(chkevent, function(e) { // For >=IE7, Chrome, Firefox
				var confirmationMessage = 'Are you sure to leave the page?';  // a space
				(e || window.event).returnValue = confirmationMessage;
				return confirmationMessage;
			});
	</script>-->
</head>
<body class="easyui-layout" fit="true">
	<!-- header layout -->
	<div data-options="region:'north'">
		<div class="north">
			<?php
				echo "<b>Username: </b><a href='javascript:void(0)' onclick='reset()'>".$_SESSION['username']."</a>";
				echo " | ";
				echo "<b>Outlet: </b><a href='javascript:void(0)' onclick='pindah()'>".$_SESSION['outlet']."</a>";
				echo " | ";
				echo "<a href='logout.php'>Logout</a> &nbsp;";
			?>
		</div>
	</div>
	<!-- menu layout -->
	<div data-options="region:'west',title:'MENU',collapsible:false" style="width:180px">
		<div id="aa" class="easyui-accordion" data-options="selected:true,multiple:true">
			<ul id="tt" class="easyui-tree" data-options="animate:true,lines:false">
				<li iconCls="icon-house"><span><a href='home.php'>Home</a></span></li>
			</ul>
				<?php
					$query = "select
						c.module_id,
						module_name,
						module_icon
					from 
						user_roles a,
						mst_rolemenus b,
						mst_menus c,
						mst_modules d
					where 
						a.role_id=b.role_id and
						b.menu_id=c.menu_id and
						c.module_id=d.module_id and
						a.user_id='$_SESSION[uid]'
					group by module_id";
					$result = mysqli_query($con,$query) or die(mysqli_error($con));
					while($data = mysqli_fetch_array($result)){
				?>
					<div title="<?php echo $data['module_name']; ?>" data-options="iconCls:'<?php echo $data['module_icon']; ?>'" style="overflow:auto;padding:10px;">
						<ul id="tt" class="easyui-tree" data-options="animate:true,lines:false">
							<?php
								$query2 = "SELECT
									a.role_id,
									menu_icon,
									menu_name,
									href,
									menu_page
								FROM 
									mst_rolemenus a,
									mst_menus b,
									user_roles c
								WHERE
									a.menu_id=b.menu_id AND
									a.rolemenu_sts=1 AND
									b.module_id='$data[module_id]' AND
									a.role_id=c.role_id AND
                                    c.user_id='$_SESSION[uid]'
								group by menu_name
								order by seq asc";
								$result2 = mysqli_query($con,$query2) or die(mysqli_error());
								while($data2 = mysqli_fetch_array($result2)){
							?>
							<li iconCls="<?php echo $data2['menu_icon']; ?>"><span><a href='<?php echo $data2['href']; ?>' onclick='<?php echo $data2['menu_page']; ?>'><?php echo $data2['menu_name']; ?></a></span></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
		</div>
	</div>
	<!-- main layout -->
	<div data-options="region:'center'">
		<div class="easyui-panel" fit="true" border="false" id="mainPanel">
			<div class="easyui-panel" title="Welcome" border="false" style="padding:20px;"> 
				<div style="margin-top:20px;">
					<?= "Welcome To The System, ".$_SESSION['username']."<br>"; ?>
				</div>
			</div>
		</div>
	</div>
	<!-- dialog layout -->
	<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons" modal="true">
		<div class="ftitle">Input Password</div>
		<form id="fm" method="post">
			<div class="fitem">
				<label>Old Password:</label>
				<input name="old" type="password" class="easyui-textbox" data-options="required:true,validType:'minLength[8]'"></input>
			</div>
			<div class="fitem">
				<label>New Password:</label>
				<input id="new" type="password" class="easyui-textbox" data-options="required:true,validType:'minLength[8]'"></input>
			</div>
			<div class="fitem">
				<label>Re-Type:</label>
				<input name="new" type="password" class="easyui-textbox" data-options="required:true" validType="equals['#new']"></input>
			</div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<!-- pindah outlet -->
	<div id="dlg2" class="easyui-dialog" style="width:450px;height:250px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons2" modal="true">
		<div class="ftitle">Pindah Outlet</div>
		<form id="ff" method="post">
			<div class="fitem">
				<label>Outlet Lama:</label>
				<input name="old1" class="easyui-textbox" style="width:60px" value="<?= $_SESSION['outlet']; ?>" editable="false">
				<input name="old2" class="easyui-textbox" style="width:200px" value="<?= $_SESSION['out_name']; ?>" editable="false">
			</div>
			<div class="fitem">
				<label>Outlet Baru:</label>
				<input id="new1" name="new1" class="easyui-textbox" style="width:60px" editable="false">
				<input id="new2" name="new2" class="easyui-combobox" style="width:200px" validType="inList['#new2']" data-options="
					url:'list_change_outlet.php',
					valueField:'nama_titik',
					textField:'nama_titik',
					onSelect:function(rec){
						$('#new1').textbox('setValue',rec.access_outlet);
					},
					required:true">
			</div>
		</form>
	</div>
	<div id="dlg-buttons2">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save2()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg2').dialog('close')" style="width:90px">Cancel</a>
	</div>
</body>
</html>