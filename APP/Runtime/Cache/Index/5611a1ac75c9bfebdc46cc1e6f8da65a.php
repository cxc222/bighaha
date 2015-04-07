<?php if (!defined('THINK_PATH')) exit();?><ul class="pageTabArticle">
    <li id="tabbbb1" onclick="setTab('tabbbb',1,3)" class="hover">其它推荐</li>
</ul>
<div id="con_tabbbb_1" class="hover" style="display: block;">
    <ul class="pageArticle">
		<?php if(is_array($articlegif)): foreach($articlegif as $key=>$v): ?><li>
				<a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>"><h4><?php echo ($v["title"]); ?></h4><img src="/Uploads/<?php echo ($v['thumbnail']); ?>" class="img"/></a>
			</li><?php endforeach; endif; ?>
    </ul>
</div>