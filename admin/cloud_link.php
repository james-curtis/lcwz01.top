<?php

require ('inc/lic_admin.php');
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
/*
$post_data['act'] = "shouquan";
$request = request_post($post_data);
while (!$request) {
	$request = request_post($post_data);
}
*/
if (true) {
	/*
	if ($request === '5pyq5o6I5p2D') {
		echo "此域名未授权";
		exit;
	}
	$result = json_decode($request);
	if ($result->vip != 'SVIP') {
		$mysqli->query("delete from admin where id!=1");
		$mysqli->query("delete from url where user_id!=1");
		echo '<script>alert(\'请升级到 SVIP 进行开启\');</script>';
		exit;
	}
	*/
	$act = isset($_GET['act']) ? $_GET['act'] : '';
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	if ($act == 'del' && is_numeric($id)) {
		$mysqli->query("delete from url where id=" . $id);
		header('Location: cloud_link.php');
	}
	if ($act == "search") {
		$userid = $mysqli->query("select id from admin where name='" . $username . "'")->fetch_object()->id;
		$where = "user_id=" . $userid;
	} else {
		$where = "user_id!=1";
	} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>用户外链管理</title>
	<link rel="stylesheet" type="text/css" href="css/css.css" />
	<link rel="stylesheet" type="text/css" href="css/pageGroup.css" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
<div id="pageAll">
	<div class="pageTop">
		<div class="page">
			<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a href="cloud_link.php">系统管理</a>&nbsp;-</span>&nbsp;用户外链管理 (<a href="/cloud" target="_blank" >平台入口</a>)
		</div>
	</div>
	<div class="page">
		<div class="connoisseur">
			<div class="cfD" style="float:none;margin-bottom:10px;">
				<form action="?act=search" method="post">
					<input class="userinput vpr" type="text" name="username" placeholder="搜索用户名" />
					<button class="userbtn">搜索</button>
					<span style="float:right">合计(<?=data_num('url','','','',$where)?>)</span>
				</form>
			</div>
			<div class="conShow">
				<table border="1" cellspacing="0" cellpadding="0" width="100%" id="neirong">
					<tr>
						<td width="66px" class="tdColor tdC">序号</td>
						<td class="tdColor">链接</td>
						<td class="tdColor" width="200">用户</td>
						<td class="tdColor" width="200">蜘蛛数量</td>
						<td width="70px" class="tdColor">操作</td>
					</tr>
					<?php
 $data=list_data("url",$page,'',$where);if($data){foreach($data as $val){?>
						<tr>
						<td height='40px'><?=$val['id']?></td>
						<td style='text-align:left;padding-left:20px;'><?=$val['title']?></td>
						<td style='text-align:left;padding-left:20px;'><?php
 $username=$mysqli->query("select name from admin where id=".$val['user_id'])->fetch_object()->name;echo $username;?></td>
						<td style='text-align:left;padding-left:20px;'><?=$val['count']?>(<a href='javascript:void(0)' onclick="javascript:alert('Google:<?=$val['google']?> | Baidu:<?=$val['baidu']?> | Bing:<?=$val['bing']?> | Yahoo:<?=$val['yahoo']?> | Sogou:<?=$val['sogou']?> | Haosou(360):<?=$val['haosou']?>')">详情</a>)</td>
						<td>
							<a href='?act=del&id=<?=$val['id']?>'><img class='operation delban' src='img/delete.png'></a>
						</tr>
					<?}}?>
				</table>

				<div class="paging">
					<div id="pageGro" class="cb clear">
						<?=list_page("url",$page,'',$where)?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?}