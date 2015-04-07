<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
	</head>
	<body>
		<div class="navlist">
			<a href="<?php echo U(GROUP_NAME.'/System/webconfig');?>" class="cur">网站配置</a>
			<a href="<?php echo U(GROUP_NAME.'/System/verify');?>">验证码</a>
		</div>
		<form method="post" action="<?php echo U(GROUP_NAME.'/System/updateWebconfig');?>">
			<table border="1" width="100%" class="table">
				<tr>
					<td align="right" width="20%">网站名称:</td>
					<td width="80%">
						<input type="code" class="len250" name="WEB_NAME" value='<?php echo (C("WEB_NAME")); ?>'/> 
					</td>
				</tr>
				<tr>
					<td align="right">网站标题:</td>
					<td>
						<input type="code" class="len250" name="WEB_TITLE" value='<?php echo (C("WEB_TITLE")); ?>'/> 
					</td>
				</tr>
				<tr>
					<td align="right">顶部欢迎词:</td>
					<td>
						<input type="code" class="len250" name="WEB_WELCOME" value='<?php echo (C("WEB_WELCOME")); ?>'/> 
					</td>
				</tr>				<tr>
					<td align="right">关键词:</td>
					<td>
						<input type="code" class="len250" name="WEB_KEYWORDS" value='<?php echo (C("WEB_KEYWORDS")); ?>'/> 
					</td>
				</tr>
				<tr>
					<td align="right">描述:</td>
					<td>
						<textarea rows="6" cols="100" name="WEB_DESRIPTION" style="height:40px;"><?php echo (C("WEB_DESRIPTION")); ?></textarea>
					</td>
				</tr>
				<tr>
					<td align="right">网站导航:</td>
					<td>
						<textarea rows="6" cols="100" name="WEB_NAV" style="height:40px;"><?php echo (C("WEB_NAV")); ?></textarea>
					</td>
				</tr>
				<tr>
					<td align="right">服务热线:</td>
					<td>
						<input type="code" class="len250" name="WEB_SERVICE_TEL" value='<?php echo (C("WEB_SERVICE_TEL")); ?>'/>
					</td>
				</tr>
				<tr>
					<td align="right">底部联系方式:</td>
					<td>
						<textarea rows="6" cols="100" name="WEB_FOOT_CONTACT" style="height:40px;"><?php echo (C("WEB_FOOT_CONTACT")); ?></textarea>
					</td>
				</tr>
				<tr>
					<td align="right">网站版权:</td>
					<td>
						<textarea rows="6" cols="100" name="WEB_COPYRIGHT" style="height:120px;"><?php echo (C("WEB_COPYRIGHT")); ?></textarea>
					</td>
				</tr>
				<tr>
					<td align="right">网站Tag:</td>
					<td>
						<textarea rows="6" cols="100" name="WEB_TAG" style="height:120px;"><?php echo (C("WEB_TAG")); ?></textarea>
					</td>
				</tr>				
				<tr>
					<td colspan="2" align="center"><input type="submit" value="修改" class="submit"/></td>
				</tr>
			</table>
			</form>
	</div>
	</body>
</html>