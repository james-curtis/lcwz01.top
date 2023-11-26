<?php
header('Content-type:text/html;charset=utf-8');
require '../admin/inc/data.php';
include '../admin/inc/function.php';
include '../admin/inc/key.php';

$config = config_list();
$act = isset($_GET['act']) ? $_GET['act'] : false;
$err = "";
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['is_login']) && !empty($_SESSION['user_id']) && !empty($_SESSION['is_login'])) {
	header("Location: index.php");
}
if ($act == "login") {
	$name = isset($_POST['name']) ? $_POST['name'] : false;
	$name = !empty($name) ? $name : false;
	$password = isset($_POST['password']) ? $_POST['password'] : false;
	$password = !empty($password) ? sha1($password) : false;
	if ($name && $password) {
		$result = $mysqli->query("select id,endtime from admin where name='" . $name . "' and password='" . $password . "' limit 1");
		if ($result->num_rows > 0) {
			$row = $result->fetch_object();
			if ($row->id == 1) {
				echo "<script>alert('用户名或密码错误');self.location.href='login.php';</script>";
				die;
			} else {
				if (time() > $row->endtime) {
					echo "<script>alert('您的账户已过期');self.location.href='login.php';</script>";
					die;
				} else {
					$_SESSION['user_id'] = $row->id;
					$_SESSION['is_login'] = 1;
					header('Location: index.php');
				}
			}
		} else {
			echo '<script>alert(\'用户名或密码错误\');self.location.href=\'login.php\';</script>';
			die;
		}
	}
}
if ($act == "out") {
	unset($_SESSION['admin_id']);
	unset($_SESSION['is_login']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>登录_用户中心</title>
    <link rel="stylesheet" type="text/css" href="css/public.css" />
    <link rel="stylesheet" type="text/css" href="css/page.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="MobileOptimized" content="240">
    <meta name="applicable-device" content="mobile">

</head>
<body>

<!-- 登录页面头部 -->
<div class="logHead">
    用户平台
</div>
<!-- 登录页面头部结束 -->

<!-- 登录body -->
<div class="logDiv">
    <div class="logGet">
        <!-- 头部提示信息 -->
        <div class="logD logDtip">
            <p class="p1">登录</p>
        </div>
        <!-- 输入框 -->
        <form action="?act=login" method="post">
            <div class="lgD">
                <img class="img1" src="img/logName.png" /><input type="text" placeholder="输入用户名" name="name" />
            </div>
            <div class="lgD">
                <img class="img1" src="img/logPwd.png" /><input type="password" placeholder="输入用户密码" name="password" />
            </div>
            <div class="logC">
                <button>登 录</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>