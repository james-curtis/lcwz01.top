<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : '';
$ok = isset($_POST['ok']) ? $_POST['ok'] : '';
if ($act == 'edit') {
	$mysqli->query("update config set unicode='" . dataen($ok) . "'");
	header('Location: unicode.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>关键词转码-<?php 
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
				<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a href="jump.php">系统管理</a>&nbsp;-</span>&nbsp;关键词转码
			</div>
		</div>
		<div class="page cfD">
			<div style="line-height: 25px;color:blue;">将关键词转换成代码,一些屏蔽词可以使用此功能达到很好的收录效果</div>
			<form action="?act=edit" method="post">
			<span>关键词转码:</span>
				<input type="radio" name="ok" value="1" <?if($config['unicode']==1){?>checked<?}?>/>开启
				<input type="radio" name="ok" value="0" <?if($config['unicode']==0){?>checked<?}?>/>关闭
				<button class="userbtn">提交</button>
			</form>
		</div>
	</div>
</body>
</html>