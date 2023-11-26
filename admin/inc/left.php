<?php


require 'lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>左侧导航</title>
<link rel="stylesheet" type="text/css" href="../css/public.css" />
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/public.js"></script>
</head>

<body id="bg">
	<!-- 左边节点 -->
	<div class="container">

		<div class="leftsidebar_box">
			<a href="../main.php" target="main">
                <div class="line">
					<img src="../img/coin01.png" />&nbsp;&nbsp;首页
				</div>
            </a>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coin03.png" /><img class="icon2"
						src="../img/coin04.png" />&nbsp;&nbsp;基础<img class="icon3"
						src="../img/coin19.png" />
                    <img class="icon4" src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=url" target="main">索引池管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=qurl" target="main">权重池管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=keywords" target="main">关键词管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=domains" target="main">域名管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=juzi2" target="main">句子管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=juzi" target="main">段子管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=shipin" target="main">视频管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coin05.png" /><img class="icon2" src="../img/coin06.png" />&nbsp;&nbsp;蜘蛛<img class="icon3"  src="../img/coin19.png" /><img class="icon4" src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../spider.php" target="main">蜘蛛日志</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../spiderset.php" target="main">蜘蛛开关</a><img class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coin05.png" /><img class="icon2" src="../img/coin06.png" />&nbsp;&nbsp;外推<img class="icon3"  src="../img/coin19.png" /><img class="icon4" src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../waituilog.php" target="main">外推日志</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=waiurl" target="main">外推管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coin05.png" /><img class="icon2" src="../img/coin06.png" />&nbsp;&nbsp;模板<img class="icon3"  src="../img/coin19.png" /><img class="icon4" src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=m_title" target="main">title</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=m_key" target="main">keywords</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=m_des" target="main">description</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=m_url" target="main">URL样式</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=a_title" target="main">文章标题</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=a_content" target="main">文章内容</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a href="../unicode.php" target="main" class="cks">关键词转码</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a href="../cache.php" target="main" class="cks">页面缓存</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a href="../templates.php" target="main" class="cks">模板管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coin05.png" /><img class="icon2" src="../img/coin06.png" />&nbsp;&nbsp;跳转<img class="icon3"  src="../img/coin19.png" /><img class="icon4" src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../jump.php" target="main">全局跳转</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=key_jump" target="main">关键词跳转</a><img class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coin05.png" /><img class="icon2" src="../img/coin06.png" />&nbsp;&nbsp;采集<img class="icon3"  src="../img/coin19.png" /><img class="icon4" src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../caiji.php" target="main">新闻采集</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../info.php?act=weiyuanchuang" target="main">采集伪原创</a><img class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coin05.png" /><img class="icon2" src="../img/coin06.png" />&nbsp;&nbsp;平台<img class="icon3"  src="../img/coin19.png" /><img class="icon4" src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../cloud_user.php" target="main">用户管理</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a class="cks" href="../cloud_link.php" target="main">用户外链</a><img class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="../img/coinL1.png" /><img class="icon2"
						src="../img/coinL2.png" />&nbsp;&nbsp;系统<img class="icon3"
						src="../img/coin19.png" /><img class="icon4"
						src="../img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a href="../jstongji.php" target="main" class="cks">流量统计</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22" src="../img/coin222.png" /><a href="../api.php" target="main" class="cks">API接口</a><img class="icon5" src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22"
						src="../img/coin222.png" /><a href="../changepwd.php"
						target="main" class="cks">修改密码</a><img class="icon5"
						src="../img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="../img/coin111.png" /><img class="coin22"
						src="../img/coin222.png" /><a class="cks" href="../log.php?act=out" target="_top">退出</a><img
						class="icon5" src="../img/coin21.png" />
				</dd>
			</dl>
		</div>
	</div>
</body>
</html>