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
<title>系统升级-<?php 
echo SYSTEM_NAME;
?>
</title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
	<script>
		$(function(){
			$('#down').click(function(){
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
				<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a
					href="update.php#">系统管理</a>&nbsp;-</span>&nbsp;系统升级
			</div>
		</div>
		<div class="page ">
			<div style="margin-bottom:10px;">当前系统版本:v<?php 
echo $config['ver'];
?>
&nbsp;&nbsp;发布日期:<?php 
echo date('Y/m/d', $config['ver_date']);
?>
</div>
				<?php 
$post_data['act'] = "update";
$post_data['ver_title'] = $config['ver'];
if ($request = request_post($post_data)) {
	$result = json_decode($request);
	if ($result->title) {
		?>
						<dl class="cur2" id="update">
							<dt>发现新版本</dt>
							<dd><span>版本:</span>v<?php 
		echo $result->title;
		?>
</dd>
							<dd class="deail"><span>说明:</span><p><?php 
		echo $result->detail;
		?>
</p></dd>
							<dd><span>日期:</span><?php 
		echo date('Y-m-d', $result->date);
		?>
</dd>
							<dd><a id="down" href="update_down.php?ver=<?php 
		echo $result->title;
		?>
&ver_date=<?php 
		echo $result->date;
		?>
&zip=<?php 
		echo $result->zip;
		?>
">更新</a></dd>
						</dl>
						<?php 
	}
} else {
	echo "已是最新版本";
}
?>
		</div>
	</div>
</body>
</html>