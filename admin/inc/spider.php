<?php


class spider
{
	function getHttp($var, $default_var = false)
	{
		static $http;
		if (!isset($http)) {
			$http = array_merge($_GET, $_POST);
		}
		if (isset($http[$var])) {
			return addslashes($http[$var]);
		} else {
			return $default_var;
		}
	}
	function getIp()
	{
		if ($ip = getenv('HTTP_CLIENT_IP')) {
		} elseif ($ip = getenv('HTTP_X_FORWARDED_FOR')) {
		} elseif ($ip = getenv('HTTP_X_FORWARDED')) {
		} elseif ($ip = getenv('HTTP_FORWARDED_FOR')) {
		} elseif ($ip = getenv('HTTP_FORWARDED')) {
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	function writeLog($engine, $agent = '')
	{
		global $mysqli;
		if ($engine && $engine != 'other' && $engine != 'yidong') {
			$table = "spider_" . date('Ymd');
			$result = $mysqli->query("SHOW TABLES LIKE '" . $table . "' ")->fetch_row();
			if (!$result) {
				$greatok = $mysqli->query("CREATE TABLE `" . $table . "` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, `ssyq` varchar(255) NOT NULL, `fwdz` varchar(255) NOT NULL, `lldz` varchar(255), `ipdz` varchar(255) NOT NULL, `age` text, `rq` int(11) NOT NULL, `ipinfo` text, PRIMARY KEY (`id`), KEY `ssyq` (`ssyq`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8");
				if (!$greatok) {
					$mysqli->query("CREATE TABLE `" . $table . "` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, `ssyq` varchar(255) NOT NULL, `fwdz` varchar(255) NOT NULL, `lldz` varchar(255), `ipdz` varchar(255) NOT NULL, `age` text, `rq` int(11) NOT NULL, `ipinfo` text, PRIMARY KEY (`id`), KEY `ssyq` (`ssyq`) USING BTREE) ENGINE=MyISAM DEFAULT CHARSET=utf8");
				}
				$table = "spider_" . date("Ymd", strtotime("-1 day"));
				$baidu = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Baidu'")->fetch_object()->count;
				$google = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Google'")->fetch_object()->count;
				$bing = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Bing'")->fetch_object()->count;
				$yahoo = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Yahoo'")->fetch_object()->count;
				$sogou = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Sogou'")->fetch_object()->count;
				$haosou = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Haosou'")->fetch_object()->count;
				$mobile = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Mobile'")->fetch_object()->count;
				$shenma = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Shenma'")->fetch_object()->count;
				$yitao = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Yitao'")->fetch_object()->count;
				$esou = $mysqli->query("select count(*) as count from " . $table . " where ssyq='Esou'")->fetch_object()->count;
				$count = $baidu + $google + $bing + $yahoo + $sogou + $haosou + $mobile + $shenma + $yitao + $esou;
				$mysqli->query("insert into spider (rq,count,baidu,google,bing,yahoo,sogou,haosou,mobile,shenma,yitao,esou) value(" . strtotime("-1 day") . "," . $count . "," . $baidu . "," . $google . "," . $bing . "," . $yahoo . "," . $sogou . "," . $haosou . "," . $mobile . "," . $shenma . "," . $yitao . "," . $esou . ")");
			} else {
				$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '无来路';
				$ipdz = $this->getIp();
				$time = time();
				$sql = "insert into {$table} (`ssyq`,`fwdz`,`lldz`,`ipdz`,`age`,`rq`,`ipinfo`) values ('{$engine}','http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}','{$ref}','{$ipdz}','{$agent}',{$time},'" . convertip($ipdz) . "')";
				$mysqli->query($sql);
			}
		}
	}
	function record()
	{
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$act = isset($_GET['act']) ? $_GET['act'] : "";
		$spider = get_naps_bot();
		if ($spider && $act != "liyunpeng") {
			$this->writeLog($spider, $agent);
		}
	}
}
$spider = new spider();
$spider->record();
unset($spider);