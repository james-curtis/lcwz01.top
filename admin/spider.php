<?php


require 'inc/lic_admin.php';
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : "";
$type = isset($_GET['type']) ? $_GET['type'] : "";
$date = isset($_GET['date']) ? $_GET['date'] : "";
$num = $date ? 0 : 1;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜘蛛日志-<?php 
echo SYSTEM_NAME;
?>
</title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<link rel="stylesheet" type="text/css" href="css/pageGroup.css" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/rili/lhgcore.js"></script>
	<script type="text/javascript" src="js/rili/lhgcalendar.js"></script>
	<style>
		#loading{display:none}
	</style>
	<script>
		$(function(){
			$('#pageGro a,#datesubmit').click(function(){
				$('#loading').show();
			});
		})
	</script>
</head>

<body>
	<div id="loading">
		<img src="img/load.gif">
	</div>
	<div id="pageAll">
		<div class="pageTop">
			<div class="page">
				<img src="img/coin02.png" style="float:left;margin-top:10px;" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;-</span>&nbsp;蜘蛛日志
				<div style="float:right">7日(<em><?php 
echo data_num('spider', 7);
?>
</em>) | 30日(<em><?php 
echo data_num('spider', 30);
?>
</em>)</div>
			</div>
		</div>
		<div style="text-align:right">合计(<em><?php 
echo data_num('spider', 'all');
?>
</em>) | <em><?php 
echo spider_type_list();
?>
</em><br>当前(<span><?php 
echo shishi_spider($date);
?>
</span>) | <span><?php 
echo spider_type_list($num, $date);
?>
</span></div>
		<div class="page">
			<div class="connoisseur">
				<div class="conShow" id="data_show">
					<div class="clear" style="margin-bottom:10px;">
						<div style="float:left;"><form action="" method="get">选择日期:<input type="text" name="date" id="dateval" style="width:100px;line-height:20px;" readonly value="<?php 
echo $date ? $date : date('Y-m-d');
?>
" onclick="J.calendar.get();"><button id="datesubmit" style="line-height: 25px;background:#47a4e1;color:#fff;">确定</button></form></div>
					</div>
					<span style="float:right"><!--<a class="userbtn" href="?act=del_all">删除所有数据</a>--></span>
					<table border="1" cellspacing="0" cellpadding="0" width="100%">
						<tr>
<!--							<td width="150" class="tdColor tdC">序号</td>-->
							<td width="85" class="tdColor">搜索引擎</td>
							<td class="tdColor">访问地址</td>
<!--							<td class="tdColor">来路地址</td>-->
							<td width="120" class="tdColor">日期</td>
							<td width="150" class="tdColor">IP地址</td>
						</tr>
						<?$data=spider_list_data($date,$page);if($data){foreach($data as $val){?>
						<tr height="40px">
							<td style="text-align:left;padding-left:20px;width:65px;"><?php 
echo $val['ssyq'];
?>
</td>
							<td style="text-align:left;padding-left:20px;"><?php 
echo $val['fwdz'];
?>
</td>
							<td style="text-align:left;padding-left:20px;width:100px;"><?php 
echo date('m-d H:i:s', $val['rq']);
?>
</td>
							<td style='width:150px;'><?php 
echo $val['ipdz'];
?>
<br><span style="font-size:10px;"><?php 
echo $val['ipinfo'];
?>
</span></td>
						</tr>
						<?}}?>
					</table>
<!--					<table border="1" cellspacing="0" cellpadding="0" width="100%" id="neirong">-->
<!--					</table>-->

<!--					<div class="paging">-->
<!--						<div id="pageGro" class="cb clear">-->
<!--						</div>-->
<!--					</div>-->
					<div class="paging">
						<div id="pageGro" class="cb clear">
							<?php 
echo spider_list_page($date, $page);
?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>