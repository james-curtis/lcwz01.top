<?php


require('inc/lic_admin.php');session_start();if(!isset($_SESSION['admin_id'])||!isset($_SESSION['is_login'])||empty($_SESSION['admin_id'])||empty($_SESSION['is_login'])){header("Location: log.php");}$act=isset($_GET['act'])?$_GET['act']:"";$id=isset($_GET['id'])?$_GET['id']:"";$id=is_numeric($id)?$id:"";$ok=isset($_GET['ok'])?$_GET['ok']:"";$ok=$ok==1?1:0;if($act=='edit'&&$id){$sql="update spiderset set ok=".$ok." where id=".$id;$mysqli->query($sql);$str="RewriteEngine on\n";$str.="RewriteRule ^sitemap.xml /xml.php\n";$str.="RewriteRule ^sitemap.html /sitemap.php\n";$str.="RewriteRule ^index.html /index.php\n";$str.="RewriteRule ^admin/$ /admin/index.php\n";$str.="RewriteRule ^cloud/$ /cloud/index.php\n";$str.="RewriteRule ^robots.txt /robots.php\n";$str.="RewriteRule ^(.*).html /article.php\n";$str.="RewriteRule ^(.*)/$ /article.php\n";$sql="select title from spiderset where ok=0";$result=$mysqli->query($sql);if($result->num_rows>0){while ($row =$result->fetch_assoc()){if ($row['title'] == 'Google'){$zz[] ="Googlebot";}if ($row['title'] == 'Baidu'){$zz[] ="Baiduspider";}if ($row['title'] == 'Bing'){$zz[] ="bingbot";}if ($row['title'] == 'Yahoo'){$zz[] ="Yahoo";}if ($row['title'] == 'Sogou'){$zz[] ="Sogou";}if ($row['title'] == 'Haosou'){$zz[] ="HaosouSpider";}if ($row['title'] == 'Shenma'){$zz[] ="YisouSpider";}if ($row['title'] == 'Yitao'){$zz[] ="EtaoSpider";}if ($row['title'] == 'Esou'){$zz[] ="EasouSpider";}}$zzstr=implode('|',$zz);$str.="RewriteCond %{HTTP_USER_AGENT} .*($zzstr) [NC]\n";$str.="RewriteRule ^.*$ https://www.baidu.com/s?wd=云凌工作室 [R=301,L]";}else{}file_put_contents('../.htaccess', $str);}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜘蛛设置</title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
	<div id="pageAll">
		<div class="pageTop">
			<div class="page">
				<img src="img/coin02.png" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;<a
					href="templates.php#">系统管理</a>&nbsp;-</span>&nbsp;蜘蛛设置
			</div>
		</div>
		<div class="page clear">
			<ul class="templates spiderset">
				<?php
 foreach(spiderset_list()as $key=>$value){?>
				<li><img src="<?=$value['thumb']?>"><?=$value['us']?></li>
				<?}?>
			</ul>
		</div>
	</div>
</body>
</html>