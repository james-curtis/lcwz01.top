<?php
header ('Content-type:text/html;charset=utf-8' );require('../admin/inc/data.php');include('../admin/inc/function.php');include('../admin/inc/key.php');$config=config_list();session_start();if(!isset($_SESSION['user_id'])||!isset($_SESSION['is_login'])||empty($_SESSION['user_id'])||empty($_SESSION['is_login'])){header("Location: login.php");}$result=$mysqli->query("select name,num,endtime from admin where id=".$_SESSION['user_id']);$user=$result->fetch_assoc();$act=isset($_GET['act'])?$_GET['act']:"";$id=isset($_GET['id'])?$_GET['id']:'';$page=isset($_GET['page'])?$_GET['page']:1;$title=isset($_POST['title'])?$_POST['title']:'';if($act=="gaimima"){$oldpass=isset($_POST['oldpass'])?$_POST['oldpass']:"";$oldpass=!empty($oldpass)?sha1($oldpass):"";$password=isset($_POST['password'])?$_POST['password']:"";$password=!empty($password)?sha1($password):"";if($password){$sql="select id from admin where id=".$_SESSION['user_id']." and password='".$oldpass."'";$result=$mysqli->query($sql);$row=$result->fetch_assoc();if($result->num_rows>0){$mysqli->query("update admin set password='".$password."' where id=".$row['id']);echo '<script>alert(\'修改成功\');self.location.href=\'index.php\';</script>';exit;}else{echo '<script>alert(\'旧密码错误\');self.location.href=\'index.php?act=gaimima\';</script>';exit;}}}if($act=='add'&&$title){$count=$mysqli->query("select count(*) as count from url where user_id=".$_SESSION['user_id'])->fetch_object()->count;if($count>=$user['num']){echo "<script>alert('数量已达到限制,请升级您的帐号');self.location.href='index.php';</script>";exit;}$mysqli->query("insert into url (title,user_id) VALUES ('".$title."',".$_SESSION['user_id'].")");header('Location: index.php');}if($act=='del'&&is_numeric($id)){$mysqli->query("delete from url where id=".$id);header('Location: index.php');}if($act=='del_all'){$mysqli->query("delete from url where user_id=".$_SESSION['user_id']);header('Location: index.php');}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>用户平台</title>
    <link rel="stylesheet" type="text/css" href="css/css.css" />
    <link rel="stylesheet" type="text/css" href="css/public.css" />
    <link rel="stylesheet" type="text/css" href="css/pageGroup.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="MobileOptimized" content="240">
    <meta name="applicable-device" content="mobile">
</head>

<body>
<!-- 头部 -->
<div class="head">
    <div class="headL">
        <h1>用户平台</h1>
        <div>格子数量:<em><?=$user['num']?></em>&nbsp;&nbsp;&nbsp;&nbsp;截止日期:<em><?=date('Y-m-d',$user['endtime'])?></em></div>
    </div>
</div>
<div class="menu"><a class="username"><?=$user['name']?></a><a class="wailian" href="index.php">索引池管理</a><a class="pwd" href="index.php?act=pwd">修改密码</a><a class="out" href="login.php?act=out">退出</a></div>
<div style="clear:both"></div>
<div class="main">
    <?if($act=='pwd'){?>
        <div class="gaimima">
            <div class="bacen">
                <form action="?act=gaimima" method="post">
                    <div class="bbD">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;用户 ID：&nbsp;&nbsp;<?=$_SESSION['user_id']?></div>
                    <div class="bbD">
                        &nbsp;&nbsp;&nbsp;输入旧密码：<input name="oldpass" type="password" class="input3" onblur="checkpwd1()" id="pwd1" /> <img class="imga" src="img/ok.png" /><img class="imgb" src="img/no.png" />
                    </div>
                    <div class="bbD">
                        &nbsp;&nbsp;&nbsp;输入新密码：<input name="password" type="password" class="input3" onblur="checkpwd2()" id="pwd2" /> <img class="imga" src="img/ok.png" /><img class="imgb" src="img/no.png" />
                    </div>
                    <div class="bbD">
                        再次确认密码：<input type="password" class="input3" onblur="checkpwd3()" id="pwd3" /> <img class="imga" src="img/ok.png" /><img class="imgb" src="img/no.png" />
                    </div>
                    <div class="bbD">
                        <p class="bbDP">
                            <button class="btn_ok btn_yes">提交</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    <?}else{?>
    <div class="wailian">
        <div class="cfD">
            (导入失败?请不要用中文文件名,请尽量保持单文件数据在5000以下</a>)
            <form action="?act=add" method="post">
                <input class="userinput vpr" type="text" name="title" id="title" placeholder="http://" /><button class="userbtn">添加</button><a class="userbtn" href="?act=del_all">清空所有</a>
            </form>
        </div>
        <div class="upload" id="upload">
            <form action="import.php?act=url" method="post" enctype="multipart/form-data">
                <input type="file" name="file">
                <input type="submit" value="导入txt(<2M)" class="userbtn">
            </form>
        </div>
        <table border="1" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td class="tdColor" style='text-align:left;padding-left:20px;'>外链(<?=data_num('url','','','','user_id='.$_SESSION['user_id'])?>)</td>
                <td class="tdColor" width='100px'>引蜘蛛次数</td>
                <td width="70px" class="tdColor">操作</td>
            </tr>
            <?php
 $where='user_id='.$_SESSION['user_id'];$data=list_data('url',$page,'',$where);if($data){foreach($data as $val){?>
            <tr>
                <td style='text-align:left;padding-left:20px;height:40px;'><?=$val['title']?></td>
                <td><?=$val['count']?></td>
                <td>
                    <a href='?act=del&id=<?=$val['id']?>'><img class='operation delban' src='img/delete.png'></a>
                </td>
            </tr>
            <? }}?>
        </table>

        <div class="paging">
            <div id="pageGro" class="cb clear">
                <?=list_page('url',$page,'',$where)?>
            </div>
        </div>
    </div>
    <?}?>
</div>
<script type="text/javascript">
    function checkpwd1(){
        var user = document.getElementById('pwd1').value.trim();
        if (user.length >= 6 && user.length <= 12) {
            $("#pwd1").parent().find(".imga").show();
            $("#pwd1").parent().find(".imgb").hide();
        }else{
            $("#pwd1").parent().find(".imgb").show();
            $("#pwd1").parent().find(".imga").hide();
            alert("请输入6位以上12位以下密码");
        }
    }
    function checkpwd2(){
        var user = document.getElementById('pwd2').value.trim();
        if (user.length >= 6 && user.length <= 12) {
            $("#pwd2").parent().find(".imga").show();
            $("#pwd2").parent().find(".imgb").hide();
        }else{
            $("#pwd2").parent().find(".imgb").show();
            $("#pwd2").parent().find(".imga").hide();
            alert("请输入6位以上12位以下密码");
        }
    }
    function checkpwd3(){
        var user = document.getElementById('pwd3').value.trim();
        var pwd = document.getElementById('pwd2').value.trim();
        if (user.length >= 6 && user.length <= 12 && user == pwd) {
            $("#pwd3").parent().find(".imga").show();
            $("#pwd3").parent().find(".imgb").hide();
        }else{
            $("#pwd3").parent().find(".imgb").show();
            $("#pwd3").parent().find(".imga").hide();
            alert("两次密码输入不一致");
        }
    }
</script>
</body>
</html>