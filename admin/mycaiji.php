<?php

require ('inc/lic_admin.php');
set_time_limit(0);
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['is_login']) || empty($_SESSION['admin_id']) || empty($_SESSION['is_login'])) {
	header("Location: log.php");
}
$act = isset($_GET['act']) ? $_GET['act'] : "";
if($act == 'add'){
  if($_POST!=''){
    urlinfo_add($_POST);
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?></title>
<link rel="stylesheet" type="text/css" href="css/css.css" />
<link rel="stylesheet" type="text/css" href="css/pageGroup.css" />
	<style>
		#loading{display:none}
    .block{
      display:block;
      margin:20px 0;
    }
    .block .left{
      display:inline-block;
      width:300px;
      text-align:right;
      
    }
    .block .right{
      display:inline-block;
      width:100px;
      text-align:right;
      
    }
    .block .userinput{
      width:300px;
      height:40px;
      border:1px solid #ccc;
      text-indent:15px;
      color:#999;
    }
    .top{
      margin-top:50px;
    }
    .userbtn{
      margin-left:300px;
      width:135px;
      height:40px;
      border:none;
      font-size:16px;
      color:#fff;
      background-color:#47a4e1;
    }
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
		<div class="pageTop">
			<div class="page">
				<img src="img/coin02.png" style="float:left;margin-top:10px;" /><span><a href="main.php">首页</a>&nbsp;-&nbsp;-</span>&nbsp;定制采集&nbsp;
			</div>
		</div>
	<div id="loading">
		<img src="img/load.gif">
	</div>
	<form class="top" action="?act=add" method="post">
    <div class="block">
      <span class="left">规则名称：</span><input class=" userinput " name="name" type="text" value="" >
      
    </div>
    <div class="block">
      <span class="left">选中文章格式：</span>
      <select name="in">
        <option value="UTF-8" >UTF-8</option>
        <option value="GBK" >GBK</option>
      </select>
      
    </div>
    <div class="block"> 
      <span class="left">列表文章URL地址：</span><input class=" userinput " name="url" type="text" value="">
      
    </div>
    <div class="block">
      <span class="left">列表文章URL选择器：</span><input class=" userinput " name="urlcss" type="text" value="">
      <span class="right">属性：</span><input class=" userinput " name="urlattr" type="text" value="" >
      <span class="right">路径：</span>
      <select name="urlpath">
        <option value="0" >相对路径</option>
        <option value="1" >绝对路径</option>
      </select>
    </div>
    <div class="block">
      <span class="left">文章标题选择器：</span><input class=" userinput" name="titlecss" type="text" value="">
      <span class="right">属性：</span><input class=" userinput " name="titleattr" type="text" value="" >
    </div>
    <div class="block">
      <span class="left">文章内容选择器：</span><input class=" userinput vpr" name="concss" type="text" value="">
      <span class="right">属性：</span><input class=" userinput " name="conattr" type="text" value="" >
      <span class="right">排除属性：</span><input class=" userinput " name="noattr" type="text" value="" >
    </div>
    
    <button class="userbtn" type="sumbit">提交</button>
   
  </form>
</body>
</html>