<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
	</head>
	<body>
		<div class="navlist">
			<a href="<?php echo U(GROUP_NAME.'/Friendlink/index');?>" class="cur">友情链接列表</a>
			<a href="<?php echo U(GROUP_NAME.'/Friendlink/add');?>">友情链接增加</a>
		</div>
		<form method="post" action="<?php echo U(GROUP_NAME.'/Friendlink/sort');?>">
		<table class="table">
			<tr>
				<td>ID</td>
				<td>名称</td>
				<td>logo</td>
				<td>排序</td>
				<td>状态</td>
				<td>操作</td>
			</tr>
			<?php if(is_array($friendlink)): foreach($friendlink as $key=>$v): ?><tr>
				<td><?php echo ($v["id"]); ?></td>
				<td><?php echo ($v["sitename"]); ?></td>
				<td><img style="width:60px;height:60px;"  src="/Uploads/friendlink/<?php echo (($v["logo"])?($v["logo"]):'defaultpic.gif'); ?>"/></td>
				<td><input type="text" name="<?php echo ($v["id"]); ?>" value="<?php echo ($v["sort"]); ?>"></td>
				<td>
					<?php if($v["statu"] == 0): ?><a style="color:grey;" href="<?php echo U(GROUP_NAME.'/Friendlink/handle',array('id'=>$v['id'],'type'=>'1'));?>">审核</a>
					<?php else: ?>
						<a style="color:green;" href="<?php echo U(GROUP_NAME.'/Friendlink/handle',array('id'=>$v['id'],'type'=>'0'));?>">取消</a><?php endif; ?>
				</td>
				<td>
					<a href="<?php echo U(GROUP_NAME.'/Friendlink/update',array('id'=>$v['id']));?>">修改</a>&nbsp;&nbsp;
					<a href="javascript:if(confirm('确实要删除吗?'))location='<?php echo U(GROUP_NAME.'/Friendlink/delete',array('id'=>$v['id']));?>'">删除</a>
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