<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>尚狐网络-管理后台</title>
<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/index.js"></script>
<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
<link rel="stylesheet" href="__PUBLIC__/Css/index.css" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<base target="iframe"/>
<head>
</head>
<body>
	<div id="top">
		<div class="exit">
			<?php echo (session('username')); ?>欢迎您回来！
		</div>
		<div class="menu">
 			<a href="http://www.gaoxiaosile.com" target="_blank">演示地址</a>
 			<a href="<?php echo U(GROUP_NAME.'/Index/index');?>" target="_self">管理后台</a>
 			<a href="/" target="_blank">网站主页</a>
 			<a href="<?php echo U(GROUP_NAME.'/Index/logout');?>" target="_self">安全退出</a>
		</div>

	</div>
	<div id="left">
 		<dl>
			<dt>文档管理</dt>
			<dd><a href="<?php echo U(GROUP_NAME.'/Category/index');?>">文档栏目</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/Article/index');?>">文档列表</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/Article/add');?>">添加文档</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/Article/addgif');?>">GIF添加</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/Article/trach');?>">回收站</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/Singlepage/index');?>">单页文档</a></dd>
		</dl>
<!-- 		<dl>
			<dt>需求管理</dt>
			<dd><a href="<?php echo U(GROUP_NAME.'/Require/index');?>">需求列表</a></dd>
		</dl> -->
		<dl>
			<dt>系统管理</dt>
			<dd><a href="<?php echo U(GROUP_NAME.'/System/webconfig');?>">网站设置</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/Friendlink/index');?>">友情链接</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/User/index');?>">管理员</a></dd>
			<dd><a href="<?php echo U(GROUP_NAME.'/Cache/removeHtml');?>">更新缓存</a></dd>
		</dl>
		<dl>
			<dt>工具导航</dt>
			<dd><a href="http://www.shangfox.com" target="_blank">尚狐网络</a></dd>
			<dd><a href="http://www.gaoxiaosile.com" target="_blank">演示地址</a></dd>
		</dl>
	</div>
	<div id="right">
		<iframe name="iframe" src="/Admin/Index/i.html"></iframe>
	</div>
</body>
</html>