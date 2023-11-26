<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>API接口-<?php 
echo SYSTEM_NAME;
?>
</title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
	<div id="pageAll">
		<div class="pageTop">
			<div class="page">
				<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a href="api.php">系统管理</a>&nbsp;-</span>&nbsp;API接口
			</div>
		</div>
		<div class="page cfD">
			<div style="line-height: 25px;color:blue;margin-bottom:10px">通过API接口可以让其他程序提交数据到蜘蛛池</div>
			<div style="line-height:25px;">
				数据提交接口:<br/><span style="color:red;font-size:16px;line-height:40px;">http://<?php 
echo $_SERVER['HTTP_HOST'];
?>
/api.php</span>
			<br/>
			post提交数据:<br/>
			apikey:<span style="padding-left:10px;color:green"><?php 
echo substr(sha1($config['title']), 20);
?>
</span><br/>
			type:<span style="padding-left:10px;color:green">suoyinchi(索引池);quanzhongchi(权重池);guanjianci(关键词)</span><br/>
			data:<span style="padding-left:10px;color:green">你的数据(单条数据)</span>
			</div>
		</div>
	</div>
</body>
</html>