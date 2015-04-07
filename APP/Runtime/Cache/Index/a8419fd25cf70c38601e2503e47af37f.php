<?php if (!defined('THINK_PATH')) exit();?><ul class="pageTab">
    <li id="tabbb1" onclick="setTab('tabbb',1,3)" class="hover">推荐动态图</li>
    <li id="tabbb2" onclick="setTab('tabbb',2,3)" class="">推荐视频</li>
</ul>
<div id="con_tabbb_1" class="hover" style="display: block;">
    <ul class="pageTabBoxPic">
		<?php if(is_array($articlegif)): foreach($articlegif as $key=>$v): ?><li>
				<a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>"><h4><?php echo ($v["title"]); ?></h4><img src="/Uploads/<?php echo ($v['thumbnail']); ?>" class="img"/></a>
			</li><?php endforeach; endif; ?>
    </ul>
</div>
<div id="con_tabbb_2" style="display: none;">
    <ul class="pageTabBoxPic">
		<?php if(is_array($articlevideo)): foreach($articlevideo as $key=>$v): ?><li>
				<a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>"><img src="/Uploads/<?php echo ($v['thumbnail']); ?>" class="img"/><h4>+ <?php echo ($v["title"]); ?></h4></a>
			</li><?php endforeach; endif; ?>
    </ul>
</div>