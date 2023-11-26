<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : '';
if ($act == "waitui") {
	$waitui = isset($_POST['waitui']) ? $_POST['waitui'] : '0';
	$waisudu = isset($_POST['waisudu']) ? $_POST['waisudu'] : '0';
	$ping = isset($_POST['ping']) ? $_POST['ping'] : '0';
	$mysqli->query("update config set waitui='" . dataen($waitui) . "'");
	$mysqli->query("update config set waisudu='" . dataen($waisudu) . "'");
	$mysqli->query("update config set ping='" . dataen($ping) . "'");
	echo '<script>alert(\'修改成功\');self.location.href=\'waituilog.php\';</script>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>外推日志-<?php 
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
				<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a
					href="waituilog.php#">系统管理</a>&nbsp;-</span>&nbsp;外推日志
			</div>
		</div>
		<div class="page cfD" style="line-height:30px;">
			<div style="line-height: 25px;color:blue;">外推:将蜘蛛池内域名发送到大型网站进行模拟用户搜索,从而增加蜘蛛入口
			<br>蜘蛛量1秒5个以上建议开低速外推,5个以下开中速,1个以下开高速
				<br>如果出现CPU或内存占满,网站打不开情况,请改为低速或关闭外推
			</div>
			<form action="?act=waitui" method="post">
				<span>外推开关:</span>
				<input type="radio" name="waitui" value="1" <?if($config['waitui']==1){?>checked<?}?>/>开启
				<input type="radio" name="waitui" value="0" <?if($config['waitui']==0){?>checked<?}?>/>关闭
				<br><span>外推速度:</span>
				<input type="radio" name="waisudu" value="1" <?if($config['waisudu']==1){?>checked<?}?>/>高速
				<input type="radio" name="waisudu" value="2" <?if($config['waisudu']==2){?>checked<?}?>/>中速
				<input type="radio" name="waisudu" value="0" <?if($config['waisudu']==0){?>checked<?}?>/>低速
				<br><span>Ping开关:</span>
				<input type="radio" name="ping" value="1" <?if($config['ping']==1){?>checked<?}?>/>开启
				<input type="radio" name="ping" value="0" <?if($config['ping']==0){?>checked<?}?>/>关闭
				<br><button class="userbtn">提交</button>
			</form>
			<br>
			<?php 
$wainum = $config['wainum'];
if ($wainum) {
	$waitui = $wainum / 1000 . "k";
	if ($wainum > 10000) {
		$waitui = $wainum / 10000 . "w";
	}
} else {
	$waitui = 0;
}
?>
			<h1 style="font-size:20px;">已发送外推链接: <span style="color:red;font-size:20px;"><?php 
echo $waitui;
?>
</span></h1>
			<img src="img/chilun.gif"/>
		</div>
	</div>
</body>
</html>