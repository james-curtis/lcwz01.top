<?php

/* 
-- mzphp 混淆加密：https://git.oschina.net/mz/mzphp2
*/
error_reporting(0);
ignore_user_abort(true);
set_time_limit(0);
date_default_timezone_set('PRC');
require 'data.php';
include 'function.php';
include 'curl.class.php';
$duankou = $_SERVER['SERVER_PORT'];
$yuming = $_SERVER['HTTP_HOST'];
$yuming = str_replace(':' . $duankou, '', $yuming);
$sql = 'select title from waiurl order by id desc';
$result = $mysqli->query($sql);
$i = $result->num_rows;
$mysqli->query('update config set wainum=wainum+' . $i);
while ($row = $result->fetch_assoc()) {
	$waitui = str_replace('{domains}', $yuming, $row['title']);
	$arr[] = array('url' => $waitui);
}
$ac = new AsyncCURL();
$ac->set_param($arr);
$ret = $ac->send();