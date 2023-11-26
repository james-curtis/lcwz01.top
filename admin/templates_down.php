<?php


set_time_limit(0);
require 'inc/lic_admin.php';
$title = isset($_GET['title']) ? $_GET['title'] : "";
$name = isset($_GET['name']) ? $_GET['name'] : "";
$url = isset($_GET['url']) ? $_GET['url'] : "";
if ($title && $name) {
	$url = urldecode($url);
	$fname = basename("{$url}");
	$upload_dir = "upload/";
	$dir = $upload_dir . $fname;
	$file = fopen($url, "rb");
	if ($file) {
		$newf = fopen($dir, "wb");
		if ($newf) {
			while (!feof($file)) {
				fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
			}
		}
	}
	if ($file) {
		fclose($file);
	}
	if ($newf) {
		fclose($newf);
		if (class_exists('ZipArchive')) {
			$zip = new ZipArchive();
			if ($zip->open($dir) === TRUE) {
				$zip->extractTo('../templates/' . $title);
				$zip->close();
				$result = $mysqli->query("select title from templates where title='" . $title . "'");
				if (!$result->num_rows) {
					$mysqli->query("insert into templates (`title`,`ok`,`name`) VALUES ('" . $title . "',0,'" . $name . "')");
				}
				if (unlink($dir)) {
					echo "下载成功";
				}
			} else {
				echo '解压失败';
			}
		} else {
			echo '请开启ZipArchive扩展';
		}
	} else {
		echo '下载失败';
	}
}