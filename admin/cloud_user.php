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
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$name = isset($_POST['name']) ? $_POST['name'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : false;
	$password = !empty($password) ? sha1($password) : false;
	$num = isset($_POST['num']) ? $_POST['num'] : '';
	$endtime = isset($_POST['endtime']) ? strtotime($_POST['endtime']) : '';
	if ($act == 'add' && $name && $password && $num && $endtime) {
		$cunzai = $mysqli->query("select id from admin where name='" . $name . "'");
		if ($cunzai->num_rows > 0) {
			echo "<script>alert('用户名已存在');self.location.href='cloud_user.php';</script>";
			exit;
		} else {
			$addtime = time();
			$mysqli->query("insert into admin (name,password,num,addtime,endtime) VALUES ('" . $name . "','" . $password . "',$num,$addtime,$endtime)");
		}
		header('Location: cloud_user.php');
	}
	if ($act == 'edit') {
		$result = $mysqli->query("select * from admin where id=" . $id);
		$row = $result->fetch_assoc();
		$name = $row['name'];
		$num = $row['num'];
		$endtime = date('Y-m-d', $row['endtime']);
	}
	if ($act == 'editsave') {
		if ($password) {
			$sql = "update admin set password='" . $password . "',num=" . $num . ",endtime=" . $endtime . " where id=" . $id;
		} else {
			$sql = "update admin set num=" . $num . ",endtime=" . $endtime . " where id=" . $id;
		}
		$mysqli->query($sql);
		header('Location: cloud_user.php');
	}
	if ($act == 'del' && is_numeric($id)) {
		$mysqli->query("delete from url where user_id=" . $id);
		$mysqli->query("delete from admin where id=" . $id);
		header('Location: cloud_user.php');
	}
	if ($act == 'delall' && is_numeric($id)) {
		$mysqli->query("delete from url where user_id=" . $id);
		$mysqli->query("delete from qurl where user_id=" . $id);
		echo '<script>alert(\'清空成功\');self.location.href=\'cloud_user.php\';</script>';
	} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>平台用户管理</title>
	<link rel="stylesheet" type="text/css" href="css/css.css" />
	<link rel="stylesheet" type="text/css" href="css/pageGroup.css" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/rili/lhgcore.js"></script>
	<script type="text/javascript" src="js/rili/lhgcalendar.js"></script>
</head>
<body>
<div id="pageAll">
	<div class="pageTop">
		<div class="page">
			<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a href="cloud_user.php">系统管理</a>&nbsp;-</span>&nbsp;平台用户管理 (<a href="/cloud" target="_blank" >平台入口</a>)
		</div>
	</div>
	<div class="page">
		<div class="connoisseur">
			<div class="conform clear">
				<div class="cfD">
					<form action="<?if($act=='edit'){echo "?act=editsave&id=".$id;}else{echo '?act=add';}?>" method="post">
						用户名:<input class="userinput vpr" type="text" name="name" style="width:150px" value="<?=$name?>" <?if($act=='edit')echo 'disabled'?>/>密码:<input class="userinput vpr" type="text" name="password" style="width:150px" <?if($act=='edit')echo "placeholder='不修改密码请留空'"?> />数量:<input class="userinput vpr" type="text" name="num" style="width:50px"  value="<?=$num?>"/>截止:<input class="userinput vpr" type="text" name="endtime" style="width:100px" value="<?=$endtime?>" onclick="J.calendar.get();" />
						<button class="userbtn"><?if($act=='edit'){echo "提交";}else{echo '添加';}?></button>
					</form>
				</div>
			</div>
			<div class="conShow">
				<span style="float:right">合计(<?=data_num('admin','','','','id!=1')?>)</span>
				<table border="1" cellspacing="0" cellpadding="0" width="100%" id="neirong">
					<tr>
						<td width="66px" class="tdColor tdC">序号</td>
						<td class="tdColor">用户名</td>
						<td class="tdColor" width="100">数量</td>
						<td class="tdColor" width="200">加入时间</td>
						<td class="tdColor" width="100">截止时间</td>
						<td width="120px" class="tdColor">操作</td>
					</tr>
					<?php
 $data=list_data("admin",$page,'','id!=1');if($data){foreach($data as $val){?>
						<tr>
						<td height='40px'><?=$val['id']?></td>
						<td style='text-align:left;padding-left:20px;'><?=$val['name']?></td>
						<td style='text-align:left;padding-left:20px;'><?=$val['num']?></td>
						<td style='text-align:left;padding-left:20px;'><?=date('Y-m-d H:i:s',$val['addtime'])?></td>
						<td style='text-align:left;padding-left:20px;'><?=date('Y-m-d',$val['endtime'])?></td>
						<td><a href='?act=edit&id=<?=$val['id']?>'><img class='operation delban' src='img/update.png'></a>&nbsp;<a href='?act=del&id=<?=$val['id']?>'><img class='operation delban' src='img/delete.png'></a>&nbsp;<a href='?act=delall&id=<?=$val['id']?>' title="清空此用户链接"><img class='operation delban' src='img/reset.png' width="21"></a></td>
						</tr>
					<?}}?>
				</table>

				<div class="paging">
					<div id="pageGro" class="cb clear">
						<?=list_page("admin",$page,'','id!=1')?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?}