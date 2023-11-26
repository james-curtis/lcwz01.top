<?php
require('inc/lic_admin.php');
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
    header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : '';
$ok = isset($_GET['ok']) ? $_GET['ok'] : 0;
$id = isset($_GET['id']) ? $_GET['id'] : '';
$cai_ji = isset($_POST['cai_ji']) ? $_POST['cai_ji'] : 0;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
if ($act == 'edit') {
    if ($cai_ji == 0) {
        $mysqli->query("truncate table c_title");
        $mysqli->query("truncate table c_content");
    }
    if ($cai_ji > 0) {
        $result = $mysqli->query("select id from c_yuan where ok=1 order by id desc");
        if ($result->num_rows > 0) {
            $param = array("url" => "http://" . $_SERVER['HTTP_HOST'] . "/admin/inc/caijistart.php");
            $ac = new AsyncCURL();
            $ac->set_param($param);
            $ret = $ac->send();
        } else {
            echo '<script>alert(\'请同步新闻源并开启\');self.location.href=\'caiji.php\';</script>';
            exit;
        }
    }
    $mysqli->query("update config set cai_ji='" . dataen($cai_ji) . "',cai_time='" . dataen(time()) . "'");
    header('Location: caiji.php');
}
if ($act == 'us' && $id) {
    $mysqli->query("update c_yuan set ok=" . $ok . " where id=" . $id);
    header('Location: caiji.php');
}
if ($act == 'sync') {
    $post_data['act'] = "caijiyuan";
    if ($request = request_post($post_data)) {
        $request = json_decode($request, true);
        foreach ($request as $val) {
            $result = $mysqli->query("select id from c_yuan where url='" . $val['url'] . "'");
            if ($result->num_rows == 0) {
                $mysqli->query("insert into c_yuan (`url`,`reg_t`,`reg_c`,`rang_t`,`rang_c`,`out`,`in`,`name`,`ok`) VALUES ('" . $val['url'] . "','" . $val['reg_t'] . "','" . $val['reg_c'] . "','" . $val['rang_t'] . "','" . $val['rang_c'] . "','" . $val['out'] . "','" . $val['in'] . "','" . $val['name'] . "','" . $val['ok'] . "')");
            }
        }
        header('Location: caiji.php');
    }
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>新闻采集-2018蜘蛛池</title>
    <link rel="stylesheet" type="text/css" href="css/css.css"/>
    <link rel="stylesheet" type="text/css" href="css/pageGroup.css"/>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script>
        $(function () {
            $('.userbtn').click(function () {
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
            <img src="img/coin02.png"/><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a
                        href="caiji.php">系统管理</a>&nbsp;-</span>&nbsp;新闻采集
        </div>
    </div>
    <div class="page">
        <div class="connoisseur">
            <div class="cfD" style="float:none;">
                <form action="?act=edit" method="post">
                    <span>自动采集间隔:</span>
                    <select name="cai_ji">
                        <option value="0">关闭</option>
                        <option value="1" <? if ($config['cai_ji'] == 1) echo "selected" ?>>1小时</option>
                        <option value="2" <? if ($config['cai_ji'] == 2) echo "selected" ?>>2小时</option>
                        <option value="3" <? if ($config['cai_ji'] == 3) echo "selected" ?>>3小时</option>
                        <option value="4" <? if ($config['cai_ji'] == 4) echo "selected" ?>>4小时</option>
                        <option value="5" <? if ($config['cai_ji'] == 5) echo "selected" ?>>5小时</option>
                    </select>
                    <button class="userbtn">提交</button>
                    <a id="caiji" class="userbtn" href="inc/caijistart.php">立即采集</a>
                    <a href="?act=sync" id="sync" class="userbtn">同步新闻源</a>
                </form>
            </div>
            <div class="conShow">
                <span style="float:right">合计(<?= data_num('c_yuan') ?>)</span>
                <table border="1" cellspacing="0" cellpadding="0" width="100%" id="neirong">
                    <tr>
                        <td width="66px" class="tdColor tdC">序号</td>
                        <td class="tdColor">名称</td>
                        <td class="tdColor">采集地址</td>
                        <td width="70px" class="tdColor">操作</td>
                    </tr>
                    <?php
                    $data = list_data("c_yuan", $page);
                    if ($data) {
                        foreach ($data as $val) { ?>
                            <tr>
                                <td height='40px'><?= $val['id'] ?></td>
                                <td style='text-align:left;padding-left:20px;'><?= $val['name'] ?></td>
                                <td style='text-align:left;padding-left:20px;'><?= $val['url'] ?></td>
                                <td>
                                    <? if ($val['ok']) { ?>
                                        <a href='?act=us&ok=0&id=<?= $val['id'] ?>'>已开启</a>
                                    <? } else { ?>
                                        <a href='?act=us&ok=1&id=<?= $val['id'] ?>'>已关闭</a>
                                    <? } ?>
                                </td>
                            </tr>
                        <? }
                    } ?>
                </table>

                <div class="paging">
                    <div id="pageGro" class="cb clear">
                        <?= list_page("c_yuan", $page) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>