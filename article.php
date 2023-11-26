<?php
header('HTTP/1.1 200 OK');
error_reporting(0);
require 'admin/inc/lic.php';
require 'admin/inc/spider.php';
define('DIR', dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)));
if ($ssyq == false) {
    if ($config['tongji']) {
        $tiaozhuan = $config['jump'];
        echo $config['tongji'];
        echo "<script>window.location.href='{$tiaozhuan}';</script>";
    } else {
        header('Location: ' . $config['jump']);
    }
    die;
} else {
    $urlaa = $_SERVER['REQUEST_URI'];
    if (stristr($urlaa, "sitemap.html")) {
        echo file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/sitemap.php');
        die;
    } elseif (stristr($urlaa, "sitemap.xml")) {
        header("Content-type: text/xml");
        echo file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/xml.php');
        die;
    } elseif (stristr($urlaa, "robots.txt")) {
        header("Content-type: text/plain");
        echo file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/robots.php');
        die;
    } else {
        $moban_id = domain_moban_id($yumi);
        if ($ssyq == 'mobile' || $ssyq == 'yidong') {
            $mo_moban_id = $moban_id['mo_moban_id'];
            if ($mo_moban_id) {
                $mo_moban = moban_t_n($mo_moban_id);
                $moban_title = $mo_moban['title'];
            } else {
                $title = " and title like 'mobile%' ";
                $moban_title = $mysqli->query("select title from templates where ok=1 {$title} order by rand() limit 1")->fetch_object()->title;
            }
        } else {
            $pc_moban_id = $moban_id['pc_moban_id'];
            if ($pc_moban_id) {
                $pc_moban = moban_t_n($pc_moban_id);
                $moban_title = $pc_moban['title'];
            } else {
                $title = " and title like 'moban%' ";
                $moban_title = $mysqli->query("select title from templates where ok=1 {$title} order by rand() limit 1")->fetch_object()->title;
            }
        }
        $moban_neirong = file_get_contents(DIR . "/templates/" . $moban_title . "/neirong.html");
        $moban_neirong = str_replace("{模板}", "/templates/" . $moban_title, $moban_neirong);
        $support .= "|模板:" . $moban_title;
        echo moban($moban_neirong, 2);
    }
}