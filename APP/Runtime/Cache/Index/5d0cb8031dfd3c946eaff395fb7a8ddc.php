<?php if (!defined('THINK_PATH')) exit();?><ul class="pageTab">
	<li id="tab1" onclick="setTab('tab',1,3)" class="hover">最热</li>
	<li id="tab2" onclick="setTab('tab',2,3)" class="">最新</li>
	<li id="tab3" onclick="setTab('tab',3,3)" class="">TOP</li>
</ul>
<div id="con_tab_1" class="hover" style="display: block;">
    <ul class="pageTabBox">
		<?php if(is_array($articlenew)): foreach($articlenew as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
    </ul>
</div>
<div id="con_tab_2" style="display: none;">
    <ul class="pageTabBox">
		<?php if(is_array($articleclick)): foreach($articleclick as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
    </ul>
</div>
<div id="con_tab_3" style="display: none;">
    <ul class="pageTabBox">
		<?php if(is_array($articleding)): foreach($articleding as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
    </ul>
</div>