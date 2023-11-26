<?php
if ($_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] === 'http')
{
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit();
}
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
ini_set('html_errors', false);
ini_set('display_errors', false);
@ini_set('memory_limit', '-1');
@set_time_limit(0);
ini_set('date.timezone', 'Asia/Shanghai');
require 'data.php';
include 'function.php';
include 'key.php';
include 'curl.class.php';
define('SYSTEM_NAME', 'AliSpider');
define('SITE_NAME', '阿里蜘蛛池');
$support = "";
$a = isset($_GET['a']) ? $_GET['a'] : "";
if ($a == "shouquan") {
	$post_data['act'] = "shouquan";
	$request = array(
		'vip'=>1,
		'title'=>'test',
		'domain'=>10000,
		'templates'=>10000,
		'enddate'=>time() + 86400 * 36500,
		'date'=>time()+10000
	);
	$request = json_encode($request);
	//$request = request_post($post_data);
	if (!$request) {
		$i = 0;
		while (!$request) {
			$request = request_post($post_data);
			++$i;
			if ($i == 20) {
				echo "无法链接授权服务器";
				die;
			}
		}
	} else {
		if ($request === '5pyq5o6I5p2D') {
			$sql = "update config set title='',vip='',templates='',domain='',date='',enddate='',link=0 limit 1";
			$mysqli->query($sql);
			$result = $mysqli->query("select title from templates");
			if ($result->num_rows) {
				while ($row = $result->fetch_assoc()) {
					delFile("templates/" . $row['title']);
				}
				$mysqli->query("delete from templates where id not in(1,2)");
			}
			echo '此域名未授权';
			die;
		}
		$result = json_decode($request);
		if ($result->title && $result->vip && $result->domain && $result->templates && $result->enddate) {
			if (time() > $result->enddate) {
				$sql = "update config set title='',vip='',templates='',domain='',date='',enddate='',link=0 limit 1";
				$mysqli->query($sql);
				$mysqli->query("update templates set ok=0");
				echo SITE_NAME . '警告:此授权已过期';
				die;
			}
			$date_data = $mysqli->query("select date from config limit 1")->fetch_object()->date;
			$date = '';
			if (!$date_data) {
				$date = ",date='" . dataen(time()) . "'";
			}
			$sql = "update config set title='" . dataen(base64_decode($result->title)) . "',vip='" . dataen($result->vip) . "',domain='" . dataen($result->domain) . "',templates='" . dataen($result->templates) . "'" . $date . ",enddate='" . dataen($result->enddate) . "',ad='" . dataen($result->ad) . "',link=0 limit 1";
			$mysqli->query($sql);
			$vip_domain_num = $result->domain;
			$domain_num = $mysqli->query("select count(*) as count from domains")->fetch_object()->count;
			if ($domain_num > $vip_domain_num) {
				$del_num = $domain_num - $vip_domain_num;
				$mysqli->query("delete from domains order by id desc limit " . $del_num);
			}
			$vip_templates_num = $result->templates;
			$templates_num = $mysqli->query("select count(*) as count from templates where ok=1")->fetch_object()->count;
			if ($templates_num > $vip_templates_num) {
				$del_num = $templates_num - $vip_templates_num;
				$mysqli->query("update templates set ok=0 where ok=1 order by id desc limit " . $del_num);
				if ($result->vip == '免费') {
					$result = $mysqli->query("select title from templates where id not in(1,2)");
					if ($result->num_rows) {
						while ($row = $result->fetch_assoc()) {
							delFile("templates/" . $row['title']);
						}
						$mysqli->query("delete from templates where id not in(1,2)");
					}
				}
			}
			echo '授权更新成功';
			die;
		} else {
			echo '授权失败<pre>';
			var_dump($result);
			die;
		}
	}
}
$duankou = $_SERVER["SERVER_PORT"];
$yuming = $_SERVER['HTTP_HOST'];
$yuming = str_replace(':' . $duankou, '', $yuming);
$yumi = getdomain($yuming);

if ($yumi != 'localhost') {
	$result = $mysqli->query("select title from domains order by id desc");
	$ok = 0;
	while ($row = $result->fetch_assoc()) {
		if ($yumi == datade($row['title'])) {
			$ok = 1;
		}
	}
	if (!$ok) {
		$sql = "SELECT title FROM `domains` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `domains`)-(SELECT MIN(id) FROM `domains`))+(SELECT MIN(id) FROM `domains`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
		$spider = $mysqli->query($sql)->fetch_object()->title;
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: http://www.' . datade($spider) . "/");
		die;
	}
}

$config = config_list();
if(!$config['title']){
	file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/?a=shouquan');
	$config = config_list();
}
if ($config['cai_ji'] == 1) {
	$c_title = $mysqli->query("select count(*) as count from c_title")->fetch_object()->count;
	if (time() > $config['cai_time'] + $config['cai_ji'] * 3600) {
		$mysqli->query("update config set cai_time='" . dataen(time()) . "'");
		$result = $mysqli->query("select id from c_yuan where ok=1 order by id desc");
		if ($result->num_rows > 0) {
			$param = array("url" => "http://" . $_SERVER['HTTP_HOST'] . "/admin/inc/caijistart.php");
			$ac = new AsyncCURL();
			$ac->set_param($param);
			$ret = $ac->send();
		}
	}
}
if ($config['waitui'] == 1) {
	if ($config['waisudu'] == 1) {
		$waiurlarr = get_rand_number(1000, 'waiurl', 'title');
		foreach ($waiurlarr as $key => $value) {
			$waitui = strtr($value, "{domains}", $yuming);
			$waiarr[] = array('url' => $waitui);
		}
		$ac = new AsyncCURL();
		$ac->set_param($waiarr);
		$ret = $ac->send();
		$mysqli->query("update config set wainum=wainum+1000");
	} elseif ($config['waisudu'] == 2) {
		$waiurlarr = get_rand_number(100, 'waiurl', 'title');
		foreach ($waiurlarr as $key => $value) {
			$waitui = strtr($value, "{domains}", $yuming);
			$waiarr[] = array('url' => $waitui);
		}
		$ac = new AsyncCURL();
		$ac->set_param($waiarr);
		$ret = $ac->send();
		$mysqli->query("update config set wainum=wainum+100");
	} elseif ($config['waisudu'] == 0) {
		$waiurlarr = get_rand_number(10, 'waiurl', 'title');
		foreach ($waiurlarr as $key => $value) {
			$waitui = strtr($value, "{domains}", $yuming);
			$waiarr[] = array('url' => $waitui);
		}
		$ac = new AsyncCURL();
		$ac->set_param($waiarr);
		$ret = $ac->send();
		$mysqli->query("update config set wainum=wainum+10");
	}
}
if ($config['ping'] == 1) {
	require "baiduping.php";
	$hosturl = 'http://' . $_SERVER['HTTP_HOST'];
	$arturl = $hosturl . $_SERVER['REQUEST_URI'];
	$xmlurl = $hosturl . "/sitemap.xml";
	$sql = "SELECT title FROM `keywords` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `keywords`)-(SELECT MIN(id) FROM `keywords`))+(SELECT MIN(id) FROM `keywords`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
	$title = $mysqli->query($sql)->fetch_object()->title;
	$arc = new Ping($title, $arturl, $hosturl, $xmlurl);
	$support .= "|Ping:" . $arc->pingbaidu();
}
$ssyq = strtolower(get_naps_bot());