<?php
header('HTTP/1.1 200 OK');
require 'admin/inc/lic.php';
require 'admin/inc/spider.php';
header('Content-type: text/xml');
$str = '<?xml version="1.0" encoding="UTF-8"?>';
$str .= '<urlset';
$str .= '    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
$str .= '    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
$str .= '    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9';
$str .= '       http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
for ($i = 0; $i < 1000; $i++) {
	$str .= '<url>';
	$str .= '<loc>http://' . $yuming . '/' . randKey(5) . '.html</loc>';
	$str .= '<priority>0.7</priority>';
	$str .= '<lastmod>' . date('Y-m-d') . '</lastmod>';
	$str .= '<changefreq>always</changefreq>';
	$str .= '</url>';
}
$str .= '</urlset>';
echo $str;