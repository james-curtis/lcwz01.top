﻿<?php


require('inc/lic_admin.php');
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
    header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : false; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>首页-2020蜘蛛池</title>
    <link rel="stylesheet" type="text/css" href="css/css.css"/>
    <script type="text/javascript" src="js/echarts.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $(".loadimg").click(function () {
                $("#loading").show();
            })
        })
    </script>
</head>
<body>
<div id="loading">
    <img src="img/load.gif">
</div>
<div id="pageAll">
    <div class="page">
        <div class="title">蜘蛛访问量<span>今日数量:<?= shishi_spider() ?>&nbsp;&nbsp;<a href="?"
                                                                                class="loadimg">7日(<?= data_num("spider", 7) ?>)</a> | <a
                        href="?act=30"
                        class="loadimg">30日(<?= data_num("spider", 30) ?>)</a> | 合计(<?= data_num("spider", "all") ?>)</a>
                | <a href="?act=hour" class="loadimg" style="color:red;">查看过去三天24小时数据分析</a></span></div>
        <div id="main" style="width: 900px;height:300px;"></div>
        <?php
        if ($act == 'hour') {
            ?>
            <div id="main3" style="width: 900px;height:300px;"></div>
            <?php
        } ?>
        <div class="title">今日蜘蛛来源<span><?= spider_type_list(1) ?></span></div>
        <div id="main2" style="width: 800px;height:500px;"></div>
    </div>
</div>

<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    <?php
    if (is_numeric($act) && $act > 0) {
        for ($i = $act - 1; $i >= 0; $i--) {
            $xAxisdata[] = "'" . date('n/j', time() - $i * 24 * 3600) . "'";
            $seriesdata[] = data_num('spider', '', date('Y-m-d', time() - $i * 24 * 3600));
        }
    } else {
        for ($i = 7; $i > 0; $i--) {
            $xAxisdata[] = "'" . date('n/j', time() - $i * 24 * 3600) . "'";
            $seriesdata[] = data_num('spider', '', date('Y-m-d', time() - $i * 24 * 3600));
        }
    }$xAxisdata = implode(',', $xAxisdata);$seriesdata = implode(',', $seriesdata);?>
    var myChart = echarts.init(document.getElementById('main'));
    // 指定图表的配置项和数据
    option = {
        tooltip: {
            trigger: 'axis'
        },
//			toolbox: {
//				show: true,
//				feature: {
//					saveAsImage: {}
//				}
//			},
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: [<?=$xAxisdata?>]
        },
        yAxis: {
            type: 'value',
            axisLabel: {
                formatter: '{value}'
            }
        },
        series: [
            {
                name: '蜘蛛访问量',
                type: 'line',
                data: [<?=$seriesdata?>],
                markPoint: {
                    data: [
                        {type: 'max', name: '最大值'},
                        {type: 'min', name: '最小值'}
                    ]
                },
                markLine: {
                    data: [
                        {type: 'average', name: '平均值'}
                    ]
                }
            }
        ]
    };
    myChart.setOption(option);
</script>
<script type="text/javascript">
    <?php
    $sql = "select title from spiderset where ok=1 order by id asc";$result = $mysqli->query($sql);while ($row = $result->fetch_assoc()) {
        $option2[] = "'" . $row['title'] . "'";
        $series[] = "{value:" . data_num('spider', '1', '', $row['title']) . ", name:'" . $row['title'] . "'}";
    }$option2_data = implode(',', $option2);$series_data = implode(',', $series);?>
    var myChart2 = echarts.init(document.getElementById('main2'));
    option2 = {
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'right',
            data: [<?=$option2_data?>]
        },
        series: [
            {
                name: '访问来源',
                type: 'pie',
                radius: '75%',
                center: ['50%', '40%'],
                data: [<?=$series_data?>],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };
    myChart2.setOption(option2);
</script>
<script type="text/javascript">
    <?php
    if($act == 'hour'){for ($i = 3; $i >= 1; $i--) {
        $data = implode(',', hour_data_num('spider', $i));
        $option3[] = "{name:'" . date('n/j', time() - $i * 24 * 3600) . "',type:'line',stack: '总量',data:[" . $data . "]}";
        $option3_legend[] = "'" . date('n/j', time() - $i * 24 * 3600) . "'";
    }$option3_series_data = implode(',', $option3);$option3_legend_data = implode(',', $option3_legend);?>
    var myChart3 = echarts.init(document.getElementById('main3'));
    option3 = {
//			title: {
//				text: '折线图堆叠'
//			},
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: [<?=$option3_legend_data?>]
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
//			toolbox: {
//				feature: {
//					saveAsImage: {}
//				}
//			},
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24']
        },
        yAxis: {
            type: 'value'
        },
        series: [<?=$option3_series_data?>]
    };
    myChart3.setOption(option3);
    <?php
    }?>
</script>
</body>
</html>