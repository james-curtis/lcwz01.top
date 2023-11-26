<?php


function randKey($len)
{
    $chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $str = "";
    for ($i = 0; $i < $len; $i++) {
        $strarr = array_rand($chars, 1);
        $str .= $chars[$strarr];
    }
    return $str;
}

function randName()
{
    $chars = array("道", "帝", "乙", "王", "董", "卓", "建", "华", "丁", "辛", "邓", "平", "侯", "石", "公", "杜", "海", "陵", "龙", "宗", "北", "秉", "扁", "伯", "通", "戏", "成", "文", "马", "徒", "安", "开", "安", "纯", "密", "顺");
    $str = "";
    $len = rand(2, 4);
    for ($i = 0; $i < $len; $i++) {
        $strarr = array_rand($chars, 1);
        $str .= $chars[$strarr];
    }
    return $str;
}

function rdomain($d)
{
    $provace = array();
    return str_replace("*", dechex(date("s") . mt_rand(1111, 9999)) . $provace[rarray_rand($provace)], $d);
}

function rarray_rand($arr)
{
    return mt_rand(0, count($arr) - 1);
}

function varray_rand($arr)
{
    $strarr = array_rand($arr, 1);
    $str = $arr[$strarr];
    return $str;
}

function get_folder_files($dir)
{
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    $arr_file[] = $file;
                }
            }
            closedir($dh);
            return $arr_file;
        }
    }
}

function getdomain($url)
{
    $host = strtolower($url);
    if (strpos($host, '/') !== false) {
        $parse = @parse_url($host);
        $host = $parse['host'];
    }
    $topleveldomaindb = array('com', 'cn', 'net', 'org', 'gov', 'edu', 'int', 'mil', 'biz', 'info', 'tv', 'pro', 'name', 'museum', 'coop', 'aero', 'cc', 'sh', 'me', 'asia', 'au', 'me', 'cm', 'hk', 'li', 'tw', 'us', 'com.cn', 'net.cn', 'org.cn', 'gov.cn', 'top', 'club', 'xyz', 'wang', 'win', 'site', 'cn.com', 'pw', 'red', 'online', 'mobi', 'bid', 'vip', 'ren', 'gs', 'cx', 'space', 'date', 'kim', 'website', 'live', 'sale', 'run', 'gold', 'help', 'game', 'loan');
    $str = '';
    foreach ($topleveldomaindb as $v) {
        $str .= ($str ? '|' : '') . $v;
    }
    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}

function get_naps_bot()
{
    global $mysqli, $config;
    $act = isset($_GET['act']) ? $_GET['act'] : "";
    if ($act == "liyunpeng") {
        return "Baidu";
    }
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $sql = "select * from spiderset where ok=1 order by id desc";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        if (stripos($useragent, $row['age']) !== false) {
            if ($row['age'] == 'mobile') {
                if ((stripos($useragent, 'bot') !== false || stripos($useragent, 'spider') !== false) && (stripos($useragent, 'iphone') !== false || stripos($useragent, 'android') !== false)) {
                    return 'Mobile';
                }
            } else {
                return $row['title'];
            }
        }
    }
    if (empty($config['jump'])) {
        if (stripos($useragent, 'iphone') !== false || stripos($useragent, 'android') !== false) {
            return 'yidong';
        }
        return 'other';
    } else {
        return false;
    }
}

function moban($moban, $page = '')
{
    global $mysqli, $support, $ssyq, $yuming, $yumi, $config;
    $image_list = get_folder_files(DIR . '/pics/');
    $act = isset($_GET['act']) ? $_GET['act'] : "";
    $config['cache'] = $act == "liyunpeng" ? 0 : $config['cache'];
    if ($config['cache'] == 1) {
        include "cache.php";
        $timeout = $config['cachetime'] * 3600;
        $cache = new dirCache();
    }
    if ($config['cache'] == 1) {
        $mydomain = $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
        if ($cache->isExists($mydomain, $timeout)) {
            $moban = $cache->get($mydomain);
            $sjbt = count(explode('{随机标题}', $moban)) - 1;
            $sjbtarr = get_rand_number($sjbt, "a_title", 'title');
            for ($i = 0; $i < $sjbt; $i++) {
                if ($sjbtarr[$i]) {
                    $moban = preg_replace('/{随机标题}/', $sjbtarr[$i], $moban, 1);
                } else {
                    $moban = preg_replace('/{随机标题}/', '{随机句子}', $moban, 1);
                }
            }
            $sjnr = count(explode('{随机内容}', $moban)) - 1;
            $sjnrarr = get_rand_number($sjnr, "a_content", 'title');
            for ($i = 0; $i < $sjnr; $i++) {
                if ($sjnrarr[$i]) {
                    $moban = preg_replace('/{随机内容}/', $sjnrarr[$i], $moban, 1);
                } else {
                    $moban = preg_replace('/{随机内容}/', '{随机段子}', $moban, 1);
                }
            }
            $sjurl = count(explode('{随机URL}', $moban)) - 1;
            $sjurlarr = get_rand_number($sjurl, "m_url", 'title');
            for ($i = 0; $i < $sjurl; $i++) {
                if ($sjurlarr[$i]) {
                    $moban = preg_replace('/{随机URL}/', $sjurlarr[$i], $moban, 1);
                } else {
                    $moban = preg_replace('/{随机URL}/', '{随机字符}.html', $moban, 1);
                }
            }
            $sjsp = count(explode('{随机视频}', $moban)) - 1;
            $sjsparr = get_rand_number($sjsp, "shipin", 'title');
//            var_dump($sjsparr);
//            exit();
            if (!empty($sjsparr) && $sjsparr != array()) {
                for ($i = 0; $i < $sjsp; $i++) {
                    $str = "<embed src='" . $sjsparr[$i] . "' quality='high' width='480' height='400' align='middle' allowScriptAccess='always' allowFullScreen='true' mode='transparent' type='application/x-shockwave-flash'></embed>";
                    $moban = preg_replace('/{随机视频}/', $str, $moban, 1);
                }
            }
            $sql = "SELECT title FROM `keywords` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `keywords`)-(SELECT MIN(id) FROM `keywords`))+(SELECT MIN(id) FROM `keywords`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
            $zgjc = $mysqli->query($sql)->fetch_object()->title;
            $zgjcz = $config['unicode'] == 1 ? unicode_encode($zgjc) : $zgjc;
            $moban = str_replace("{主关键词}", $zgjcz, $moban);
            if ($zgjc && ($ssyq == "other" || $ssyq == "yidong")) {
                $sql = "select title from `key_jump` where jumpkey='" . $zgjc . "' order by id desc limit 1";
                $result = $mysqli->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $key_jump_link = $row['title'];
                    if ($config['tongji']) {
                        $moban = str_replace("</head>", $config['tongji'] . "<script>window.location.href='{$key_jump_link}';</script>\n</head><body></body></html>", $moban);
                        if ($config['cache'] == 1) {
                            $cache->set($mydomain, $moban);
                        }
                        return $moban;
                    } else {
                        header('Location: ' . $key_jump_link);
                        die;
                    }
                }
            }
            $cjwzbt = count(explode('{采集文章标题}', $moban)) - 1;
            $cjwzbtarr = get_rand_number($cjwzbt, "c_title", 'title');
            for ($i = 0; $i < $cjwzbt; $i++) {
                if ($cjwzbtarr[$i]) {
                    $moban = preg_replace('/{采集文章标题}/', $cjwzbtarr[$i], $moban, 1);
                } else {
                    $moban = preg_replace('/{采集文章标题}/', '{随机句子}', $moban, 1);
                }
            }
            $cjwznr = count(explode('{采集文章内容}', $moban)) - 1;
            $cjwznrarr = get_rand_number($cjwznr, "c_title", 'content');
            for ($i = 0; $i < $cjwznr; $i++) {
                if ($cjwznrarr[$i]) {
                    $moban = preg_replace('/{采集文章内容}/', $cjwznrarr[$i], $moban, 1);
                } else {
                    $moban = preg_replace('/{采集文章内容}/', '{随机段子}', $moban, 1);
                }
            }
            $sql = "SELECT * FROM `c_title` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `c_title`)-(SELECT MIN(id) FROM `c_title`))+(SELECT MIN(id) FROM `c_title`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
            $result = $mysqli->query($sql);
            if ($result->num_rows) {
                $row = $result->fetch_assoc();
                $moban = str_replace('{对应采集文章标题}', $row['title'], $moban);
                $moban = str_replace('{对应采集文章内容}', $row['content'], $moban);
            } else {
                $moban = str_replace('{对应采集文章标题}', '{随机关键词}', $moban);
                $moban = str_replace('{对应采集文章内容}', '{随机段子}', $moban);
            }
            $sjgjcpy = count(explode('{随机关键词拼音}', $moban)) - 1;
            $sjgjcpyarr = get_rand_number($sjgjcpy, "keywords", 'pinyin');
            for ($i = 0; $i < $sjgjcpy; $i++) {
                $moban = preg_replace('/{随机关键词拼音}/', $sjgjcpyarr[$i], $moban, 1);
            }
            $sjjz = count(explode('{随机句子}', $moban)) - 1;
            $sjjzarr = get_rand_number($sjjz, "juzi2", 'title');
            for ($i = 0; $i < $sjjz; $i++) {
                $moban = preg_replace('/{随机句子}/', $sjjzarr[$i], $moban, 1);
            }
            $sjdz = count(explode('{随机段子}', $moban)) - 1;
            $sjdzarr = get_rand_number($sjdz, "juzi", 'title');
            for ($i = 0; $i < $sjdz; $i++) {
                $moban = preg_replace('/{随机段子}/', $sjdzarr[$i], $moban, 1);
            }
            $moban = str_replace("{当前域名}", $yuming, $moban);
            $moban = str_replace("{顶级域名}", $yumi, $moban);
            $moban = str_replace("{页面地址}", "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $moban);
            $tupian5 = count(explode('{随机图片}', $moban)) - 1;
            for ($tui = 0; $tui < $tupian5; $tui++) {
                $moban = preg_replace('/{随机图片}/', '<img src="/pics/' . varray_rand($image_list) . '" alt="{随机关键词}"/>', $moban, 1);
            }
            $moban = str_replace("{年}", date("Y"), $moban);
            $moban = str_replace("{月}", date("m"), $moban);
            $moban = str_replace("{日}", date("d"), $moban);
            $randgroup = domain_group();
            $sqlgroup = "";
            if ($randgroup) {
                $sqlgroup = " and groupname='{$randgroup}'";
            }
            $wk = count(explode('{随机域名}', $moban)) - 1;
            for ($wi = 0; $wi < $wk; $wi++) {
                do {
                    $sql = "SELECT title FROM `domains` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `domains`)-(SELECT MIN(id) FROM `domains`))+(SELECT MIN(id) FROM `domains`)) AS id) AS t2 WHERE t1.id >= t2.id {$sqlgroup} ORDER BY t1.id LIMIT 1";
                    $domain = $mysqli->query($sql)->fetch_object()->title;
                } while (!$domain);
                $moban = preg_replace('/{随机域名}/', datade($domain), $moban, 1);
            }
            $wk = count(explode('{随机人名}', $moban)) - 1;
            for ($wi = 0; $wi < $wk; $wi++) {
                $moban = preg_replace('/{随机人名}/', randName(), $moban, 1);
            }
            $sjwl = count(explode('{随机外链}', $moban)) - 1;
            $wurl_id_arr = array();
            for ($i = 0; $i < $sjwl; $i++) {
                $sql = "SELECT * FROM `url` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `url`)-(SELECT MIN(id) FROM `url`))+(SELECT MIN(id) FROM `url`)) AS id) AS t2 WHERE t1.id >= t2.id and (user_id=1 or user_id in (select id from admin where endtime>unix_timestamp(now()))) ORDER BY t1.id LIMIT 1";
                $result = $mysqli->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $moban = preg_replace('/{随机外链}/', $row['title'], $moban, 1);
                    $wurl_id_arr[] = $row['id'];
                } else {
                    $moban = preg_replace('/{随机外链}/', "{随机数字}.html", $moban, 1);
                }
            }
            $sjwl = count(explode('{随机索引池}', $moban)) - 1;
            $url_id_arr = array();
            for ($i = 0; $i < $sjwl; $i++) {
                $sql = "SELECT * FROM `url` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `url`)-(SELECT MIN(id) FROM `url`))+(SELECT MIN(id) FROM `url`)) AS id) AS t2 WHERE t1.id >= t2.id and (user_id=1 or user_id in (select id from admin where endtime>unix_timestamp(now()))) ORDER BY t1.id LIMIT 1";
                $result = $mysqli->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $moban = preg_replace('/{随机索引池}/', $row['title'], $moban, 1);
                    $url_id_arr[] = $row['id'];
                } else {
                    $moban = preg_replace('/{随机索引池}/', "{随机数字}.html", $moban, 1);
                }
            }
            $sjwl = count(explode('{随机权重池}', $moban)) - 1;
            $qurl_id_arr = array();
            for ($i = 0; $i < $sjwl; $i++) {
                $sql = "SELECT * FROM `qurl` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `qurl`)-(SELECT MIN(id) FROM `qurl`))+(SELECT MIN(id) FROM `qurl`)) AS id) AS t2 WHERE t1.id >= t2.id and (user_id=1 or user_id in (select id from admin where endtime>unix_timestamp(now()))) ORDER BY t1.id LIMIT 1";
                $result = $mysqli->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $link = "<a href='" . $row['title'] . "' target='_blank'>" . $row['text'] . "</a>";
                    $moban = preg_replace('/{随机权重池}/', $link, $moban, 1);
                    $qurl_id_arr[] = $row['id'];
                } else {
                    $moban = preg_replace('/{随机权重池}/', "<a href=\"{随机数字}.html\">{随机关键词}</a>", $moban, 1);
                }
            }
            if ($ssyq && $ssyq !== 'other' && $ssyq !== 'yidong') {
                $url_id = implode(',', $url_id_arr);
                $wurl_id = implode(',', $wurl_id_arr);
                $qurl_id = implode(',', $qurl_id_arr);
                $mysqli->query("update url set " . $ssyq . "=" . $ssyq . "+1,count=count+1 where id in ({$url_id})");
                $mysqli->query("update url set " . $ssyq . "=" . $ssyq . "+1,count=count+1 where id in ({$wurl_id})");
                $mysqli->query("update qurl set " . $ssyq . "=" . $ssyq . "+1,count=count+1 where id in ({$qurl_id})");
            }
            $sjgjc = count(explode('{随机关键词}', $moban)) - 1;
            $sjgjcarr = get_rand_number($sjgjc, "keywords");
            for ($i = 0; $i < $sjgjc; $i++) {
                $keywords = $config['unicode'] ? unicode_encode($sjgjcarr[$i]) : $sjgjcarr[$i];
                $moban = preg_replace('/{随机关键词}/', $keywords, $moban, 1);
            }
            $zf1 = count(explode('{随机字符}', $moban)) - 1;
            for ($ii = 0; $ii < $zf1; $ii++) {
                $moban = preg_replace('/{随机字符}/', randKey(5), $moban, 1);
            }
            $ri5 = count(explode('{随机数字}', $moban)) - 1;
            for ($i = 0; $i < $ri5; $i++) {
                $moban = preg_replace('/{随机数字}/', mt_rand(10000, 99999), $moban, 1);
            }
            $moban = str_replace("{当天日期}", date("Y-m-d"), $moban);
            $sjsj = count(explode('{随机日期}', $moban)) - 1;
            for ($tui = 0; $tui < $sjsj; $tui++) {
                $i = mt_rand(0, 6);
                $moban = preg_replace('/{随机日期}/', date("m-d", strtotime("-{$i} day")), $moban, 1);
            }
            $sjsj = count(explode('{随机时间}', $moban)) - 1;
            for ($tui = 0; $tui < $sjsj; $tui++) {
                $datetime = strtotime(date('Y-m-d'));
                $xs = date('H');
                $i = mt_rand(0, $xs * 3600);
                $newdate = $datetime + $i;
                $sjsj = date('Y-m-d H:i:s', $newdate);
                $moban = preg_replace('/{随机时间}/', $sjsj, $moban, 1);
            }
            $moban = str_replace("</body>", $config['tongji'] . "\n</body>", $moban);
            echo $moban;
            die;
        }
    }
    $moban = str_replace("</head>", "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"{随机关键词}\" href=\"http://{当前域名}/sitemap.xml\" />\n</head>", $moban);
    $sql = "SELECT title FROM `m_title` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `m_title`)-(SELECT MIN(id) FROM `m_title`))+(SELECT MIN(id) FROM `m_title`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
    $title = $mysqli->query($sql)->fetch_object()->title;
    $title = $config['vip'] == '免费' ? $title . "_云凌工作室" : $title;
    $sql = "SELECT title FROM `m_key` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `m_key`)-(SELECT MIN(id) FROM `m_key`))+(SELECT MIN(id) FROM `m_key`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
    $key = $mysqli->query($sql)->fetch_object()->title;
    $sql = "SELECT title FROM `m_des` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `m_des`)-(SELECT MIN(id) FROM `m_des`))+(SELECT MIN(id) FROM `m_des`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
    $des = $mysqli->query($sql)->fetch_object()->title;
    $sql = "SELECT title FROM `keywords` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `keywords`)-(SELECT MIN(id) FROM `keywords`))+(SELECT MIN(id) FROM `keywords`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
    $zgjc = $mysqli->query($sql)->fetch_object()->title;
    $zgjcz = $config['unicode'] == 1 ? unicode_encode($zgjc) : $zgjc;
    $title = str_replace("{主关键词}", $zgjcz, $title);
    $key = str_replace("{主关键词}", $zgjcz, $key);
    $des = str_replace("{主关键词}", $zgjcz, $des);
    $cjwzbt = count(explode('{采集文章标题}', $title)) - 1;
    $cjwzbtarr = get_rand_number($cjwzbt, "c_title", 'title');
    for ($i = 0; $i < $cjwzbt; $i++) {
        if ($cjwzbtarr[$i]) {
            $title = preg_replace('/{采集文章标题}/', $cjwzbtarr[$i], $title, 1);
        } else {
            $title = preg_replace('/{采集文章标题}/', '{随机句子}', $title, 1);
        }
    }
    $cjwzbt = count(explode('{采集文章标题}', $key)) - 1;
    $cjwzbtarr = get_rand_number($cjwzbt, "c_title", 'title');
    for ($i = 0; $i < $cjwzbt; $i++) {
        if ($cjwzbtarr[$i]) {
            $key = preg_replace('/{采集文章标题}/', $cjwzbtarr[$i], $key, 1);
        } else {
            $key = preg_replace('/{采集文章标题}/', '{随机句子}', $key, 1);
        }
    }
    $cjwzbt = count(explode('{采集文章标题}', $des)) - 1;
    $cjwzbtarr = get_rand_number($cjwzbt, "c_title", 'title');
    for ($i = 0; $i < $cjwzbt; $i++) {
        if ($cjwzbtarr[$i]) {
            $des = preg_replace('/{采集文章标题}/', $cjwzbtarr[$i], $des, 1);
        } else {
            $des = preg_replace('/{采集文章标题}/', '{随机句子}', $des, 1);
        }
    }
    $sjjz = count(explode('{随机句子}', $title)) - 1;
    $sjjzarr = get_rand_number($sjjz, "juzi2", 'title');
    for ($i = 0; $i < $sjjz; $i++) {
        $title = preg_replace('/{随机句子}/', $sjjzarr[$i], $title, 1);
    }
    $sjjz = count(explode('{随机句子}', $key)) - 1;
    $sjjzarr = get_rand_number($sjjz, "juzi2", 'title');
    for ($i = 0; $i < $sjjz; $i++) {
        $key = preg_replace('/{随机句子}/', $sjjzarr[$i], $key, 1);
    }
    $sjjz = count(explode('{随机句子}', $des)) - 1;
    $sjjzarr = get_rand_number($sjjz, "juzi2", 'title');
    for ($i = 0; $i < $sjjz; $i++) {
        $des = preg_replace('/{随机句子}/', $sjjzarr[$i], $des, 1);
    }
    $sjdz = count(explode('{随机段子}', $title)) - 1;
    $sjdzarr = get_rand_number($sjdz, "juzi", 'title');
    for ($i = 0; $i < $sjdz; $i++) {
        $title = preg_replace('/{随机段子}/', $sjdzarr[$i], $title, 1);
    }
    $sjdz = count(explode('{随机段子}', $key)) - 1;
    $sjdzarr = get_rand_number($sjdz, "juzi", 'title');
    for ($i = 0; $i < $sjdz; $i++) {
        $key = preg_replace('/{随机段子}/', $sjdzarr[$i], $key, 1);
    }
    $sjdz = count(explode('{随机段子}', $des)) - 1;
    $sjdzarr = get_rand_number($sjdz, "juzi", 'title');
    for ($i = 0; $i < $sjdz; $i++) {
        $des = preg_replace('/{随机段子}/', $sjdzarr[$i], $des, 1);
    }
    $sjgjc = count(explode('{随机关键词}', $title)) - 1;
    $sjgjcarr = get_rand_number($sjgjc, "keywords");
    for ($i = 0; $i < $sjgjc; $i++) {
        $keywords = $config['unicode'] ? unicode_encode($sjgjcarr[$i]) : $sjgjcarr[$i];
        $title = preg_replace('/{随机关键词}/', $keywords, $title, 1);
    }
    $sjgjc = count(explode('{随机关键词}', $key)) - 1;
    $sjgjcarr = get_rand_number($sjgjc, "keywords");
    for ($i = 0; $i < $sjgjc; $i++) {
        $keywords = $config['unicode'] ? unicode_encode($sjgjcarr[$i]) : $sjgjcarr[$i];
        $key = preg_replace('/{随机关键词}/', $keywords, $key, 1);
    }
    $sjgjc = count(explode('{随机关键词}', $moban)) - 1;
    $sjgjcarr = get_rand_number($sjgjc, "keywords");
    for ($i = 0; $i < $sjgjc; $i++) {
        $keywords = $config['unicode'] ? unicode_encode($sjgjcarr[$i]) : $sjgjcarr[$i];
        $des = preg_replace('/{随机关键词}/', $keywords, $des, 1);
    }
    $moban = strtr($moban, array("{title}" => $title, "{keywords}" => $key, "{description}" => $des));
    if ($config['cache'] == 1 && $page == 1) {
        $cache->set($mydomain, $moban);
    }
    $sjbt = count(explode('{随机标题}', $moban)) - 1;
    $sjbtarr = get_rand_number($sjbt, "a_title", 'title');
    for ($i = 0; $i < $sjbt; $i++) {
        if ($sjbtarr[$i]) {
            $moban = preg_replace('/{随机标题}/', $sjbtarr[$i], $moban, 1);
        } else {
            $moban = preg_replace('/{随机标题}/', '{随机句子}', $moban, 1);
        }
    }
    $sjnr = count(explode('{随机内容}', $moban)) - 1;
    $sjnrarr = get_rand_number($sjnr, "a_content", 'title');
    for ($i = 0; $i < $sjnr; $i++) {
        if ($sjnrarr[$i]) {
            $moban = preg_replace('/{随机内容}/', $sjnrarr[$i], $moban, 1);
        } else {
            $moban = preg_replace('/{随机内容}/', '{随机段子}', $moban, 1);
        }
    }
    $sjurl = count(explode('{随机URL}', $moban)) - 1;
    $sjurlarr = get_rand_number($sjurl, "m_url", 'title');
    for ($i = 0; $i < $sjurl; $i++) {
        if ($sjurlarr[$i]) {
            $moban = preg_replace('/{随机URL}/', $sjurlarr[$i], $moban, 1);
        } else {
            $moban = preg_replace('/{随机URL}/', '{随机字符}.html', $moban, 1);
        }
    }
    $sjsp = count(explode('{随机视频}', $moban)) - 1;
    $sjsparr = get_rand_number($sjsp, "shipin", 'title');
    if (!empty($sjsparr)) {
        for ($i = 0; $i < $sjsp; $i++) {
            $str = "<embed src='" . $sjsparr[$i] . "' quality='high' width='480' height='400' align='middle' allowScriptAccess='always' allowFullScreen='true' mode='transparent' type='application/x-shockwave-flash'></embed>";
            $moban = preg_replace('/{随机视频}/', $str, $moban, 1);
        }
    } else {
        $moban = preg_replace('/{随机视频}/', '', $moban, 1);
    }
    $sql = "SELECT title FROM `keywords` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `keywords`)-(SELECT MIN(id) FROM `keywords`))+(SELECT MIN(id) FROM `keywords`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
    $zgjc = $mysqli->query($sql)->fetch_object()->title;
    $zgjcz = $config['unicode'] == 1 ? unicode_encode($zgjc) : $zgjc;
    $moban = str_replace("{主关键词}", $zgjcz, $moban);
    if ($zgjc && ($ssyq == "other" || $ssyq == "yidong")) {
        $sql = "select title from `key_jump` where jumpkey='" . $zgjc . "' order by id desc limit 1";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $key_jump_link = $row['title'];
            if ($config['tongji']) {
                $moban = str_replace("</head>", $config['tongji'] . "<script>window.location.href='{$key_jump_link}';</script>\n</head><body></body></html>", $moban);
                if ($config['cache'] == 1) {
                    $cache->set($mydomain, $moban);
                }
                return $moban;
            } else {
                header('Location: ' . $key_jump_link);
                die;
            }
        }
    }
    $cjwzbt = count(explode('{采集文章标题}', $moban)) - 1;
    $cjwzbtarr = get_rand_number($cjwzbt, "c_title", 'title');
    for ($i = 0; $i < $cjwzbt; $i++) {
        if ($cjwzbtarr[$i]) {
            $moban = preg_replace('/{采集文章标题}/', $cjwzbtarr[$i], $moban, 1);
        } else {
            $moban = preg_replace('/{采集文章标题}/', '{随机句子}', $moban, 1);
        }
    }
    $cjwznr = count(explode('{采集文章内容}', $moban)) - 1;
    $cjwznrarr = get_rand_number($cjwznr, "c_title", 'content');
    for ($i = 0; $i < $cjwznr; $i++) {
        if ($cjwznrarr[$i]) {
            $moban = preg_replace('/{采集文章内容}/', $cjwznrarr[$i], $moban, 1);
        } else {
            $moban = preg_replace('/{采集文章内容}/', '{随机段子}', $moban, 1);
        }
    }
    $sql = "SELECT * FROM `c_title` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `c_title`)-(SELECT MIN(id) FROM `c_title`))+(SELECT MIN(id) FROM `c_title`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
    $result = $mysqli->query($sql);
    if ($result->num_rows) {
        $row = $result->fetch_assoc();
        $moban = str_replace('{对应采集文章标题}', $row['title'], $moban);
        $moban = str_replace('{对应采集文章内容}', $row['content'], $moban);
    } else {
        $moban = str_replace('{对应采集文章标题}', '{随机关键词}', $moban);
        $moban = str_replace('{对应采集文章内容}', '{随机段子}', $moban);
    }
    $sjgjcpy = count(explode('{随机关键词拼音}', $moban)) - 1;
    $sjgjcpyarr = get_rand_number($sjgjcpy, "keywords", 'pinyin');
    for ($i = 0; $i < $sjgjcpy; $i++) {
        $moban = preg_replace('/{随机关键词拼音}/', $sjgjcpyarr[$i], $moban, 1);
    }
    $sjjz = count(explode('{随机句子}', $moban)) - 1;
    $sjjzarr = get_rand_number($sjjz, "juzi2", 'title');
    for ($i = 0; $i < $sjjz; $i++) {
        $moban = preg_replace('/{随机句子}/', $sjjzarr[$i], $moban, 1);
    }
    $sjdz = count(explode('{随机段子}', $moban)) - 1;
    $sjdzarr = get_rand_number($sjdz, "juzi", 'title');
    for ($i = 0; $i < $sjdz; $i++) {
        $moban = preg_replace('/{随机段子}/', $sjdzarr[$i], $moban, 1);
    }
    $moban = str_replace("{当前域名}", $yuming, $moban);
    $moban = str_replace("{顶级域名}", $yumi, $moban);
    $moban = str_replace("{页面地址}", "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $moban);
    $tupian5 = count(explode('{随机图片}', $moban)) - 1;
    for ($tui = 0; $tui < $tupian5; $tui++) {
        $moban = preg_replace('/{随机图片}/', '<img src="/pics/' . varray_rand($image_list) . '" alt="{随机关键词}"/>', $moban, 1);
    }
    $moban = str_replace("{年}", date("Y"), $moban);
    $moban = str_replace("{月}", date("m"), $moban);
    $moban = str_replace("{日}", date("d"), $moban);
    $randgroup = domain_group();
    $sqlgroup = "";
    if ($randgroup) {
        $sqlgroup = " and groupname='{$randgroup}'";
    }
    $wk = count(explode('{随机域名}', $moban)) - 1;
    for ($wi = 0; $wi < $wk; $wi++) {
        do {
            $sql = "SELECT title FROM `domains` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `domains`)-(SELECT MIN(id) FROM `domains`))+(SELECT MIN(id) FROM `domains`)) AS id) AS t2 WHERE t1.id >= t2.id {$sqlgroup} ORDER BY t1.id LIMIT 1";
            $domain = $mysqli->query($sql)->fetch_object()->title;
        } while (!$domain);
        $moban = preg_replace('/{随机域名}/', datade($domain), $moban, 1);
    }
    $wk = count(explode('{随机人名}', $moban)) - 1;
    for ($wi = 0; $wi < $wk; $wi++) {
        $moban = preg_replace('/{随机人名}/', randName(), $moban, 1);
    }
    $sjgjc = count(explode('{随机关键词}', $moban)) - 1;
    $sjgjcarr = get_rand_number($sjgjc, "keywords");
    for ($i = 0; $i < $sjgjc; $i++) {
        $keywords = $config['unicode'] ? unicode_encode($sjgjcarr[$i]) : $sjgjcarr[$i];
        $moban = preg_replace('/{随机关键词}/', $keywords, $moban, 1);
    }
    if ($config['cache'] == 1 && $page == 2) {
        $cache->set($mydomain, $moban);
    }
    $sjwl = count(explode('{随机外链}', $moban)) - 1;
    $wurl_id_arr = array();
    for ($i = 0; $i < $sjwl; $i++) {
        $sql = "SELECT * FROM `url` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `url`)-(SELECT MIN(id) FROM `url`))+(SELECT MIN(id) FROM `url`)) AS id) AS t2 WHERE t1.id >= t2.id and (user_id=1 or user_id in (select id from admin where endtime>unix_timestamp(now()))) ORDER BY t1.id LIMIT 1";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $moban = preg_replace('/{随机外链}/', $row['title'], $moban, 1);
            $wurl_id_arr[] = $row['id'];
        } else {
            $moban = preg_replace('/{随机外链}/', "{随机数字}.html", $moban, 1);
        }
    }
    $sjwl = count(explode('{随机索引池}', $moban)) - 1;
    $url_id_arr = array();
    for ($i = 0; $i < $sjwl; $i++) {
        $sql = "SELECT * FROM `url` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `url`)-(SELECT MIN(id) FROM `url`))+(SELECT MIN(id) FROM `url`)) AS id) AS t2 WHERE t1.id >= t2.id and (user_id=1 or user_id in (select id from admin where endtime>unix_timestamp(now()))) ORDER BY t1.id LIMIT 1";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $moban = preg_replace('/{随机索引池}/', $row['title'], $moban, 1);
            $url_id_arr[] = $row['id'];
        } else {
            $moban = preg_replace('/{随机索引池}/', "{随机数字}.html", $moban, 1);
        }
    }
    $sjwl = count(explode('{随机权重池}', $moban)) - 1;
    $qurl_id_arr = array();
    for ($i = 0; $i < $sjwl; $i++) {
        $sql = "SELECT * FROM `qurl` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `qurl`)-(SELECT MIN(id) FROM `qurl`))+(SELECT MIN(id) FROM `qurl`)) AS id) AS t2 WHERE t1.id >= t2.id and (user_id=1 or user_id in (select id from admin where endtime>unix_timestamp(now()))) ORDER BY t1.id LIMIT 1";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $link = "<a href='" . $row['title'] . "' target='_blank'>" . $row['text'] . "</a>";
            $moban = preg_replace('/{随机权重池}/', $link, $moban, 1);
            $qurl_id_arr[] = $row['id'];
        } else {
            $moban = preg_replace('/{随机权重池}/', "<a href=\"{随机数字}.html\">{随机关键词}</a>", $moban, 1);
        }
    }
    if ($ssyq && $ssyq !== 'other' && $ssyq !== 'yidong') {
        $url_id = implode(',', $url_id_arr);
        $wurl_id = implode(',', $wurl_id_arr);
        $qurl_id = implode(',', $qurl_id_arr);
        $mysqli->query("update url set " . $ssyq . "=" . $ssyq . "+1,count=count+1 where id in ({$url_id})");
        $mysqli->query("update url set " . $ssyq . "=" . $ssyq . "+1,count=count+1 where id in ({$wurl_id})");
        $mysqli->query("update qurl set " . $ssyq . "=" . $ssyq . "+1,count=count+1 where id in ({$qurl_id})");
    }
    $sjgjc = count(explode('{随机关键词}', $moban)) - 1;
    $sjgjcarr = get_rand_number($sjgjc, "keywords");
    for ($i = 0; $i < $sjgjc; $i++) {
        $keywords = $config['unicode'] ? unicode_encode($sjgjcarr[$i]) : $sjgjcarr[$i];
        $moban = preg_replace('/{随机关键词}/', $keywords, $moban, 1);
    }
    $zf1 = count(explode('{随机字符}', $moban)) - 1;
    for ($ii = 0; $ii < $zf1; $ii++) {
        $moban = preg_replace('/{随机字符}/', randKey(5), $moban, 1);
    }
    $ri5 = count(explode('{随机数字}', $moban)) - 1;
    for ($i = 0; $i < $ri5; $i++) {
        $moban = preg_replace('/{随机数字}/', mt_rand(10000, 99999), $moban, 1);
    }
    $moban = str_replace("{当天日期}", date("Y-m-d"), $moban);
    $sjsj = count(explode('{随机日期}', $moban)) - 1;
    for ($tui = 0; $tui < $sjsj; $tui++) {
        $i = mt_rand(0, 6);
        $moban = preg_replace('/{随机日期}/', date("m-d", strtotime("-{$i} day")), $moban, 1);
    }
    $sjsj = count(explode('{随机时间}', $moban)) - 1;
    for ($tui = 0; $tui < $sjsj; $tui++) {
        $datetime = strtotime(date('Y-m-d'));
        $xs = date('H');
        $i = mt_rand(0, $xs * 3600);
        $newdate = $datetime + $i;
        $sjsj = date('Y-m-d H:i:s', $newdate);
        $moban = preg_replace('/{随机时间}/', $sjsj, $moban, 1);
    }
    if ($act == 'liyunpeng') {
        $wainum = $config['wainum'];
        if ($wainum) {
            $waitui = $wainum / 1000 . "k";
            if ($wainum > 10000) {
                $waitui = $wainum / 10000 . "w";
            }
            $support .= "|waitui " . $waitui;
        }
        $moban = str_replace("</body>", $config['tongji'] . "\n</body>", $moban);
    } else {
        $moban = str_replace("</body>", $config['tongji'] . "\n</body>", $moban);
    }
    return $moban;
}

function domain_group()
{
    global $mysqli;
    $duankou = $_SERVER["SERVER_PORT"];
    $yuming = $_SERVER['HTTP_HOST'];
    $yuming = str_replace(':' . $duankou, '', $yuming);
    $yumi = getdomain($yuming);
    $result = $mysqli->query("select count(*) as count from domains where groupname='a'");
    if ($result->num_rows) {
        $ca = $result->fetch_object()->count;
    }
    $result = $mysqli->query("select count(*) as count from domains where groupname='b'");
    if ($result->num_rows) {
        $cb = $result->fetch_object()->count;
    }
    $result = $mysqli->query("select count(*) as count from domains where groupname='c'");
    if ($result->num_rows) {
        $cc = $result->fetch_object()->count;
    }
    $result = $mysqli->query("select count(*) as count from domains where groupname='d'");
    if ($result->num_rows) {
        $cd = $result->fetch_object()->count;
    }
    $result = $mysqli->query("select count(*) as count from domains where groupname='e'");
    if ($result->num_rows) {
        $ce = $result->fetch_object()->count;
    }
    if ($ca && $cb && $cc && $cd && $ce) {
        $groupname = $mysqli->query("select groupname from domains where title='" . $yumi . "' order by id desc limit 1")->fetch_object()->groupname;
        $group = str_replace($groupname, "", "abcde");
        $grouparr = str_split($group);
        $randgroup = $grouparr[mt_rand(0, 3)];
        return $randgroup;
    }
    return false;
}

function info_add($from, $data)
{
    global $mysqli, $config;
    if ($from == 'domains') {
        $vip_domain_num = $config['domain'];
        $result = $mysqli->query("select count(*) as count from domains");
        if ($result->num_rows) {
            $domain_num = $result->fetch_object()->count;
        }
        if ($domain_num >= $vip_domain_num) {
            echo "<script>alert('数量已达到VIP限制,请升级您的帐号');self.location.href='info.php?act=" . $from . "';</script>";
            die;
        }
        $chars = array("a", "b", "c", "d", "e");
        $group = $chars[mt_rand(0, 4)];
        $pc_moban = moban_ok('moban');
        if ($pc_moban) {
            $pc_moban_rand = array_rand($pc_moban, 1);
            $pc_moban_id = $pc_moban[$pc_moban_rand]['id'];
        } else {
            $pc_moban_id = 0;
        }
        $mo_moban = moban_ok('mobile');
        if ($mo_moban) {
            $mo_moban_rand = array_rand($mo_moban, 1);
            $mo_moban_id = $mo_moban[$mo_moban_rand]['id'];
        } else {
            $mo_moban_id = 0;
        }
        $mysqli->query("insert into " . $from . " (`title`,`groupname`,`pc_moban_id`,`mo_moban_id`) values('" . dataen($data['title']) . "','" . $group . "',{$pc_moban_id},{$mo_moban_id})");
        if ($mysqli->insert_id) {
            return true;
        }
    }
    if ($from == 'keywords') {
        $PingYing = new GetPingYing();
        $str = iconv('utf-8', 'gbk', $data['title']);
        $pinyin = $PingYing->getAllPY($str);
        $mysqli->query("insert into " . $from . " (`title`,`pinyin`) values('" . $data['title'] . "','" . $pinyin . "')");
        if ($mysqli->insert_id) {
            return true;
        }
    }
    if ($from == 'm_url') {
        $mysqli->query("insert into " . $from . " (`title`,`page`) values('" . $data['title'] . "','" . $data['page'] . "')");
        if ($mysqli->insert_id) {
            return true;
        }
    }
    if ($from == 'url') {
        $user_id = $_SESSION['admin_id'];
        $mysqli->query("insert into " . $from . " (`title`,`user_id`) values('" . $data['title'] . "'," . $user_id . ")");
        if ($mysqli->insert_id) {
            return true;
        }
    }
    if ($from == 'qurl') {
        $user_id = $_SESSION['admin_id'];
        $info = explode('|', $data['title']);
        if (count($info) == 2) {
            $mysqli->query("insert into " . $from . " (`title`,`text`,`user_id`) values('" . $info[0] . "','" . $info[1] . "'," . $user_id . ")");
            if ($mysqli->insert_id) {
                return true;
            }
        }
    }
    if ($from == 'weiyuanchuang') {
        $info = explode('|', $data['title']);
        if (count($info) == 2) {
            $mysqli->query("insert into " . $from . " (`title`,`new`) values('" . $info[0] . "','" . $info[1] . "')");
            if ($mysqli->insert_id) {
                return true;
            }
        }
    }
    if ($from == 'key_jump') {
        $info = explode('|', $data['title']);
        if (count($info) == 2) {
            $mysqli->query("insert into " . $from . " (`title`,`jumpkey`) values('" . $info[0] . "','" . $info[1] . "')");
            if ($mysqli->insert_id) {
                return true;
            }
        }
    }
    $mysqli->query("insert into " . $from . " (`title`) values('" . $data['title'] . "')");
    if ($mysqli->insert_id) {
        header("Location: info.php?act=" . $from);
    }
}

function ajax_info_add($data)
{
    global $mysqli, $config;
    if ($data['from'] == 'domains') {
        $vip_domain_num = $config['domain'];
        $domain_num = $mysqli->query("select count(*) as count from domains")->fetch_object()->count;
        if ($domain_num >= $vip_domain_num) {
            $str = "err";
            return $str;
        }
    }
    if ($data['from'] == 'keywords') {
        $PingYing = new GetPingYing();
        $str = iconv('utf-8', 'gbk', $data['title']);
        $pinyin = $PingYing->getAllPY($str);
        $mysqli->query("insert into " . $data['from'] . " (`title`,`pinyin`) values('" . $data['title'] . "','" . $pinyin . "')");
        return true;
    }
    if ($data['from'] == 'url') {
        $user_id = $_SESSION['admin_id'];
        if ($user_id != 1) {
            $num = $mysqli->query("select num from admin where id=" . $user_id)->fetch_object()->num;
            $oknum = $mysqli->query("select count(*) as count from url where user_id=" . $user_id)->fetch_object()->count;
            if ($oknum >= $num) {
                $str = "err";
                return $str;
            }
        }
        $mysqli->query("insert into " . $data['from'] . " (`title`,`user_id`) values('" . $data['title'] . "'," . $user_id . ")");
        return true;
    }
    $mysqli->query("insert into " . $data['from'] . " (`title`) values('" . $data['title'] . "')");
    return true;
}

function list_data($from, $page, $type = '', $where = '')
{
    global $mysqli;
    $page_size = 30;
    $sql = "select id from " . $from;
    if ($type) {
        $sql .= " where ssyq='" . $type . "'";
    }
    $mysqli->query($sql);
    $total = $mysqli->affected_rows;
    $pagenum = ceil($total / $page_size);
    if ($page != "all" && ($page < 1 || !is_numeric($page) || $page > $pagenum)) {
        $page = 1;
    }
    $min = ($page - 1) * $page_size;
    $sql = "select * from " . $from;
    if ($type) {
        $sql .= " where ssyq='" . $type . "'";
    }
    if ($where) {
        $sql .= " where " . $where;
    }
    $sql .= " order by id desc";
    if ($page != "all") {
        $sql .= " limit " . $min . "," . $page_size;
    }
    $result = $mysqli->query($sql);
    if ($mysqli->affected_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}

function ajax_list_data($data)
{
    global $mysqli;
    $page_size = 30;
    $sql = "select id from " . $data['from'];
    if ($data['type']) {
        $sql .= " where ssyq='" . $data['type'] . "'";
    }
    $mysqli->query($sql);
    $total = $mysqli->affected_rows;
    $pagenum = ceil($total / $page_size);
    if ($data['page'] != "all" && ($data['page'] < 1 || !is_numeric($data['page']) || $data['page'] > $pagenum)) {
        $data['page'] = 1;
    }
    $min = ($data['page'] - 1) * $page_size;
    $sql = "select * from " . $data['from'];
    if ($data['type']) {
        $sql .= " where ssyq='" . $data['type'] . "'";
    }
    $sql .= " order by id desc";
    if ($data['page'] != "all") {
        $sql .= " limit " . $min . "," . $page_size;
    }
    $result = $mysqli->query($sql);
    if ($mysqli->affected_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}

function ajax_spider_list_data($data)
{
    global $mysqli;
    $page_size = 30;
    if ($data['date']) {
        $table = "spider_" . date('Ymd', strtotime($data['date']));
    } else {
        $table = "spider_" . date('Ymd');
    }
    $result = $mysqli->query("SHOW TABLES LIKE '" . $table . "' ")->fetch_row();
    if ($result) {
        $total = $mysqli->query("select count(*) as count from " . $table)->fetch_object()->count;
        $pagenum = ceil($total / $page_size);
        if ($data['page'] < 1 || !is_numeric($data['page']) || $data['page'] > $pagenum) {
            $data['page'] = 1;
        }
        $min = ($data['page'] - 1) * $page_size;
        $sql = "select * from " . $table . " order by id desc limit " . $min . "," . $page_size;
        $result = $mysqli->query($sql);
        if ($mysqli->affected_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }
}

function spider_list_data($date, $page)
{
    global $mysqli;
    $page_size = 30;
    if ($date) {
        $table = "spider_" . date('Ymd', strtotime($date));
    } else {
        $table = "spider_" . date('Ymd');
    }
    $result = $mysqli->query("SHOW TABLES LIKE '" . $table . "' ")->fetch_row();
    if ($result) {
        $total = $mysqli->query("select count(*) as count from " . $table)->fetch_object()->count;
        $pagenum = ceil($total / $page_size);
        if ($page < 1 || !is_numeric($page) || $page > $pagenum) {
            $page = 1;
        }
        $min = ($page - 1) * $page_size;
        $sql = "select * from " . $table . " order by id desc limit " . $min . "," . $page_size;
        $result = $mysqli->query($sql);
        if ($mysqli->affected_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }
}

function list_page($from, $page, $type = '', $where = '')
{
    global $mysqli;
    $page_size = 30;
    $sql = "select id from " . $from;
    if ($type) {
        $sql .= " where ssyq='" . $type . "'";
    }
    if ($where) {
        $sql .= " where " . $where;
    }
    $mysqli->query($sql);
    $total = $mysqli->affected_rows;
    if ($total > 0) {
        $pagenum = ceil($total / $page_size);
        if ($page < 1 || !is_numeric($page) || $page > $pagenum) {
            $page = 1;
        }
        $shang = $page > 1 ? $page - 1 : 1;
        $str = "<div class=\"pageUp\"><a href=\"?act=" . $from . "&page=" . $shang . "&type=" . $type . "\">上一页</a></div>";
        $str .= "<div class=\"pageList clear\"><ul>";
        if ($page - 3 > 0) {
            $str .= "<li><a href=\"?act=" . $from . "&page=1&type=" . $type . "\">1</a></li>";
            $str .= "<li>...</li>";
        }
        if ($page - 2 > 0) {
            $str .= "<li><a href=\"?act=" . $from . "&page=" . ($page - 2) . "&type=" . $type . "\">" . ($page - 2) . "</a></li>";
        }
        if ($page - 1 > 0) {
            $str .= "<li><a href=\"?act=" . $from . "&page=" . ($page - 1) . "&type=" . $type . "\">" . ($page - 1) . "</a></li>";
        }
        $str .= "<li class=\"on\">{$page}</li>";
        if ($page + 1 <= $pagenum) {
            $str .= "<li><a href=\"?act=" . $from . "&page=" . ($page + 1) . "&type=" . $type . "\">" . ($page + 1) . "</a></li>";
        }
        if ($page + 2 <= $pagenum) {
            $str .= "<li><a href=\"?act=" . $from . "&page=" . ($page + 2) . "&type=" . $type . "\">" . ($page + 2) . "</a></li>";
        }
        if ($page + 3 <= $pagenum) {
            $str .= "<li>...</li>";
            $str .= "<li><a href=\"?act=" . $from . "&page=" . $pagenum . "&type=" . $type . "\">" . $pagenum . "</a></li>";
        }
        $str .= "</ul></div>";
        $xia = $page >= $pagenum ? $pagenum : $page + 1;
        $str .= "<div class=\"pageDown\"><a href=\"?act=" . $from . "&page=" . $xia . "&type=" . $type . "\">下一页</a></div>";
        $str .= "<div class=\"pagejump\"><form action='' method='get'><input type='hidden' name='act' value='{$from}'/><input type='hidden' name='type' value='{$type}'/>共{$pagenum}页 | 跳转到<input type='text' name='page'/>页</form></div>";
        return $str;
    }
}

function ajax_list_page($data)
{
    global $mysqli;
    $page_size = 30;
    if (isset($data['date'])) {
        $table = "spider_" . date('Ymd', strtotime($data['date']));
        $f = $data['date'];
    } else {
        $f = $table = $data['from'];
    }
    $sql = "select id from " . $table;
    $mysqli->query($sql);
    $total = $mysqli->affected_rows;
    if ($total > 0) {
        $pagenum = ceil($total / $page_size);
        if ($data['page'] < 1 || !is_numeric($data['page']) || $data['page'] > $pagenum) {
            $data['page'] = 1;
        }
        $shang = $data['page'] > 1 ? $data['page'] - 1 : 1;
        $str = "<div class=\"pageUp\"><a onclick='data_list(\"" . $f . "\",{$shang})'>上一页</a></div>";
        $str .= "<div class=\"pageList clear\"><ul>";
        if ($data['page'] - 3 > 0) {
            $str .= "<li><a onclick='data_list(\"" . $f . "\",1)'>1</a></li>";
            $str .= "<li>...</li>";
        }
        if ($data['page'] - 2 > 0) {
            $str .= "<li><a onclick='data_list(\"" . $f . "\"," . ($data['page'] - 2) . ")'>" . ($data['page'] - 2) . "</a></li>";
        }
        if ($data['page'] - 1 > 0) {
            $str .= "<li><a onclick='data_list(\"" . $f . "\"," . ($data['page'] - 1) . ")'>" . ($data['page'] - 1) . "</a></li>";
        }
        $str .= "<li class=\"on\">" . $data['page'] . "</li>";
        if ($data['page'] + 1 <= $pagenum) {
            $str .= "<li><a onclick='data_list(\"" . $f . "\"," . ($data['page'] + 1) . ")'>" . ($data['page'] + 1) . "</a></li>";
        }
        if ($data['page'] + 2 <= $pagenum) {
            $str .= "<li><a onclick='data_list(\"" . $f . "\"," . ($data['page'] + 2) . ")'>" . ($data['page'] + 2) . "</a></li>";
        }
        if ($data['page'] + 3 <= $pagenum) {
            $str .= "<li>...</li>";
            $str .= "<li><a onclick='data_list(\"" . $f . "\"," . $pagenum . ")'>" . $pagenum . "</a></li>";
        }
        $str .= "</ul></div>";
        $xia = $data['page'] >= $pagenum ? $pagenum : $data['page'] + 1;
        $str .= "<div class=\"pageDown\"><a onclick='data_list(\"" . $f . "\",{$xia})'>下一页</a></div>";
        $str .= "<div class=\"pagejump\">共{$pagenum}页 | 跳转到<input type='text' name='page' onchange='data_list(\"" . $f . "\",this.value)'/>页</div>";
        return $str;
    }
}

function spider_list_page($date, $page)
{
    global $mysqli;
    $page_size = 30;
    if ($date) {
        $table = "spider_" . date('Ymd', strtotime($date));
    } else {
        $date = date("Y-m-d");
        $table = "spider_" . date("Ymd");
    }
    $sql = "select id from " . $table;
    $mysqli->query($sql);
    $total = $mysqli->affected_rows;
    if ($total > 0) {
        $pagenum = ceil($total / $page_size);
        if ($page < 1 || !is_numeric($page) || $page > $pagenum) {
            $page = 1;
        }
        $shang = $page > 1 ? $page - 1 : 1;
        $str = "<div class=\"pageUp\"><a href=\"?page=" . $shang . "&date=" . $date . "\">上一页</a></div>";
        $str .= "<div class=\"pageList clear\"><ul>";
        if ($page - 3 > 0) {
            $str .= "<li><a href=\"?page=1&date=" . $date . "\">1</a></li>";
            $str .= "<li>...</li>";
        }
        if ($page - 2 > 0) {
            $str .= "<li><a href=\"?page=" . ($page - 2) . "&date=" . $date . "\">" . ($page - 2) . "</a></li>";
        }
        if ($page - 1 > 0) {
            $str .= "<li><a href=\"?page=" . ($page - 1) . "&date=" . $date . "\">" . ($page - 1) . "</a></li>";
        }
        $str .= "<li class=\"on\">{$page}</li>";
        if ($page + 1 <= $pagenum) {
            $str .= "<li><a href=\"?page=" . ($page + 1) . "&date=" . $date . "\">" . ($page + 1) . "</a></li>";
        }
        if ($page + 2 <= $pagenum) {
            $str .= "<li><a href=\"?page=" . ($page + 2) . "&date=" . $date . "\">" . ($page + 2) . "</a></li>";
        }
        if ($page + 3 <= $pagenum) {
            $str .= "<li>...</li>";
            $str .= "<li><a href=\"?page=" . $pagenum . "&date=" . $date . "\">" . $pagenum . "</a></li>";
        }
        $str .= "</ul></div>";
        $xia = $page >= $pagenum ? $pagenum : $page + 1;
        $str .= "<div class=\"pageDown\"><a href=\"?page=" . $xia . "&date=" . $date . "\">下一页</a></div>";
        $str .= "<div class=\"pagejump\"><form action='' method='get'><input type='hidden' name='date' value='{$date}'/>共{$pagenum}页 | 跳转到<input type='text' name='page'/>页</form></div>";
        return $str;
    }
}

function info_save($from, $data, $page, $id)
{
    global $mysqli;
    $sql = "update " . $from . " set title='" . $data['title'] . "'";
    if ($from == 'm_url' && is_numeric($data['page'])) {
        $sql .= ",page=" . $data['page'];
    }
    if ($from == 'qurl' && $data['text']) {
        $sql .= ",text='" . $data['text'] . "'";
    }
    if ($from == 'weiyuanchuang' && $data['new']) {
        $sql .= ",new='" . $data['new'] . "'";
    }
    if ($from == 'key_jump' && $data['jumpkey']) {
        $sql .= ",jumpkey='" . $data['jumpkey'] . "'";
    }
    if ($from == 'domains') {
        $sql = "update " . $from . " set title='" . dataen($data['title']) . "'";
        $sql .= ",pc_moban_id='" . $data['pc_moban_id'] . "',mo_moban_id='" . $data['mo_moban_id'] . "'";
    }
    $sql .= " where id=" . $id;
    $mysqli->query($sql);
    header('Location: info.php?act=' . $from . "&page=" . $page);
}

function info_del($from, $page, $id)
{
    global $mysqli;
    $mysqli->query("delete from " . $from . " where id=" . $id);
    header('Location: info.php?act=' . $from . "&page=" . $page);
}

function ajax_info_del($data)
{
    global $mysqli;
    $mysqli->query("delete from " . $data['from'] . " where id=" . $data['id']);
    return true;
}

function info_del_all($from)
{
    global $mysqli;
    $mysqli->query("truncate table " . $from);
    header('Location: info.php?act=' . $from);
}

function ajax_del_all($data)
{
    global $mysqli;
    $mysqli->query("truncate table " . $data['from']);
    return true;
}

function data_num($from, $num = '', $day = '', $type = '', $where = '')
{
    global $mysqli;
    $count_all = 0;
    $count = 0;
    if ($from == 'spider') {
        if ($type && is_numeric($num) && !$day) {
            $table = "spider_" . date('Ymd');
            $result = $mysqli->query("SHOW TABLES LIKE '" . $table . "' ")->fetch_row();
            if ($result) {
                $result = $mysqli->query("select count(*) as count from " . $table . " where ssyq='" . $type . "'");
                if ($result->num_rows) {
                    $count = $result->fetch_object()->count;
                }
                $count_all += $count;
            }
        }
        if (is_numeric($num) && !$type && !$day) {
            $num = '-' . $num . ' day';
            $riqi = strtotime(date('Y-m-d', strtotime($num)));
            $result = $mysqli->query("select sum(count) as count from spider where rq>{$riqi}");
            if ($result->num_rows) {
                $count = $result->fetch_object()->count;
            }
            $count_all += $count;
        }
        if ($num == "all" && !$type && !$day) {
            $result = $mysqli->query("select sum(count) as count from spider");
            if ($result->num_rows) {
                $count = $result->fetch_object()->count;
            }
            $count_all += $count;
        }
        if ($type && !$num && !$day) {
            $result = $mysqli->query("select sum(" . $type . ") as count from spider");
            if ($result->num_rows) {
                $count_all = $result->fetch_object()->count;
            }
        }
        if ($type && $day) {
            $type = strtolower($type);
            $table = "spider_" . date('Ymd', strtotime($day));
            $result = $mysqli->query("select count(*) as count from " . $table . " where ssyq='" . $type . "'");
            if ($result->num_rows) {
                $count = $result->fetch_object()->count;
            }
            $count_all += $count;
        }
        if ($day && !$type) {
            $result = $mysqli->query("select count from spider where DATE_FORMAT(FROM_UNIXTIME(rq),'%Y-%m-%d') = '" . $day . "'");
            if ($result->num_rows) {
                $count = $result->fetch_object()->count;
            }
            $count_all += $count;
        }
    } else {
        $sql = "select count(*) as count from " . $from;
        if ($where) {
            $sql .= " where " . $where;
        }
        $result = $mysqli->query($sql);
        if ($result->num_rows) {
            $count = $result->fetch_object()->count;
        }
        $count_all += $count;
    }
    return $count_all;
}

function shishi_spider($date = "")
{
    global $mysqli;
    if ($date) {
        $table = "spider_" . date('Ymd', strtotime($date));
    } else {
        $table = "spider_" . date('Ymd');
    }
    $result = $mysqli->query("SHOW TABLES LIKE '" . $table . "' ")->fetch_row();
    if ($result) {
        $count = $mysqli->query("select count(*) as count from " . $table)->fetch_object()->count;
        return $count;
    }
}

function ajax_shishi_spider()
{
    global $mysqli;
    $table = "spider_" . date('Ymd');
    $count = $mysqli->query("select count(*) as count from " . $table)->fetch_object()->count;
    return $count;
}

function ajax_data_num($data)
{
    global $mysqli;
    $count_all = 0;
    if ($data['from'] == 'spider') {
        if ($data['num'] && is_numeric($data['num'])) {
            $num = '-' . $data['num'] . ' day';
            $riqi = strtotime(date('Y-m-d', strtotime($num)));
            $count = $mysqli->query("select sum(count) as count from spider where rq>{$riqi}")->fetch_object()->count;
            $count_all += $count;
        }
        if ($data['num'] && $data['num'] == "all") {
            $count = $mysqli->query("select sum(count) as count from spider")->fetch_object()->count;
            $count_all += $count;
        }
        if ($data['day']) {
            $result = $mysqli->query("select count from spider where DATE_FORMAT(FROM_UNIXTIME(rq),'%Y-%m-%d') = '" . $data['day'] . "'");
            if ($result) {
                $row = $result->fetch_assoc();
                $count = $row['count'];
            }
            $count_all += $count;
        }
    } else {
        $count = $mysqli->query("select count(*) as count from " . $data['from'])->fetch_object()->count;
        $count_all += $count;
    }
    return $count_all;
}

function hour_data_num($from, $num)
{
    global $mysqli;
    if (is_numeric($num)) {
        $num = '-' . $num . ' day';
        $table = "spider_" . date('Ymd', strtotime($num));
        $riqi = strtotime(date('Y-m-d', strtotime($num)));
        $count = array();
        $result = $mysqli->query("SHOW TABLES LIKE '" . $table . "' ")->fetch_row();
        for ($i = 0; $i <= 23; $i++) {
            if ($result) {
                $sql = "select count(*) as count from " . $table;
                $min = $riqi + $i * 60 * 60;
                $max = $min + 60 * 60;
                $sql .= " where rq>={$min} and rq<={$max}";
                $count[] = $mysqli->query($sql)->fetch_object()->count;
            } else {
                $count[] = 0;
            }
        }
        return $count;
    }
}

function templates_list()
{
    global $mysqli;
    $post_data['act'] = "templates";
    $data = "";
    if (true || $request = request_post($post_data)) {
        $ser_vip = '免费';
        $sql = "select * from templates order by id desc";
        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $row['thumb'] = "/templates/" . $row['title'] . "/thumb.jpg";
            if ($row['ok']) {
                $row['us'] = "<a class='ok' href='?act=edit&id=" . $row['id'] . "&ok=0'>已启用</a>";
            } else {
                $row['us'] = "<a href='?act=edit&id=" . $row['id'] . "&ok=1'>未启用</a>";
            }
            if ($ser_vip != '免费') {
                $row['us'] .= "<a class='reset' title='重新下载' href='templates.php?moban_title=" . $row['title'] . "'></a>";
            }
            $data[] = $row;
        }
    }
    return $data;
}

function templates_list_old()
{
    global $mysqli;
    $post_data['act'] = "templates";
    $data = "";
    if ($request = request_post($post_data)) {
        $yuanmoban = json_decode($request, true);
        $post_data['act'] = "shouquan";
        $request = request_post($post_data);
        if (!$request) {
            $i = 0;
            while (!$request) {
                $request = request_post($post_data);
                ++$i;
                if ($i == 20) {
                    echo "无法链接授权服务器";
                    die;
                }
            }
        } elseif ($request === '5pyq5o6I5p2D') {
            $sql = "update config set title='',vip='',templates='',domain='',date='',enddate='',link=0 limit 1";
            $mysqli->query($sql);
            echo '警告:此域名未授权,请<a href=\'http://uiku.net/#goumai\' target=\'_blank\'>点此链接</a>申请免费使用或购买授权';
            die;
        } else {
            $result = json_decode($request);
            if ($result->enddate && time() > $result->enddate) {
                $sql = "update config set title='',vip='',templates='',domain='',date='',enddate='',link=0 limit 1";
                $mysqli->query($sql);
                echo '警告:此授权已过期,请<a href=\'http://uiku.net/#goumai\' target=\'_blank\'>点此链接</a>申请免费使用或购买授权';
                die;
            }
        }
        $result = json_decode($request);
        $ser_vip = $result->vip;
        foreach ($yuanmoban as $value) {
            $sql = "select * from templates where title='" . $value['title'] . "'";
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();
            if ($result->num_rows == 0) {
                $row['thumb'] = "http://uiku.net/" . $value['title'] . "_thumb.jpg";
                $row['name'] = $value['detail'];
                if ($ser_vip == '免费') {
                    $row['us'] = "<a class='down_no' href=\"javascript:void(0)\">下载</a>";
                } else {
                    $row['us'] = "<a class='down' href='templates.php?moban_title=" . $value['title'] . "'>下载</a>";
                }
                $data[] = $row;
            }
        }
        $sql = "select * from templates order by id desc";
        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $row['thumb'] = "/templates/" . $row['title'] . "/thumb.jpg";
            if ($row['ok']) {
                $row['us'] = "<a class='ok' href='?act=edit&id=" . $row['id'] . "&ok=0'>已启用</a>";
            } else {
                $row['us'] = "<a href='?act=edit&id=" . $row['id'] . "&ok=1'>未启用</a>";
            }
            if ($ser_vip != '免费') {
                $row['us'] .= "<a class='reset' title='重新下载' href='templates.php?moban_title=" . $row['title'] . "'></a>";
            }
            $data[] = $row;
        }
    }
    return $data;
}

function spiderset_list()
{
    global $mysqli;
    $sql = "select * from spiderset order by id asc";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $row['thumb'] = "img/" . $row['title'] . "_thumb.jpg";
        if ($row['ok']) {
            $row['us'] = "<a class='ok' href='?act=edit&id=" . $row['id'] . "&ok=0'>已开启</a>";
        } else {
            $row['us'] = "<a href='?act=edit&id=" . $row['id'] . "&ok=1'>未开启</a>";
        }
        $data[] = $row;
    }
    return $data;
}

function spider_type_list($num = '', $day = '')
{
    global $mysqli;
    $sql = "select title from spiderset where ok=1 order by id asc";
    $result = $mysqli->query($sql);
    $str = "";
    while ($row = $result->fetch_assoc()) {
        $title = $row['title'];
        $str .= $title . "(" . data_num('spider', $num, $day, $row['title']) . ") | ";
    }
    return $str;
}

function request_post($post_data = array())
{
    if (function_exists("curl_init")) {
        $url = "http://vip.alizhizhuchi.top/index.php";
        $config = config_list();
        if ($config['title']) {
            $domain = $config['title'];
        } else {
            $duankou = $_SERVER["SERVER_PORT"];
            $yuming = $_SERVER['HTTP_HOST'];
            $yuming = str_replace(':' . $duankou, '', $yuming);
            $domain = $yuming;
        }
        $post_data['domain'] = $domain;
        if (empty($url) || empty($post_data)) {
            return false;
        }
        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "{$k}=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    } else {
        echo '请在服务器开启curl扩展';
        die;
    }
}

function config_list()
{
    global $mysqli;
    $res = false;
    $sql = "select * from config limit 1";
    $result = $mysqli->query($sql);
    if ($result->num_rows) {
        $row = $result->fetch_assoc();
        foreach ($row as $k => $v) {
            if ($k == 'link' || $k == 'wainum') {
                $res[$k] = $v;
            } else {
                $res[$k] = datade($v);
            }
        }
        return $res;
    } else {
        return false;
    }
}

function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if ($file != '.' && $file != '..') {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                if (!copy($src . '/' . $file, $dst . '/' . $file)) {
                    echo "更新失败,请下载更新包,手动上传到服务器。";
                    die;
                } else {
                    unlink($src . '/' . $file);
                }
            }
        }
    }
    closedir($dir);
}

function convertip($ip)
{
    $ip1num = 0;
    $ip2num = 0;
    $ipAddr1 = "";
    $ipAddr2 = "";
    $dat_path = './admin/qqwry.dat';
    if (!preg_match('/^\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}$/', $ip)) {
        return 'IP Address Error';
    }
    if (!($fd = @fopen($dat_path, 'rb'))) {
        return 'IP date file not exists or access denied';
    }
    $ip = explode('.', $ip);
    $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
    $DataBegin = fread($fd, 4);
    $DataEnd = fread($fd, 4);
    $ipbegin = implode('', unpack('L', $DataBegin));
    if ($ipbegin < 0) {
        $ipbegin += pow(2, 32);
    }
    $ipend = implode('', unpack('L', $DataEnd));
    if ($ipend < 0) {
        $ipend += pow(2, 32);
    }
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
    $BeginNum = 0;
    $EndNum = $ipAllNum;
    while ($ip1num > $ipNum || $ip2num < $ipNum) {
        $Middle = intval(($EndNum + $BeginNum) / 2);
        fseek($fd, $ipbegin + 7 * $Middle);
        $ipData1 = fread($fd, 4);
        if (strlen($ipData1) < 4) {
            fclose($fd);
            return 'System Error';
        }
        $ip1num = implode('', unpack('L', $ipData1));
        if ($ip1num < 0) {
            $ip1num += pow(2, 32);
        }
        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }
        $DataSeek = fread($fd, 3);
        if (strlen($DataSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
        fseek($fd, $DataSeek);
        $ipData2 = fread($fd, 4);
        if (strlen($ipData2) < 4) {
            fclose($fd);
            return 'System Error';
        }
        $ip2num = implode('', unpack('L', $ipData2));
        if ($ip2num < 0) {
            $ip2num += pow(2, 32);
        }
        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                fclose($fd);
                return 'Unknown';
            }
            $BeginNum = $Middle;
        }
    }
    $ipFlag = fread($fd, 1);
    if ($ipFlag == chr(1)) {
        $ipSeek = fread($fd, 3);
        if (strlen($ipSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
        fseek($fd, $ipSeek);
        $ipFlag = fread($fd, 1);
    }
    if ($ipFlag == chr(2)) {
        $AddrSeek = fread($fd, 3);
        if (strlen($AddrSeek) < 3) {
            fclose($fd);
            return 'System Error';
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr2 .= $char;
        }
        $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
        fseek($fd, $AddrSeek);
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr1 .= $char;
        }
    } else {
        fseek($fd, -1, SEEK_CUR);
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr1 .= $char;
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr2 .= $char;
        }
    }
    fclose($fd);
    if (preg_match('/http/i', $ipAddr2)) {
        $ipAddr2 = '';
    }
    $ipaddr = "{$ipAddr1} {$ipAddr2}";
    $ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr);
    $ipaddr = preg_replace('/^s*/is', '', $ipaddr);
    $ipaddr = preg_replace('/s*$/is', '', $ipaddr);
    $ipaddr = iconv("utf-8", "utf-8//IGNORE", $ipaddr);
    if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
        $ipaddr = 'Unknown';
    }
    return $ipaddr;
}

function unicode_encode($str, $encoding = 'UTF-8', $prefix = '&#', $postfix = ';')
{
    $str = iconv($encoding, 'UCS-2', $str);
    $arrstr = str_split($str, 2);
    $unistr = '';
    for ($i = 0, $len = count($arrstr); $i < $len; $i++) {
        $dec = hexdec(bin2hex($arrstr[$i]));
        $unistr .= $prefix . $dec . $postfix;
    }
    return $unistr;
}

function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0; QQBrowser/7.2.7006.400)");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function _sock($url)
{
    $host = parse_url($url, PHP_URL_HOST);
    $port = parse_url($url, PHP_URL_PORT);
    $port = $port ? $port : 80;
    $scheme = parse_url($url, PHP_URL_SCHEME);
    $path = parse_url($url, PHP_URL_PATH);
    $query = parse_url($url, PHP_URL_QUERY);
    if ($query) {
        $path .= '?' . $query;
    }
    if ($scheme == 'https') {
        $host = 'ssl://' . $host;
    }
    $fp = fsockopen($host, $port, $error_code, $error_msg, 1);
    if (!$fp) {
        return array('error_code' => $error_code, 'error_msg' => $error_msg);
    } else {
        stream_set_blocking($fp, 1);
        $header = "GET {$path} HTTP/1.1\r\n";
        $header .= "Host: {$host}\r\n";
        $header .= "Connection: close\r\n\r\n";
        fwrite($fp, $header);
        usleep(1000);
        fclose($fp);
        return array('error_code' => 0);
    }
}

function delFile($dir)
{
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                delFile($fullpath);
            }
        }
    }
    closedir($dh);
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

function weiyuanchuang()
{
    global $mysqli;
    $sql = "select title,new from weiyuanchuang order by id desc";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $wyc[$row['title']] = $row['new'];
        }
        return $wyc;
    }
}

function moban_t_n($moban_id)
{
    global $mysqli;
    $sql = "select title,name from templates where id=" . $moban_id;
    $row = $mysqli->query($sql)->fetch_assoc();
    return $row;
}

function moban_ok($type)
{
    global $mysqli;
    $moban_ok = array();
    $sql = "select * from templates where ok=1 and title like '" . $type . "%' order by id desc";
    $result = $mysqli->query($sql);
    if ($result->num_rows) {
        while ($row = $result->fetch_assoc()) {
            $moban_ok[] = $row;
        }
    }
    return $moban_ok;
}

function domain_moban_id($domain)
{
    global $mysqli;
    $sql = "select title,pc_moban_id,mo_moban_id from domains";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        if ($domain == datade($row['title'])) {
            $data['pc_moban_id'] = $row['pc_moban_id'];
            $data['mo_moban_id'] = $row['mo_moban_id'];
        }
    }
    return $data;
}

function moban_set()
{
    global $mysqli;
    $sql = "select id from domains order by id desc";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $pc_moban = moban_ok('moban');
        if ($pc_moban) {
            $pc_moban_rand = array_rand($pc_moban, 1);
            $pc_moban_id = $pc_moban[$pc_moban_rand]['id'];
        } else {
            $pc_moban_id = 0;
        }
        $mo_moban = moban_ok('mobile');
        if ($mo_moban) {
            $mo_moban_rand = array_rand($mo_moban, 1);
            $mo_moban_id = $mo_moban[$mo_moban_rand]['id'];
        } else {
            $mo_moban_id = 0;
        }
        $mysqli->query("update domains set pc_moban_id=" . $pc_moban_id . ",mo_moban_id=" . $mo_moban_id . " where id=" . $row['id']);
    }
    return true;
}

function moban_rand()
{
    global $mysqli;
    $mysqli->query("update domains set pc_moban_id=0,mo_moban_id=0");
    return true;
}

function get_rand_number($length = 4, $table = '', $ziduan = 'title')
{
    global $mysqli;
    $connt = 0;
    $temp = array();
    $shuju = "";
    $count = $mysqli->query("SELECT count(*) as count FROM {$table} order by id desc")->fetch_object()->count;
    if ($count && $length) {
        if ($length > $count) {
            $result = $mysqli->query("SELECT {$ziduan} FROM {$table} order by id desc");
            while ($row = $result->fetch_assoc()) {
                $data[] = $ziduan == "*" ? $row : $row[$ziduan];
            }
            for ($i = 0; $i < $length; $i++) {
                $gjc = array_rand($data, 1);
                $shuju[] = $data[$gjc];
            }
        } else {
            $start = $mysqli->query("select id from {$table} order by id asc limit 1")->fetch_object()->id;
            $end = $mysqli->query("select id from {$table} order by id desc limit 1")->fetch_object()->id;
            while ($connt < $length) {
                $temp[] = mt_rand($start, $end);
                $data = array_unique($temp);
                $connt = count($data);
            }
            $inid = implode(",", $data);
            $result = $mysqli->query("select {$ziduan} from {$table} where id in ({$inid}) order by id desc");
            while ($row = $result->fetch_assoc()) {
                $shuju[] = $ziduan == "*" ? $row : $row[$ziduan];
            }
        }
    }
    return $shuju;
}

function urlinfo_add($data)
{
    global $mysqli;

    $table = 'c_yuan';

    //数据处理
    $info['name'] = $data['name'];
    $info['url'] = $data['url'];
    $info['in'] = $data['in'];
    $info['out'] = 'UTF-8';
    $info['ok'] = 1;

    //url选择器
    $urlcss = $data['urlcss'];
    $urlattr = $data['urlattr'];
    $urlpath = $data['urlpath'];

    //标题选择器
    $titlecss = $data['titlecss'];
    $titleattr = $data['titleattr'];

    //内容选择器
    $concss = $data['concss'];
    $conattr = $data['conattr'];
    $noattr = $data['noattr'];


    $reg_c['title'] = array($titlecss, $titleattr);
    $reg_c['content'] = array($concss, $conattr, $noattr);

    $info['reg_t'] = '["a","href"]';
    $info['reg_c'] = json_encode($reg_c);
    $info['rang_t'] = $urlcss;


    if ($titlecss != '' and $titleattr != '' and $concss != '' and $conattr != '') {
        $sql = "insert into " . $table . " (url,reg_t,reg_c,rang_t,out_c,in_c,name,ok)  value('" . $info['url'] . "','" . $info['reg_t'] . "','" . $info['reg_c'] . "','" . $info['rang_t'] . "','" . $info['out'] . "','" . $info['in'] . "','" . $info['name'] . "'," . $info['ok'] . ")";

        $res = $mysqli->query($sql);
        if ($mysqli->insert_id) {
            return true;
        }
    }


}