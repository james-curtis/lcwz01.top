<?php

include 'header.tpl.php';
?>
	<div class="body_box">
        <div class="main_box">
            <div class="hd">
            	<div class="bz a5"><div class="jj_bg"></div></div>
            </div>
            <div class="ct">
            	<div class="bg_t"></div>
                <div class="clr">
                    <div class="l"></div>
                    <div class="ct_box">
                     <div class="nr">
                  	<div id="installmessage" >正在准备安装 ...<br /></div>
                     </div>
                    </div>
                </div>
                <div class="bg_b"></div>
            </div>
            <div class="btn_box"><a href="javascript:history.go(-1);" class="s_btn pre">上一步</a><a href="javascript:void(0);"  onClick="$('#install').submit();return false;" class="x_btn pre" id="finish">安装中..</a></div>            
        </div>
    </div>
    <div id="hiddenop"></div>
	<form id="install" action="install.php?" method="post">
	<input type="hidden" name="step" value="6">
	</form>
</body>
<script language="JavaScript">
<!--
$().ready(function() {
reloads();
})

var setting =  new Array();
setting['admin'] = '后台管理主模块安装成功......';

var dbhost = '<?php 
echo $dbhost;
?>
';
var dbuser = '<?php 
echo $dbuser;
?>
';
var dbpw = '<?php 
echo $dbpw;
?>
';
var dbname = '<?php 
echo $dbname;
?>
';

var username = '<?php 
echo $username;
?>
';
var password = '<?php 
echo $password;
?>
';

function reloads() {
	$.ajax({
		   type: "POST",
		   url: 'install.php',
		   data: "step=installmodule&module=admin&dbhost="+dbhost+"&dbuser="+dbuser+"&dbpw="+dbpw+"&dbname="+dbname+"&username="+username+"&password="+password+"&sid="+Math.random()*5,
		   success: function(msg){
			   if(msg==1) {
				   alert('指定的数据库不存在，系统也无法创建，请先通过其他方式建立好数据库！');
			   } else if(msg==2) {
				   $('#installmessage').append("<font color='#ff0000'>/install/main/alizhizhuchi.sql 数据库文件不存在</font>");
			   } else if(msg.length>20) {
				   $('#installmessage').append("<font color='#ff0000'>错误信息：</font>"+msg);
			   } else {
				   $('#installmessage').append(setting['admin'] + msg + "<img src='images/correct.gif' /><br>");

						$('#installmessage').append("<font color='yellow'>安装完成</font>");
						$('#finish').removeClass('pre');
						$('#finish').html('安装完成');
//						setTimeout("$('#install').submit();",1000);

					document.getElementById('installmessage').scrollTop = document.getElementById('installmessage').scrollHeight;
			   }	
		}	
		});
}
//-->
</script>
</html>