<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
	</head>
	<body><?php echo ($id); ?>
		<div class="navlist">
			<a href="<?php echo U(GROUP_NAME.'/User/index');?>">管理员列表</a>
			<a href="<?php echo U(GROUP_NAME.'/User/add');?>">管理员增加</a>
			<a href="javascript:;" class="cur">管理员修改</a>
		</div>
		<form method="post" action="<?php echo U(GROUP_NAME.'/User/updateRun');?>" enctype="multipart/form-data">
		<table class="table">
			<tr>
				<td colspan="2">管理员修改</td>
			</tr>
			<tr>
				<td align="right">名称：</td>
				<td><input type="text" name="username" value="<?php echo ($user["username"]); ?>" class="len250"></td>
			</tr>
			<tr>
				<td align="right">新密码：</td>
				<td><input type="password" name="password" value="" class="len250"></td>
			</tr>
			<tr>
				<td align="right">重复新密码：</td>
				<td><input type="password" name="notpassword" value="" class="len250"></td>
			</tr>
			<tr>
				<td align="right">头像：</td>
				<td>
					<input type="file" name="avatar" value="">
					<?php if($user["avatar"] == ''): else: ?>
						<img style="width:37px;height:37px;" src="/Uploads/avatar/<?php echo ($user["avatar"]); ?>"/><?php endif; ?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="hidden" value="<?php echo ($user["id"]); ?>" name="id"/>
					<input type="submit" value="保存添加" class="submit"/>
				</td>
			</tr>			
		</table>
	</form>
	</body>
</html>