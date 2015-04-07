<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
	</head>
	<body>
		<div class="navlist">
			<a href="<?php echo U(GROUP_NAME.'/Article/index');?>"   class="cur">文章列表</a>
			<a href="<?php echo U(GROUP_NAME.'/Article/add');?>">增加文章</a>
			<a href="<?php echo U(GROUP_NAME.'/Article/trach');?>">文章回收站</a>
		</div>
		<table class="table">
			<tr>
				<td width="8%">ID</td>
				<td>标题</td>
				<td width="8%">缩略图</td>
				<td width="10%">所属分类</td>
				<td width="15%">发布人</td>
				<td width="10%">点击次数</td>
				<td width="15%">发布时间</td>
				<td width="10%">操作</td>
			</tr>
			<form method="post" action="<?php echo U(GROUP_NAME.'/Article/deleteAll');?>">
			<?php if(is_array($article)): foreach($article as $key=>$v): ?><tr>
					<td><?php echo ($v["id"]); ?><input type="hidden" name="id[]" value="<?php echo ($v["id"]); ?>"></td>
					<td>
						<a href="<?php echo U('/'.$v['id'].'.html');?>" target="_blank"><?php echo ($v["title"]); ?></a>
						<?php if($v["cid"] == 1): ?><a href="<?php echo U(GROUP_NAME.'/Article/updategif',array('id'=>$v['id']));?>" style="color:grey;">修</a>
						<?php else: ?>
							<a href="<?php echo U(GROUP_NAME.'/Article/update',array('id'=>$v['id']));?>" style="color:grey;">修</a><?php endif; ?>
						<!--<?php if(is_array($v["attr"])): foreach($v["attr"] as $key=>$value): ?><strong style="color:<?php echo ($value["color"]); ?>">[<?php echo ($value["name"]); ?>]</strong><?php endforeach; endif; ?> -->
					</td>
					<td><img src="/Uploads/<?php echo (($v["thumbnail"])?($v["thumbnail"]):'default.gif'); ?>" style="width:80px;height:60px;"></td>
					<td><a href="<?php echo U(GROUP_NAME.'/Article/lists',array('id'=>$v['cid']));?>"><?php echo ($v["cate"]); ?></a></td>
					<td><?php echo ($v["username"]); ?></td>
					<td><?php echo ($v["click"]); ?></td>
					<td><?php echo (date('Y-m-d H:i:s',$v["time"])); ?></td>
					<td>
						<?php if(ACTION_NAME == "index"): ?><a href="<?php echo U(GROUP_NAME.'/Article/toTrach',array('id'=>$v['id'],'type'=>1));?>">删除</a>&nbsp;&nbsp;
							<?php if($v["cid"] == 1): ?><a href="<?php echo U(GROUP_NAME.'/Article/updategif',array('id'=>$v['id']));?>">修改</a>
							<?php else: ?>
								<a href="<?php echo U(GROUP_NAME.'/Article/update',array('id'=>$v['id']));?>">修改</a><?php endif; ?>
						<?php else: ?>	
							<a href="<?php echo U(GROUP_NAME.'/Article/toTrach',array('id'=>$v['id'],'type'=>0));?>">还原</a>&nbsp;&nbsp;
							<a href="javascript:if(confirm('确实要删除吗?')) location='<?php echo U(GROUP_NAME.'/Blog/delete',array('id'=>$v['id']));?>'">彻底删除</a><?php endif; ?>
					</td>
				</tr><?php endforeach; endif; ?>
			<?php if(ACTION_NAME == "trach"): ?><tr>
				<td  colspan="8"  align="center">
					<input type="submit" value="删除全部"/>
				</td>
			</tr><?php endif; ?>
			</form>
			<tr>
				<td colspan="8"><div class="pagesize"><?php echo ($page); ?></div></td>
			</tr>
		</table>
	</body>
</html>