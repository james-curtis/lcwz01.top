<?php


header('Content-type:text/html;charset=utf-8');
ini_set('memory_limit', '-1');
ignore_user_abort(true);
set_time_limit(0);
require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
    header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : "";
if ($_FILES["file"]["type"] == "text/plain" && $_FILES["file"]["size"] < 2196576) {
    if ($_FILES["file"]["error"] > 0) {
        echo "文件错误,上传失败";
    } else {
        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
        $myFile = file("upload/" . $_FILES["file"]["name"]) or die("上传失败");
        $sql2 = "";
        $count = count($myFile);
        if ($count > 5000)
        {
            die("数据量过大，上传失败");
        }
//        $zheng = floor($count / 1000);
//        $yu = $count % 1000;
        /* 	for ($i = 0; $i < $count; $i++) {
          $encode = mb_detect_encoding($myFile[$i], array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                $str = mb_convert_encoding($myFile[$i], 'UTF-8', $encode);
                $str = trim($str);
                $str = str_replace(array("\r\n", "\r", "\n", "\t", "　"), "", $str);
                if ($str) {
                    if ($act == 'domains') {
                        $chars = array("a", "b", "c", "d", "e");
                        $group = $chars[mt_rand(0, 4)];
                        $pc_moban = moban_ok('moban');
                        $pc_moban_rand = array_rand($pc_moban, 1);
                        $pc_moban_id = $pc_moban[$pc_moban_rand]['id'];
                        $mo_moban = moban_ok('mobile');
                        $mo_moban_rand = array_rand($mo_moban, 1);
                        $mo_moban_id = $mo_moban[$mo_moban_rand]['id'];
                        $mysqli->query("insert into " . $act . " (`title`,`groupname`,`pc_moban_id`,`mo_moban_id`) values('" . dataen($str) . "','" . $group . "',{$pc_moban_id},{$mo_moban_id})");
                    } elseif ($act == 'keywords') {
                        $PingYing = new GetPingYing();
                        $pinyin = $PingYing->getAllPY($myFile[$i]);
                        $pinyin = str_replace(array("\r\n", "\r", "\n"), "", $pinyin);
                        $sql = "insert into " . $act . " (`title`,`pinyin`) values('" . $str . "','" . $pinyin . "')";
                        $mysqli->query($sql);
                    } elseif ($act == 'url') {
                        $str = trim($str);
                        if (!(strpos($str, 'http://') !== false)) {
                            $str = "http://" . $str;
                        }
                        $sql = "insert into " . $act . " (`title`,`user_id`) values('" . $str . "',1)";
                        $mysqli->query($sql);
                    } elseif ($act == 'qurl') {
                        $str = trim($str);
                        if (!(strpos($str, 'http://') !== false)) {
                            $str = "http://" . $str;
                        }
                        $info = explode('|', $str);
                        if (count($info) == 2) {
                            $sql = "insert into " . $act . " (`title`,`text`,`user_id`) values('" . $info[0] . "','" . $info[1] . "',1)";
                            $mysqli->query($sql);
                        }
                    } elseif ($act == 'key_jump') {
                        $str = trim($str);
                        if (!(strpos($str, 'http://') !== false)) {
                            $str = "http://" . $str;
                        }
                        $info = explode('|', $str);
                        if (count($info) == 2) {
                            $sql = "insert into " . $act . " (`title`,`jumpkey`) values('" . $info[0] . "','" . $info[1] . "')";
                            $mysqli->query($sql);
                        }
                    } elseif ($act == 'weiyuanchuang') {
                        $str = trim($str);
                        $info = explode('|', $str);
                        if (count($info) == 2) {
                            $sql = "insert into " . $act . " (`title`,`new`) values('" . $info[0] . "','" . $info[1] . "')";
                            $mysqli->query($sql);
                        }
                    } else {
                        $sql2 .= "('" . $str . "'),";
                    }
                }
                if (($i + 1) % 1000 == 0) {
                    $sql = "insert into " . $act . " (`title`) values";
                    $sql .= substr($sql2, 0, -1);
                    $mysqli->query($sql);
                    $sql2 = "";
                }
            } */
        for ($i = 0; $i < $count; $i++) {
            $encode = mb_detect_encoding($myFile[$i], array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
            $str = mb_convert_encoding($myFile[$i], 'UTF-8', $encode);
            $str = trim($str);
            $str = str_replace(array("\r\n", "\r", "\n", "\t"), "", $str);
            if ($str) {
                if ($act == 'domains') {
                    $chars = array("a", "b", "c", "d", "e");
                    $group = $chars[mt_rand(0, 4)];
                    $pc_moban = moban_ok('moban');
                    $pc_moban_rand = array_rand($pc_moban, 1);
                    $pc_moban_id = $pc_moban[$pc_moban_rand]['id'];
                    $mo_moban = moban_ok('mobile');
                    $mo_moban_rand = array_rand($mo_moban, 1);
                    $mo_moban_id = $mo_moban[$mo_moban_rand]['id'];
                    $mysqli->query("insert into " . $act . " (`title`,`groupname`,`pc_moban_id`,`mo_moban_id`) values('" . dataen($str) . "','" . $group . "',{$pc_moban_id},{$mo_moban_id})");
                } elseif ($act == 'keywords') {
                    $PingYing = new GetPingYing();
                    $pinyin = $PingYing->getAllPY($myFile[$i]);
                    $pinyin = str_replace(array("\r\n", "\r", "\n"), "", $pinyin);
                    if (empty($pinyin))
                    {
                        var_dump($myFile[$i],$pinyin);
                        continue;
                    }
                    $sql = "insert into " . $act . " (`title`,`pinyin`) values('" . $str . "','" . $pinyin . "')";
                    $mysqli->query($sql);
                } elseif ($act == 'url') {
                    $str = trim($str);
                    if (!(strpos($str, 'http') !== false)) {
                        $str = "http://" . $str;
                    }
                    $sql = "insert into " . $act . " (`title`,`user_id`) values('" . $str . "',1)";
                    $mysqli->query($sql);
                } elseif ($act == 'qurl') {
                    $str = trim($str);
                    if (!(strpos($str, 'http') !== false)) {
                        $str = "http://" . $str;
                    }
                    $info = explode('|', $str);
                    if (count($info) == 2) {
                        $sql = "insert into " . $act . " (`title`,`text`,`user_id`) values('" . $info[0] . "','" . $info[1] . "',1)";
                        $mysqli->query($sql);
                    }
                } elseif ($act == 'key_jump') {
                    $str = trim($str);
                    if (!(strpos($str, 'http') !== false)) {
                        $str = "http://" . $str;
                    }
                    $info = explode('|', $str);
                    if (count($info) == 2) {
                        $sql = "insert into " . $act . " (`title`,`jumpkey`) values('" . $info[0] . "','" . $info[1] . "')";
                        $mysqli->query($sql);
                    }
                } elseif ($act == 'weiyuanchuang') {
                    $str = trim($str);
                    $info = explode('|', $str);
                    if (count($info) == 2) {
                        $sql = "insert into " . $act . " (`title`,`new`) values('" . $info[0] . "','" . $info[1] . "')";
                        $mysqli->query($sql);
                    }
                } else {
                    $sql2 .= "('" . $str . "'),";
                }
            }
            $sql = "insert into " . $act . " (`title`) values";
            $sql .= substr($sql2, 0, -1);
            $mysqli->query($sql);
            $sql2 = "";
        }
        unlink('upload/' . $_FILES["file"]["name"]);
        header('Location: info.php?act=' . $act);
    }
} else {
    echo '格式错误或文件大于2M';
}

//转换utf-8;
function characet($data)
{
    if (!empty($data)) {
        $fileType = mb_detect_encoding($data, array('UTF-8', 'GBK', 'LATIN1', 'BIG5'));
        if ($fileType != 'UTF-8') {
            $data = mb_convert_encoding($data, 'utf-8', $fileType);
        }
    }
    return $data;
}