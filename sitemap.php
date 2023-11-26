<?php

/* 
-- mzphp 混淆加密：https://git.oschina.net/mz/mzphp2
*/
header('HTTP/1.1 200 OK');
error_reporting(0);
require 'admin/inc/lic.php';
require 'admin/inc/spider.php';
define('DIR', dirname(__FILE__));
$moban_map = file_get_contents(DIR . '/static/map.html');
echo moban($moban_map, 3);