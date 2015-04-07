<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
</head>
<body>
<div class="navlist">
	<a href="<?php echo U(GROUP_NAME.'/User/index');?>" class="cur">管理员列表</a>
	<a href="<?php echo U(GROUP_NAME.'/User/add');?>">管理员增加</a>
</div>
<table class="table">
	<tr>
		<td>ID</td>
		<td>名称</td>
		<td>头像</td>
		<td>操作</td>
	</tr>
	<?php if(is_array($user)): foreach($user as $key=>$v): ?><tr>
		<td><?php echo ($v["id"]); ?></td>
		<td><?php echo ($v["username"]); ?></td>
		<td><img style="width:60px;height:60px;" src="/Uploads/avatar/<?php echo (($v["avatar"])?($v["avatar"]):'defaultpic.gif'); ?>"/></td>
		<td>
			<a href="<?php echo U(GROUP_NAME.'/User/update',array('id'=>$v['id']));?>">修改</a>&nbsp;&nbsp;
			<a href="javascript:if(confirm('确实要删除吗?'))location='<?php echo U(GROUP_NAME.'/User/delete',array('id'=>$v['id']));?>'">删除</a>
		</td>
	</tr><?php endforeach; endif; ?>
</table>
</body>
</html>