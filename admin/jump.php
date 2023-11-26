<?php
require('inc/lic_admin.php');
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
    header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : '';
$jump_url = isset($_POST['jump_url']) ? $_POST['jump_url'] : '';
if ($act == 'edit') {
    if ($jump_url && !(strpos($jump_url, 'http://') !== false)) {
        $jump_url = "http://" . $jump_url;
    }
    $mysqli->query("update config set jump='" . dataen($jump_url) . "'");
    header('Location: jump.php');
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>全局跳转</title>
    <link rel="stylesheet" type="text/css" href="css/css.css"/>
    <script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
<div id="pageAll">
    <div class="pageTop">
        <div class="page">
            <img src="img/coin02.png"/><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a
                        href="jump.php">系统管理</a>&nbsp;-</span>&nbsp;全局跳转
        </div>
    </div>
    <div class="page cfD">
        <div style="line-height: 25px;color:blue;">开启跳转后用户访问自动跳转,蜘蛛访问不影响<br/>开启全局跳转后关键词跳转功能失效<br>留空,关闭跳转</div>
        <form action="?act=edit" method="post">
            <span>全局跳转地址:</span>
            <input class="userinput vpr" style="width:400px;" type="text" name="jump_url" placeholder="http://"
                   value="<?= $config['jump'] ?>"/>
            <button class="userbtn">提交</button>
        </form>
    </div>
</div>
</body>
</html>