<?php

require ('inc/lic_admin.php');
set_time_limit(0);
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : "";
$action = isset($_GET['action']) ? $_GET['action'] : "";
$page = isset($_GET['page']) ? $_GET['page'] : 1;
switch ($act) {
	case "keywords":
		$title = "关键词管理";
	break;
	case 'key_jump':
		$title = "关键词跳转";
	break;
	case 'weiyuanchuang':
		$title = "采集伪原创";
	break;
	case 'domains':

		$title = "域名管理";
	break;
	case 'url':
		$title = "索引池管理";
	break;
	case 'qurl':
		$title = "权重池管理";
	break;
	case 'juzi2':
		$title = "句子管理";
	break;
	case 'juzi':
		$title = "段子管理";
	break;
	case 'shipin':
		$title = "视频管理";
	break;
	case 'm_title':
		$title = "title";
	break;
	case 'm_key':
		$title = "keywords";
	break;
	case 'm_des':
		$title = "description";
	break;
	case 'm_url':
		$title = "URL样式";
	break;
	case 'a_title':
		$title = "文章标题管理";
	break;
	case 'a_content':
		$title = "文章内容管理";
	break;
	case 'waiurl':
		$title = "外推管理";
	break;
}
switch ($action) {
	case "add":
		$data['title'] = isset($_POST['title']) ? $_POST['title'] : "";
		if ($act == 'url' || $act == 'qurl' || $act == 'key_jump') {
			if (!(strpos($data['title'], 'http') !== false)) {
				$data['title'] = "http://" . $data['title'];
			}
		}
		$data['page'] = isset($_POST['leixing']) ? $_POST['leixing'] : "";
		if (!empty($act) && !empty($data)) {
			info_add($act, $data);
		}
	break;
	case 'edit':
		$id = isset($_GET['id']) ? $_GET['id'] : "";
	break;
	case 'save':
		$data['title'] = isset($_POST['title']) ? $_POST['title'] : "";
		$data['jumpkey'] = isset($_POST['jumpkey']) ? $_POST['jumpkey'] : "";
		$data['text'] = isset($_POST['text']) ? $_POST['text'] : "";
		$data['new'] = isset($_POST['new']) ? $_POST['new'] : "";
		$data['page'] = isset($_POST['page']) ? $_POST['page'] : 0;
		$data['pc_moban_id'] = isset($_POST['pc_moban_id']) ? $_POST['pc_moban_id'] : "0";
		$data['mo_moban_id'] = isset($_POST['mo_moban_id']) ? $_POST['mo_moban_id'] : "0";
		$id = isset($_GET['id']) ? $_GET['id'] : "";
		if (!empty($act) && !empty($data['title']) && !empty($id) && is_numeric($id)) {
			info_save($act, $data, $page, $id);
		}
	break;
	case 'del':
		$id = isset($_GET['id']) ? $_GET['id'] : "";
		if (!empty($act) && !empty($id) && is_numeric($id)) {
			info_del($act, $page, $id);
		}
	break;
	case 'del_all':
		if ($act == 'url' || $act == 'qurl') {
			$mysqli->query("delete from $act where user_id=1");
		} else {
			info_del_all($act);
		}
	break;
	case 'one_set':
		if (moban_set()) {
			header('Location: info.php?act=' . $act);
		}
	break;
	case 'one_rand':
		if (moban_rand()) {
			header('Location: info.php?act=' . $act);
		}
	break;
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?>2018蜘蛛池</title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<link rel="stylesheet" type="text/css" href="css/pageGroup.css" />
	<style>
		#loading{display:none}
	</style>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script>
		$(function(){
			$('.userbtn').click(function(){
				$('#loading').show();
			});
			$('#pageGro a').click(function(){
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
				<img src="img/coin02.png" style="float:left;margin-top:10px;" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;</span>&nbsp;<?=$title?>&nbsp;(导入失败点此链接?文件名请不要用中文,请尽量保持单文件数据在5000以下)
			</div>
		</div>

		<div class="page">
			<div class="connoisseur">
				<?if($act=='url'){?>
					<div style="line-height: 25px;color:blue;">需要蜘蛛引流的链接地址,快速收录</div>
				<?}?>
				<?if($act=='key_jump'){?>
					<div style="line-height: 25px;color:blue;">根据{主关键词}跳转,页面中至少保留一个{主关键词}标签<br/>此处关键词必须在“关键词管理”中存在<br/>导入数据使用“跳转地址|关键词”样式</div>
				<?}?>
				<?if($act=='qurl'){?>
					<div style="line-height: 25px;color:blue;">导权重、提升排名;导入请使用 锚链接|锚文字 样式</div>
				<?}?>
				<?if($act=='miaoshu'){?>
					<div style="line-height: 25px;color:blue;">这里放置的是描述部分文字,方便自定义。如果此部分留空,默认填充随机关键词</div>
				<?}?>
				<?if($act=='m_title'){?>
					<div style="line-height: 25px;color:blue;">适用标签: {随机关键词} {随机句子} {主关键词} {对应采集文章标题}<br/>{主关键词}:当前页面所有此标签调用数据一致</div>
				<?}?>
				<?if($act=='m_key'){?>
					<div style="line-height: 25px;color:blue;">适用标签: {随机关键词} {主关键词}</div>
				<?}?>
				<?if($act=='m_des'){?>
					<div style="line-height: 25px;color:blue;">适用标签: {随机关键词} {主关键词} {对应采集文章标题}</div>
				<?}?>
				<?if($act=='m_url'){?>
					<div style="line-height: 25px;color:blue;">适用标签: {随机关键词拼音} {当前域名} {顶级域名} {随机域名} {随机字符} {随机数字} {随机索引池} {年} {月} {日}<br/>{随机索引池}不需要加http://<br/>请参照论坛“蜘蛛喜欢URL样式大全”</div>
				<?}?>
				<?if($act=='a_title'){?>
					<div style="line-height: 25px;color:blue;">适用标签: {随机关键词} {主关键词} {采集文章标题} {随机句子}</div>
				<?}?>
				<?if($act=='a_content'){?>
					<div style="line-height: 25px;color:blue;">适用标签: {随机域名} {当前域名} {顶级域名} {随机关键词} {主关键词} {页面地址} {随机段子} {随机图片} {随机视频} {随机人名} {对应采集文章标题} {对应采集文章内容} {随机权重池}</div>
				<?}?>
				<?if($act=='juzi'){?>
					<div style="line-height: 25px;color:blue;">每条段子控制在200个汉字以内</div>
				<?}?>
				<?if($act=='juzi2'){?>
					<div style="line-height: 25px;color:blue;">每条句子控制在20个汉字以内</div>
				<?}?>
				<?if($act=='waiurl'){?>
					<div style="line-height: 25px;color:blue;">{domains}表示要发送的域名</div>
				<?}?>
				<div class="conform clear">
					<?if($act!="a_content"){?>
						<div class="cfD">
							<form action="?act=<?=$act?>&action=add" method="post">
								添加新数据:
								<input class="userinput vpr" type="text" name="title" id="title" <?if($act=='url'||$act=="m_url"){?>placeholder="http://"<?}?><?if($act=='domains'){?>placeholder="xxx.com"<?}?><?if($act=='qurl'){?>placeholder="锚链接|锚文字"<?}?><?if($act=='weiyuanchuang'){?>placeholder="内容|替换内容"<?}?><?if($act=='key_jump'){?>placeholder="跳转地址|关键词"<?}?> />
								<?if($act=='m_url'){?>
								<select name="leixing">
									<option value="0">通用</option>
									<option value="1">首页</option>
									<option value="2">内页</option>
									<option value="3">地图</option>
								</select>
									<style>
										button.userbtn {width:70px!important}
									</style>
								<? }?>
								<button class="userbtn" style="width:100px">添加</button>
								<a class="userbtn" href="?act=<?=$act?>&action=del_all">删除所有数据</a>
							</form>
						</div>
						<div class="upload" id="upload">
							<form action="import.php?act=<?=$act?>" method="post" enctype="multipart/form-data">
								<input type="file" name="file">
								<input type="submit" value="导入txt(<2M)" class="userbtn">
							</form>
						</div>
					<?}else{?>
						<div class="cfD">
						<a class="userbtn" href="?act=<?=$act?>&action=del_all">删除所有数据</a>
						<a href="content.php?from=a_content&action=add" class="userbtn">添加</a>
						</div>
					<?}if($act=="domains"){?>
					<div style="margin:5px 0;float:right" class="cfD"><a class="userbtn" href="?act=<?=$act?>&action=one_rand">一键随机</a><a class="userbtn" href="?act=<?=$act?>&action=one_set">一键指定</a></div>
					<?}?>
				</div>
				<div class="conShow">
					<span style="float:right">合计(<?php
 if($act=='url' || $act=='qurl'){echo data_num($act,'','','',"user_id=1");}else{echo data_num($act);}?>)</span>
					<table border="1" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td width="66px" class="tdColor tdC">序号</td>
							<?if($act!="a_content"){?><td class="tdColor">内容</td><?}?>
							<?php
 if($act=="url"){echo "<td class=\"tdColor\" width='100px'>引蜘蛛次数</td><td class=\"tdColor\" width='60px'>详情</td>";}if($act=="m_url"){echo "<td class=\"tdColor\" width='100px'>类型</td>";}if($act=="qurl"){echo "<td class=\"tdColor\" width='300px'>锚文字</td><td class=\"tdColor\" width='100px'>引蜘蛛次数</td><td class=\"tdColor\" width='60px'>详情</td>";}if($act=="weiyuanchuang"){echo "<td class=\"tdColor\" width='300px'>替换内容</td>";}if($act=="key_jump"){echo "<td class=\"tdColor\" width='300px'>关键词</td>";}if($act=="domains"){echo "<td class=\"tdColor\" width='200px'>PC模板</td><td class=\"tdColor\" width='200px'>手机模板</td>";}?>
							<td width="100px" class="tdColor">操作</td>
						</tr>
						<?php
 if($act=='url' || $act=='qurl'){$data=list_data($act,$page,'','user_id=1');}else{$data=list_data($act,$page);}if($data){foreach($data as $val){if (isset($id)&& $id == $val['id'] && $action == "edit"){?>
						<tr bgcolor='#999'>
							<td colspan='6'>
								<form action='?act=<?=$act?>&action=save&page=<?=$page?>&id=<?=$id?>' method='post'>
									<table border='0' width='100%'>
										<tr>
											<td height='40px' width='66px'><?=$val['id']?></td>
											<td style='text-align:left;padding-left:20px;'><input type='text' name='title' value='<?if($act=='domains'){echo datade($val['title']);}else{echo $val['title'];}?>'/></td>
											<?if ($act == "url"){?>
											<td width='100px'><?=$val['count']?></td><td width='60px'><a href='javascript:void(0)' onclick="javascript:alert('Google:<?=$val['google']?> | Baidu:<?=$val['baidu']?> | Bing:<?=$val['bing']?> | Yahoo:<?=$val['yahoo']?> | Sogou:<?=$val['sogou']?> | Haosou:<?=$val['haosou']?> | Mobile:<?=$val['mobile']?> | Shenma:<?=$val['shenma']?> | Yitao:<?=$val['yitao']?> | Esou:<?=$val['easou']?>')">查看</a></td>
											<?}if ($act == "m_url"){?>
												<td width='100px'><select name="page">
														<option value="0">通用</option>
														<option value="1"<?if($val['page']==1)echo "selected"?>>首页</option>
														<option value="2"<?if($val['page']==2)echo "selected"?>>内页</option>
														<option value="3"<?if($val['page']==3)echo "selected"?>>地图</option>
													</select></td>
											<?}if ($act == "qurl"){?>
												<td width='300px'><input type='text' name='text' value='<?=$val['text']?>'/></td><td width='100px'><?=$val['count']?></td><td width='60px'><a href='javascript:void(0)' onclick="javascript:alert('Google:<?=$val['google']?> | Baidu:<?=$val['baidu']?> | Bing:<?=$val['bing']?> | Yahoo:<?=$val['yahoo']?> | Sogou:<?=$val['sogou']?> | Haosou:<?=$val['haosou']?> | Mobile:<?=$val['mobile']?> | Shenma:<?=$val['shenma']?> | Yitao:<?=$val['yitao']?> | Esou:<?=$val['easou']?>')">查看</a></td>
											<?}if ($act == "weiyuanchuang"){?>
											<td  width='280px' style='text-align:left;padding-left:20px;'><input type='text' name='new' value='<?=$val['new']?>'/></td>
											<?}if ($act == "key_jump"){?>
												<td  width='280px' style='text-align:left;padding-left:20px;'><input type='text' name='jumpkey' value='<?=$val['jumpkey']?>'/></td>
											<?}if ($act == "domains"){?>
												<td  width='180px' style='text-align:left;padding-left:20px;'>
													<select name="pc_moban_id">
														<option value="0">随机模板</option>
														<?php
 $moban_pc=moban_ok('moban');foreach($moban_pc as $k=>$v){?>
														<option value="<?=$v['id']?>"<?if($val['pc_moban_id']==$v['id'])echo "selected"?>><?=$v['name']?></option>
														<?}?>
													</select>
												</td>
												<td  width='180px' style='text-align:left;padding-left:20px;'>
													<select name="mo_moban_id">
														<option value="0">随机模板</option>
														<?php
 $moban_pc=moban_ok('mobile');foreach($moban_pc as $k=>$v){?>
															<option value="<?=$v['id']?>"<?if($val['mo_moban_id']==$v['id'])echo "selected"?>><?=$v['name']?></option>
														<?}?>
													</select>
												</td>
											<?}?>
											<td width='100px'>
												<button><img src='img/ok.png'></button><a href='?act=<?=$act?>&page=<?=$page?>'><img src='img/no.png'></a></td>
										</tr>
									</table>
								</form>
							</td>
						</tr>
						<?}else {?>
						<tr>
							<td height='40px'><?=$val['id']?></td>
							<?if($act != "a_content"){?>
								<td style='text-align:left;padding-left:20px;'><?if($act=='domains'){echo datade($val['title']);}else{echo $val['title'];}?></td>
							<?}if ($act == "url"){?>
							<td><?=$val['count']?></td><td width='60px'><a href='javascript:void(0)' onclick="javascript:alert('Google:<?=$val['google']?> | Baidu:<?=$val['baidu']?> | Bing:<?=$val['bing']?> | Yahoo:<?=$val['yahoo']?> | Sogou:<?=$val['sogou']?> | Haosou:<?=$val['haosou']?> | Mobile:<?=$val['mobile']?> | Shenma:<?=$val['shenma']?> | Yitao:<?=$val['yitao']?> | Esou:<?=$val['easou']?>')">查看</a></td>
							<?}if ($act == "m_url"){?>
							<td><?php
 switch($val['page']){case 1: echo "首页";break;case 2: echo '内页';break;case 3: echo '地图';break;default: echo '通用';}?></td>
							<?}if ($act == "qurl"){?>
								<td width='100px'><?=$val['text']?></td><td><?=$val['count']?></td><td width='60px'><a href='javascript:void(0)' onclick="javascript:alert('Google:<?=$val['google']?> | Baidu:<?=$val['baidu']?> | Bing:<?=$val['bing']?> | Yahoo:<?=$val['yahoo']?> | Sogou:<?=$val['sogou']?> | Haosou:<?=$val['haosou']?> | Mobile:<?=$val['mobile']?> | Shenma:<?=$val['shenma']?> | Yitao:<?=$val['yitao']?> | Esou:<?=$val['easou']?>')">查看</a></td>
							<?}if ($act == "weiyuanchuang"){?>
								<td><?=$val['new']?></td>
							<?}if ($act == "key_jump"){?>
								<td><?=$val['jumpkey']?></td>
							<?}if ($act == "domains"){?>
								<td>
									<?php
 if($val['pc_moban_id']){$moban_t_n=moban_t_n($val['pc_moban_id']);echo $moban_t_n['name'];}else{echo '随机模板';}?>
								</td><td>
									<?php
 if($val['mo_moban_id']){$moban_t_n=moban_t_n($val['mo_moban_id']);echo $moban_t_n['name'];}else{echo '随机模板';}?>
								</td>
							<?}?>
							<td>
								<?if($act != "a_content"){?>
								<a href='?act=<?=$act?>&action=edit&page=<?=$page?>&id=<?=$val['id']?>'><img class='operation delban' src='img/update.png'></a>&nbsp;<a href='?act=<?=$act?>&action=del&page=<?=$page?>&id=<?=$val['id']?>'><img class='operation delban' src='img/delete.png'></a>
								<?}else{?>
								<a href='content.php?from=<?=$act?>&action=edit&page=<?=$page?>&id=<?=$val['id']?>'><img class='operation delban' src='img/update.png'></a>&nbsp;<a href='?act=<?=$act?>&action=del&page=<?=$page?>&id=<?=$val['id']?>'><img class='operation delban' src='img/delete.png'></a>
								<?}?>
							</td>
						</tr>
						<? }}}?>
					</table>

					<div class="paging">
						<div id="pageGro" class="cb clear">
							<?php
 if($act=='url' || $act=='qurl'){echo list_page($act,$page,'','user_id=1');}else{echo list_page($act,$page);}?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>