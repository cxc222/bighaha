<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo (C("WEB_NAME")); ?> - 后台管理系统 - 尚狐网络(www.shangfox.com)</title>
		<meta name="description" content="<?php echo (C("WEB_DESRIPTION")); ?>" />
		<meta name="keywords" content="<?php echo (C("WEB_KEYWORDS")); ?>" />
		<link rel="stylesheet" href="__PUBLIC__/Css/login.css" />
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/Js/login.js"></script>
		<script type="text/javascript">
			var URL = '<?php echo U(GROUP_NAME."/Login/verify");?>/';
			bg = new Array(2); //设定图片数量，如果图片数为3，这个参数就设为2，依次类推
			bg[0] = '__PUBLIC__/Images/a.jpg'; //显示的图片路径，可用http://
			bg[1] = '__PUBLIC__/Images/b.jpg';
			bg[2] = '__PUBLIC__/Images/c.jpg';
			index = Math.floor(Math.random() * bg.length);
			document.write("<BODY BACKGROUND="+bg[index]+">");

		</script>
	</head>
	<body>
		<a href="/" title="返回 -> <?php echo (C("WEB_NAME")); ?>"><div id="top"></div></a>
		<div class="login">	
			<form action="<?php echo U(GROUP_NAME.'/Login/login');?>" method="post" id="login">
			<table border="1" width="100%">
				<tr>
					<th>帐号:</th>
					<td>
						<input type="username" name="username" class="len250"/>
					</td>
				</tr>
				<tr>
					<th>密码:</th>
					<td>
						<input type="password" class="len250" name="password"/>
					</td>
				</tr>
				<tr>
					<th>验证:</th>
					<td>
						<input type="code" class="len250" name="code"/> <a href="javascript:void(change_code(this));"><img src="<?php echo U(GROUP_NAME.'/Login/verify');?>" id="code"/> </a>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-left:104px;"> <input type="submit" class="submit" value="登录"/></td>
				</tr>
				<tr>
					<td colspan="2" style="padding-left:104px;color:#333;font-size:13px;line-height:24px;"> 技术支持：<a href="http://www.shangfox.com" title="成都做网站,成都尚狐网络" style="color:#333;" target="_blank">成都尚狐网络 www.shangfox.com</a>&nbsp;&nbsp;&nbsp;&nbsp;QQ：406333726</td>
				</tr>
			</table>
		</form>
	</div>
	</body>
</html>