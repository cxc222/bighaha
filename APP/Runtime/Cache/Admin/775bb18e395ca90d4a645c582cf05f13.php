<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="stylesheet" href="__PUBLIC__/Css/public.css" />
		<script type="text/javascript">
			window.UEDITOR_HOME_URL = '__ROOT__/Data/Ueditor2/';
			window.onload = function(){
				window.UEDITOR_CONFIG.initialFrameHeight = 300;
				window.UEDITOR_CONFIG.initialFrameWidth = 1000;
				window.UEDITOR_CONFIG.imageUrl = "<?php echo U(GROUP_NAME.'/Article/upload');?>";           //图片上传提交地址
    			window.UEDITOR_CONFIG.imagePath = '__ROOT__/Uploads/';   
				UE.getEditor('content');
			}
		</script>
		<script type="text/javascript" src="__ROOT__/Data/Ueditor2/ueditor.config.js"></script>
		<script type="text/javascript" src="__ROOT__/Data/Ueditor2/ueditor.all.min.js"></script>
	</head>
	<body>
		<div class="navlist">
			<a href="<?php echo U(GROUP_NAME.'/Article/index');?>">文章列表</a>
			<a href="<?php echo U(GROUP_NAME.'/Article/add');?>"  class="cur">增加文章</a>
			<a href="<?php echo U(GROUP_NAME.'/Article/trach');?>">文章回收站</a>
		</div>
		<form method="post" action="<?php echo U(GROUP_NAME.'/Article/addRun');?>" enctype="multipart/form-data">
		<table class="table">
			<tr>
				<td align="right" width="10%">文章标题：</td>
				<td>
					<input type="text" name="title" class="inputlong">
					<select name="cid" class="select">
						<?php if(is_array($cate)): foreach($cate as $key=>$v): if($v["id"] != 1): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["html"]); echo ($v["name"]); ?></option><?php endif; endforeach; endif; ?>
					</select>
					<select name="username" class="select">
					<?php if(is_array($user)): foreach($user as $key=>$v): ?><option value="<?php echo ($v["username"]); ?>"><?php echo ($v["username"]); ?></option><?php endforeach; endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" width="10%">文章属性：</td>
				<td>
<!-- 					<?php if(is_array($attr)): foreach($attr as $key=>$v): ?><label style="margin-right:10px;">
							<input type="checkbox" name="aid[]" value="<?php echo ($v["id"]); ?>"/>&nbsp;<div class="tip"><?php echo ($v["name"]); ?></div>
						</label><?php endforeach; endif; ?> -->
					<input type="checkbox" name="del" value="1"/>&nbsp;<div class="tip">放入回收站</div>
				</td>
			</tr>
			<tr>
				<td align="right" width="10%">缩略图：</td>
				<td>
					<input type="file" name="thumbnail"/>
				</td>
			</tr>
			<tr>
				<td align="right" width="10%">文章摘要：</td>
				<td><textarea name="summary" class="textarea"></textarea></td>
			</tr>
			<tr>
				<td align="right" width="10%">关键字：</td>
				<td><input type="text" name="keyword" class="inputlong"/><input type="text" name="click" value="<?php echo ($click); ?>"/></td>
			</tr>
			<tr>
				<td align="right" width="10%">Tag:</td>
				<td><input type="text" name="tag" class="inputlong"/><div class="tip2">用英文状态下的,分隔</div></td>
			</tr>
			<tr>
				<td align="right" width="10%"></td>
				<td>
					<textarea name="content" id="content" cols="80" rows="6"></textarea>
				</td>
			</tr>
			<tr>
				<td width="10%"></td>
				<td>
					<input type="submit" value="保存添加" class="submit"/>
				</td>
			</tr>			
		</table>
	</form>
	</body>
</html>