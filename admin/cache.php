<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : '';
$cachetime = isset($_POST['cachetime']) ? $_POST['cachetime'] : '';
$ok = isset($_POST['ok']) ? $_POST['ok'] : '';
if ($act == 'edit') {
	$mysqli->query("update config set cache='" . dataen($ok) . "',cachetime='" . dataen($cachetime) . "'");
	echo '<script>alert(\'修改成功\');self.location.href=\'cache.php\';</script>';
}
if ($act == 'del_all') {
	delFile("../cacheData");
	echo '<script>alert(\'更新成功\')</script>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>页面缓存-<?php 
echo SYSTEM_NAME;
?>
</title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
	<script>
		$(function(){
			$('.userbtn').click(function(){
				$('#loading').show();
			});
		})
	</script>
</head>
<body>
<div id="loading">
	<img src="img/load.gif">
</div>
	<div id="pageAll">
		<div class="pageTop">
			<div class="page">
				<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a href="cache.php">系统管理</a>&nbsp;-</span>&nbsp;页面缓存
			</div>
		</div>
		<div class="page cfD">
			<div style="line-height: 25px;color:blue;">
				开启缓存后:首页只固定TDK,内页除索引池调用、权重池调用、时间日期相管调用外其余内容不变化。<br>
				开启缓存将生成大量文件,小硬盘用户请时常关注硬盘使用量。<br>
			</div>
			<form action="?act=edit" method="post">
			<span>开启页面缓存:</span>
				<input type="radio" name="ok" value="1" <?if($config['cache']==1){?>checked<?}?>/>开启
				<input type="radio" name="ok" value="0" <?if($config['cache']==0){?>checked<?}?>/>关闭
				&nbsp;&nbsp;缓存时间:
				<select name="cachetime">
					<option value="2160">90天</option>
					<option value="720" <?if($config['cachetime']==720){?>selected<?}?>>30天</option>
					<option value="240" <?if($config['cachetime']==240){?>selected<?}?>>10天</option>
					<option value="24" <?if($config['cachetime']==24){?>selected<?}?>>1天</option>
					<option value="1" <?if($config['cachetime']==1){?>selected<?}?>>1小时</option>
				</select>
				<button class="userbtn">提交</button>
<!--				<a class="userbtn" href="?act=del_all">更新缓存</a>-->
			</form>
		</div>
	</div>
</body>
</html>