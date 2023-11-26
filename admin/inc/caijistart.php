<?php


error_reporting(0);
ignore_user_abort(true);
set_time_limit(0);
date_default_timezone_set('PRC');
require 'data.php';
include 'function.php';
include 'key.php';
ini_set('max_execution_time', "0");
require '../QueryList/vendor/autoload.php';

use QL\QueryList;

$caijicount = $mysqli->query("select count(*) as count from c_title order by id desc")->fetch_object()->count;
if ($caijicount > 50000) {
    $mysqli->query("truncate table c_title");
}
$result = $mysqli->query("select * from c_yuan where ok=1 order by id desc");
$zs = microtime(true);
while ($row = $result->fetch_assoc()) {
    $startime = microtime(true);
    $links = array();
    for ($i = 2; $i < 12; $i++) {
        $page = str_replace("[i]", $i, $row['url']);
        $regarr = json_decode($row['reg_t'], 1);
        $reg = array('link' => $regarr);
        $rang = $row['rang_t'];
        $ql = QueryList::Query($page, $reg, $rang);
        $data = $ql->getData();
        foreach ($data as $v) {
            $links[] = $v['link'];
        }
    }
    QueryList::run('Multi', array('list' => $links, 'curl' => array('opt' => array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_FOLLOWLOCATION => true, CURLOPT_AUTOREFERER => true), 'maxThread' => 100, 'maxTry' => 1), 'success' => function ($a) {
        $regarr = json_decode($GLOBALS['row']['reg_c'], 1);
        $reg = $regarr;
        $rang = $GLOBALS['row']['rang_c'];
        $ql = QueryList::Query($a['content'], $reg, $rang, $GLOBALS['row']['out_c'], $GLOBALS['row']['in_c']);
        $data = $ql->getData();
        $c_title = $data[0]['title'];
        $c_content = $data[0]['content'];
        if ($c_title && mb_strlen(strip_tags($c_content)) > 100) {
            $config = config_list();
            if ($config['wyc']) {
                $wyc = weiyuanchuang();
                if ($wyc) {
                    $c_title = strtr($c_title, $wyc);
                    $c_content = strtr($c_content, $wyc);
                }
            }
            $GLOBALS['mysqli']->query("insert into c_title (title,content) value('{$c_title}','{$c_content}')");
        }
    }));
    $endtime = microtime(true);
    usleep(100);
}
$ze = microtime(true);
echo "|" . ($ze - $zs);