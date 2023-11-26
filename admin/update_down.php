<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$ver = isset($_GET['ver']) ? $_GET['ver'] : "";
$ver_date = isset($_GET['ver_date']) ? $_GET['ver_date'] : "";
$zip = isset($_GET['zip']) ? $_GET['zip'] : "";
$url = "http://vip.alizhizhuchi.top" . $zip;
if ($ver && $ver_date && $zip) {
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
		$zip = new ZipArchive();
		if ($zip->open($dir) === TRUE) {
			$zip->extractTo('upload/' . $ver);
			$zip->close();
			recurse_copy($upload_dir . $ver, "../");
			$file = file_exists("../ver_update.php");
			if ($file) {
				_sock("http://" . $_SERVER['HTTP_HOST'] . "/ver_update.php");
			}
			unlink($dir);
			header('Location: update.php');
		} else {
			echo '解压失败';
		}
	} else {
		echo '下载失败';
	}
}