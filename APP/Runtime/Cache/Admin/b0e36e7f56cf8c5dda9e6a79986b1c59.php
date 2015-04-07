<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
	</head>
	<body>
		<div class="navlist">
			<a href="<?php echo U(GROUP_NAME.'/Category/index');?>" class="cur">栏目列表</a>
			<a href="<?php echo U(GROUP_NAME.'/Category/add');?>">增加栏目</a>
		</div>
		<form method="post" action="<?php echo U(GROUP_NAME.'/Category/sort');?>">
		<table class="table">
			<tr>
				<td>ID</td>
				<td>名称</td>
				<td>Filename</td>
				<td>级别</td>
				<td>排序</td>
				<td>操作</td>
			</tr>
			<?php if(is_array($cate)): foreach($cate as $key=>$v): ?><tr>
				<td><?php echo ($v["id"]); ?></td>
				<td><?php echo ($v["html"]); echo ($v["name"]); ?></td>
				<td><?php echo ($v["filename"]); ?></td>
				<td><?php echo ($v["level"]); ?></td>
				<td><input type="text" name="<?php echo ($v["id"]); ?>" value="<?php echo ($v["sort"]); ?>"></td>
				<td>
					<a href="<?php echo U(GROUP_NAME.'/Article/lists',array('id'=>$v['id']));?>">查看文档</a>&nbsp;&nbsp;
					<a href="<?php echo U(GROUP_NAME.'/Category/add',array('pid'=>$v['id']));?>">添加子分类</a>&nbsp;&nbsp;
					<a href="<?php echo U(GROUP_NAME.'/Category/update',array('id'=>$v['id']));?>">修改</a>&nbsp;&nbsp;
					<!-- <a href="<?php echo U(GROUP_NAME.'/Category/delete',array('id'=>$v['id']));?>">删除</a> -->
					<a href="javascript:if(confirm('确实要删除吗?'))location='<?php echo U(GROUP_NAME.'/Category/delete',array('id'=>$v['id']));?>'">删除</a>
				</td>
			</tr><?php endforeach; endif; ?>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" value="更新排序" class="submit"/>
				</td>
			</tr>			
		</table>
	</form>
	</body>
</html>