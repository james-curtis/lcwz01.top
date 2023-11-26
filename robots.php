<?php

header('HTTP/1.1 200 OK');
error_reporting(0);
require 'admin/inc/lic.php';
require 'admin/inc/spider.php';
header('Content-type: text/plain');
$robots = "User-agent: *\nAllow:/\n";
$robots .= "Sitemap: http://www." . $yumi . "/sitemap.xml";
echo $robots;