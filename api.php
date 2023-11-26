<?php

header('HTTP/1.1 200 OK');
error_reporting(0);
require 'admin/inc/data.php';
require 'admin/inc/pinyin.php';
$apikey = isset($_POST['apikey']) ? $_POST['apikey'] : "";
$type = isset($_POST['type']) ? $_POST['type'] : "";
$data = isset($_POST['data']) ? $_POST['data'] : "";
$apikey_ok = substr(sha1($config['title']), 20);
$mes = "提交失败";
if ($apikey && $type && $data && $apikey == $apikey_ok) {
	if ($type == 'suoyinchi') {
		if (!(strpos($data, 'http') !== false)) {
			$data = "http://" . $data;
		}
		$mysqli->query("insert into url (`title`,`user_id`) values('" . $data . "',1)");
		if ($mysqli->insert_id) {
			$mes = "提交成功";
		}
	}
	if ($type == 'quanzhongchi') {
		$str = trim($data);
		if (!(strpos($str, 'http') !== false)) {
			$str = "http://" . $str;
		}
		$info = explode('|', $str);
		if (count($info) == 2) {
			$mysqli->query("insert into qurl (`title`,`text`,`user_id`) values('" . $info[0] . "','" . $info[1] . "',1)");
			if ($mysqli->insert_id) {
				$mes = "提交成功";
			}
		}
	}
	if ($type == 'guanjianci') {
        $PingYing = new GetPingYing();
		$pinyin = $PingYing->getAllPY($data);
		$sql = "insert into keywords (`title`,`pinyin`) values('" . $data . "','" . $pinyin . "')";
		$mysqli->query($sql);
		if ($mysqli->insert_id) {
			$mes = "提交成功";
		}
	}
}
echo $mes;