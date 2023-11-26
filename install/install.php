<?php

@set_time_limit(1000);
header('Content-type:text/html;charset=utf-8');
if (phpversion() < '5.3.0') {
	set_magic_quotes_runtime(0);
}
if (phpversion() < '5.4.0') {
	die('您的php版本为' . phpversion() . ',版本过低，不能安装本软件，请升级到5.4.x或更高版本再安装，谢谢！');
}
define('PC_PATH', dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)) . DIRECTORY_SEPARATOR);
if (!defined('ALI_PATH')) {
	define('ALI_PATH', PC_PATH . '..' . DIRECTORY_SEPARATOR);
}
if (file_exists(ALI_PATH . 'install.lock')) {
	die('您已经安装过了，如果需要重新安装，请删除 ./install.lock 文件！');
}
$steps = array('1' => '安装许可协议', '2' => '运行环境检测', '3' => '文件权限设置', '4' => '帐号设置', '5' => '安装详细过程', '6' => '安装完成');
$step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : 1;
$mode = 511;
include '../admin/inc/key.php';
switch ($step) {
	case '1':
		$license = file_get_contents(ALI_PATH . "install" . DIRECTORY_SEPARATOR . "license.txt");
		include 'step/step' . $step . ".tpl.php";
		break;
	case '2':
		$PHP_JSON = '0';
		if (extension_loaded('json')) {
			if (function_exists('json_decode') && function_exists('json_encode')) {
				$PHP_JSON = '1';
			}
		}
		$PHP_DNS = preg_match("/^[0-9.]{7,15}$/", @gethostbyname('vip.alizhizhuchi.top')) ? 1 : 0;
		$is_right = phpversion() >= '5.4.0' && extension_loaded('mysqli') && extension_loaded('curl') && extension_loaded('zip') && $PHP_JSON && (extension_loaded('iconv') || extension_loaded('mbstring')) && ini_get('allow_url_fopen') && ini_get('short_open_tag') && $PHP_DNS ? 1 : 0;
		include 'step/step' . $step . ".tpl.php";
		break;
	case '3':
		$chmod_file = 'chmod.txt';
		$files = file(ALI_PATH . "install" . DIRECTORY_SEPARATOR . $chmod_file);
		$no_writablefile = 0;
		foreach ($files as $_k => $file) {
			$file = str_replace('*', '', $file);
			$file = trim($file);
			if (is_dir(ALI_PATH . $file)) {
				$is_dir = '1';
				$cname = '目录';
				$write_able = writable_check(ALI_PATH . $file);
			} else {
				$is_dir = '0';
				$cname = '文件';
			}
			if ($is_dir == '0' && is_writable(ALI_PATH . $file)) {
				$is_writable = 1;
			} elseif ($is_dir == '1' && dir_writeable(ALI_PATH . $file)) {
				$is_writable = $write_able;
				if ($is_writable == '0') {
					$no_writablefile = 1;
				}
			} else {
				$is_writable = 0;
				$no_writablefile = 1;
			}
			$filesmod[$_k]['file'] = $file;
			$filesmod[$_k]['is_dir'] = $is_dir;
			$filesmod[$_k]['cname'] = $cname;
			$filesmod[$_k]['is_writable'] = $is_writable;
		}
		if (dir_writeable(ALI_PATH)) {
			$is_writable = 1;
		} else {
			$is_writable = 0;
		}
		$filesmod[$_k + 1]['file'] = '网站根目录';
		$filesmod[$_k + 1]['is_dir'] = '1';
		$filesmod[$_k + 1]['cname'] = '目录';
		$filesmod[$_k + 1]['is_writable'] = $is_writable;
		include 'step/step' . $step . ".tpl.php";
		break;
	case '4':
		$database = (include "../admin/inc/database.php");
		extract($database['default']);
		include 'step/step' . $step . ".tpl.php";
		break;
	case '5':
		extract($_POST);
		include 'step/step' . $step . ".tpl.php";
		break;
	case '6':
		$pos = strpos(get_url(), 'install/install.php');
		$url = substr(get_url(), 0, $pos);
		file_put_contents(ALI_PATH . 'install.lock', '');
		include 'step/step' . $step . ".tpl.php";
		break;
	case 'installmodule':
		extract($_POST);
		$PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
		if ($module == 'admin') {
			$db_config = array('hostname' => $dbhost, 'username' => $dbuser, 'password' => $dbpw, 'database' => $dbname);
			set_config($db_config, 'database');
			$link = mysqli_connect($dbhost, $dbuser, $dbpw) or die('Not connected : ' . mysqli_connect_error());
			$version = mysqli_get_server_info($link);
			if ($version > '4.1') {
				mysqli_query($link, "SET NAMES 'utf8");
			}
			if ($version > '5.0') {
				mysqli_query($link, "SET sql_mode=''");
			}
			if (!@mysqli_select_db($link, $dbname)) {
				@mysqli_query($link, "CREATE DATABASE {$dbname}");
				if (@mysqli_error($link)) {
					echo 1;
					die;
				} else {
					mysqli_select_db($link, $dbname);
				}
			}
			$dbfile = 'kafeizhizhuchi.sql';
			if (file_exists(ALI_PATH . 'install' . DIRECTORY_SEPARATOR . 'main' . DIRECTORY_SEPARATOR . "" . $dbfile)) {
				$sql = file_get_contents(ALI_PATH . "install" . DIRECTORY_SEPARATOR . "main" . DIRECTORY_SEPARATOR . $dbfile);
				_sql_execute($link, $sql);
				$password = sha1($password);
				_sql_execute($link, "INSERT INTO admin (id,name,password,num,addtime,endtime) VALUES (1,'{$username}','{$password}',0,0,0)");
				$duankou = $_SERVER["SERVER_PORT"];
				$yuming = $_SERVER['HTTP_HOST'];
				$yuming = str_replace(':' . $duankou, '', $yuming);
				$yumi = getdomain($yuming);
				_sql_execute($link, "INSERT INTO domains (id,title) VALUES (1,'" . dataen($yumi) . "')");
			} else {
				echo '2';
			}
		}
		echo $module;
		break;
	case 'dbtest':
		extract($_GET);
		$link = @mysqli_connect($dbhost, $dbuser, $dbpw, null, $dbport);
		if (!$link) {
			die('2');
		}
		$server_info = mysqli_get_server_info($link);
		if ($server_info < '5.0') {
			die('6');
		}
		if (!mysqli_select_db($link, $dbname)) {
			if (!@mysqli_query($link, "CREATE DATABASE `{$dbname}`")) {
				die('3');
			}
			mysqli_select_db($link, $dbname);
		}
		$tables = array();
		$query = mysqli_query($link, "SHOW TABLES FROM `{$dbname}`");
		while ($r = mysqli_fetch_row($query)) {
			$tables[] = $r[0];
		}
		if ($tables && in_array('admin', $tables)) {
			die('0');
		} else {
			die('1');
		}
		break;
}
function format_textarea($string)
{
	$chars = 'utf-8';
	return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($string, ENT_COMPAT, $chars)));
}
function _sql_execute($link, $sql, $r_tablepre = '', $s_tablepre = 'phpcms_')
{
	$sqls = _sql_split($link, $sql, $r_tablepre, $s_tablepre);
	if (is_array($sqls)) {
		foreach ($sqls as $sql) {
			if (trim($sql) != '') {
				mysqli_query($link, $sql);
			}
		}
	} else {
		mysqli_query($link, $sqls);
	}
	return true;
}
function _sql_split($link, $sql, $r_tablepre = '', $s_tablepre = 'phpcms_')
{
	if (mysqli_get_server_info($link) > '4.1') {
		$sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
	}
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach ($queriesarray as $query) {
		$ret[$num] = '';
		$queries = explode("\n", trim($query));
		$queries = array_filter($queries);
		foreach ($queries as $query) {
			$str1 = substr($query, 0, 1);
			if ($str1 != '#' && $str1 != '-') {
				$ret[$num] .= $query;
			}
		}
		$num++;
	}
	return $ret;
}
function dir_writeable($dir)
{
	$writeable = 0;
	if (is_dir($dir)) {
		if ($fp = @fopen($dir . DIRECTORY_SEPARATOR . "chkdir.test", 'w')) {
			@fclose($fp);
			@unlink($dir . DIRECTORY_SEPARATOR . "chkdir.test");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}
function writable_check($path)
{
	$dir = '';
	$is_writable = '1';
	if (!is_dir($path)) {
		return '0';
	}
	$dir = opendir($path);
	while (($file = readdir($dir)) !== false) {
		if ($file != '.' && $file != '..') {
			if (is_file($path . DIRECTORY_SEPARATOR . $file)) {
				if (!is_writable($path . DIRECTORY_SEPARATOR . $file)) {
					return '0';
				}
			} else {
				$dir_wrt = dir_writeable($path . DIRECTORY_SEPARATOR . $file);
				if ($dir_wrt == '0') {
					return '0';
				}
				$is_writable = writable_check($path . DIRECTORY_SEPARATOR . $file);
			}
		}
	}
	return $is_writable;
}
function set_config($config, $cfgfile)
{
	if (!$config || !$cfgfile) {
		return false;
	}
	$configfile = ALI_PATH . "admin" . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "database.php";
	if (!is_writable($configfile)) {
		echo 'Please chmod ' . $configfile . ' to 0777 !';
	}
	$pattern = $replacement = array();
	foreach ($config as $k => $v) {
		$v = trim($v);
		$configs[$k] = $v;
		$pattern[$k] = "/'" . $k . "'\s*=>\s*([']?)[^']*([']?)(\s*),/is";
		$replacement[$k] = "'" . $k . "' => \${1}" . $v . "\${2}\${3},";
	}
	$str = file_get_contents($configfile);
	$str = preg_replace($pattern, $replacement, $str);
	return file_put_contents($configfile, $str);
}
function get_url()
{
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}
function safe_replace($string)
{
	$string = str_replace('%20', '', $string);
	$string = str_replace('%27', '', $string);
	$string = str_replace('%2527', '', $string);
	$string = str_replace('*', '', $string);
	$string = str_replace('"', '&quot;', $string);
	$string = str_replace("'", '', $string);
	$string = str_replace('"', '', $string);
	$string = str_replace(';', '', $string);
	$string = str_replace('<', '&lt;', $string);
	$string = str_replace('>', '&gt;', $string);
	$string = str_replace("{", '', $string);
	$string = str_replace('}', '', $string);
	$string = str_replace('\\', '', $string);
	return $string;
}
function getdomain($url)
{
	$host = strtolower($url);
	if (strpos($host, '/') !== false) {
		$parse = @parse_url($host);
		$host = $parse['host'];
	}
	$topleveldomaindb = array('com', 'cn', 'net', 'org', 'gov', 'edu', 'int', 'mil', 'biz', 'info', 'tv', 'pro', 'name', 'museum', 'coop', 'aero', 'cc', 'sh', 'me', 'asia', 'au', 'me', 'cm', 'hk', 'li', 'tw', 'us', 'com.cn', 'net.cn', 'org.cn', 'gov.cn', 'top', 'club', 'xyz', 'wang', 'win', 'site', 'cn.com', 'pw', 'red', 'online', 'mobi', 'bid', 'vip', 'ren', 'gs', 'cx', 'space', 'date', 'kim', 'website', 'live', 'sale', 'run', 'gold', 'help', 'game', 'loan');
	$str = '';
	foreach ($topleveldomaindb as $v) {
		$str .= ($str ? '|' : '') . $v;
	}
	$matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
	if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
		$domain = $matchs['0'];
	} else {
		$domain = $host;
	}
	return $domain;
}