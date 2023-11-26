<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$from = isset($_GET['from']) ? $_GET['from'] : '';
$content = isset($_POST['content']) ? $_POST['content'] : '';
$con = "";
if ($act == "save" && $action == 'add' && $content) {
	$mysqli->query("insert into " . $from . " (title) values('" . $content . "')");
	header('Location: info.php?act=' . $from);
}
if ($act == "save" && $action == 'edit' && $content && $id) {
	$mysqli->query("update " . $from . " set title='" . $content . "' where id=" . $id);
	header('Location: info.php?act=' . $from);
}
if ($action == 'edit' && $id) {
	$con = $mysqli->query("select title from " . $from . " where id=" . $id)->fetch_object()->title;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>文章内容编辑-<?php 
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
			<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a href="jump.php">系统管理</a>&nbsp;-</span>&nbsp;文章内容编辑
		</div>
	</div>
	<div class="page cfD">
		<div style="line-height: 25px;color:blue;">适用标签: {随机域名} {当前域名} {顶级域名} {随机关键词} {主关键词} {页面地址} {随机段子} {随机图片} {随机视频} {随机人名} {对应采集文章标题} {对应采集文章内容} {随机权重池}</div>
		<form action="?act=save&action=<?php 
echo $action;
?>
&from=<?php 
echo $from;
?>
&id=<?php 
echo $id;
?>
" method="post">
			<!-- 加载编辑器的容器 -->
			<script id="content" name="content" type="text/plain"><?php 
echo $con;
?>
</script>
			<!-- 配置文件 -->
			<script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
			<!-- 编辑器源码文件 -->
			<script type="text/javascript" src="/ueditor/ueditor.all.min.js"></script>
			<!-- 实例化编辑器 -->
			<script type="text/javascript">
				var ue = UE.getEditor('content');
			</script>
			<div style="margin-top:10px;"><button class="userbtn">提交</button><a style="float:left" href="info.php?act=<?php 
echo $from;
?>
&page=1" class="userbtn">返回</a></div>
		</form>
	</div>
</div>
</body>
</html>