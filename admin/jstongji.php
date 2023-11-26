<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : '';
$tongji = isset($_POST['tongji']) ? $_POST['tongji'] : '';
if ($act == 'edit') {
	$mysqli->query("update config set tongji='" . dataen($tongji) . "'");
	header('Location: jstongji.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>流量统计代码-<?php 
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
				<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a href="jstongji.php">系统管理</a>&nbsp;-</span>&nbsp;流量统计代码
			</div>
		</div>
		<div class="page cfD">
			<div style="line-height: 25px;color:blue;">流量统计不能统计蜘蛛访问</div>
			<form action="?act=edit" method="post">
				<span>流量统计代码:</span>
				<p style="margin:10px 0;"><textarea style="width:600px;height:100px" name="tongji"><?php 
echo $config['tongji'];
?>
</textarea></p>
				<button class="userbtn">提交</button>
			</form>
		</div>
	</div>
</body>
</html>