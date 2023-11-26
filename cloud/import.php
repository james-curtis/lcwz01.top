<?php
header('Content-type:text/html;charset=utf-8');
ini_set('memory_limit', '-1');
ignore_user_abort(true);
set_time_limit(0);
require '../admin/inc/data.php';
include '../admin/inc/function.php';
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['user_id']) || empty($_SESSION['is_login'])) {
	header("Location: login.php");
}
if ($_FILES["file"]["type"] == "text/plain" && $_FILES["file"]["size"] < 1048576) {
	if ($_FILES["file"]["error"] > 0) {
		echo "文件错误,上传失败";
	} else {
		move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
		$file = fopen("upload/" . $_FILES["file"]["name"], "r") or die("文件错误");
		while (!feof($file)) {
			$line = fgets($file);
			$str = mb_convert_encoding($line, 'utf-8', 'gb2312');
			$str = trim($str);
			$str = str_replace(array("\r\n", "\r", "\n", "\t", "　"), "", $str);
			if ($str) {
				$result = $mysqli->query("select num from admin where id=" . $_SESSION['user_id']);
				$user = $result->fetch_assoc();
				$count = $mysqli->query("select count(*) as count from url where user_id=" . $_SESSION['user_id'])->fetch_object()->count;
				if ($count >= $user['num']) {
					echo "<script>alert('数量已达到VIP限制,请升级您的帐号');self.location.href='index.php';</script>";
					die;
				} else {
					if (!(strpos($str, 'http://') !== false)) {
						$str = "http://" . $str;
					}
					$mysqli->query("insert into url (title,user_id) VALUES ('" . $str . "'," . $_SESSION['user_id'] . ")");
				}
			}
		}
		fclose($file);
		unlink('upload/' . $_FILES["file"]["name"]);
		header('Location: index.php');
	}
} else {
	echo '格式错误或文件大于1M';
}