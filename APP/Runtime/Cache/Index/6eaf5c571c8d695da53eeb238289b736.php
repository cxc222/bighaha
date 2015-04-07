<?php if (!defined('THINK_PATH')) exit();?><ul class="pageTab">
    <li id="tabb1" onclick="setTab('tabb',1,3)" class="hover">搞笑gif</li>
    <li id="tabb2" onclick="setTab('tabb',2,3)" class="">搞笑图片</li>
    <li id="tabb3" onclick="setTab('tabb',3,3)" class="">搞笑网文</li>
</ul>
<div id="con_tabb_1" class="hover" style="display: block;">
    <ul class="pageTabBox">
		<?php if(is_array($articlegif)): foreach($articlegif as $key=>$v): ?><li>
				<a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a>
			</li><?php endforeach; endif; ?>
    </ul>
</div>
<div id="con_tabb_2" style="display: none;">
    <ul class="pageTabBox">
		<?php if(is_array($articlepic)): foreach($articlepic as $key=>$v): ?><li>
				<a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a>
			</li><?php endforeach; endif; ?>
    </ul>
</div>
<div id="con_tabb_3" style="display: none;">
    <ul class="pageTabBox">
		<?php if(is_array($articlenews)): foreach($articlenews as $key=>$v): ?><li>
				<a href="<?php echo U('/'.$v['id']);?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a>
			</li><?php endforeach; endif; ?>
    </ul>
</div>