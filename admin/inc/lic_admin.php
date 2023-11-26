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
require 'data.php';
require 'pinyin.php';
include 'function.php';
include 'key.php';
include 'curl.class.php';
define('SYSTEM_NAME', 'AliSpider');
define('SITE_NAME', '阿里蜘蛛池');
$config = config_list();
if(!$config['title']){
	file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/?a=shouquan');
	$config = config_list();
}