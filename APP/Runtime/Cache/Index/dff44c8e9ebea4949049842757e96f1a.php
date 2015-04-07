<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title><?php echo (C("WEB_TITLE")); ?> - <?php echo (C("WEB_NAME")); ?></title>
<meta name="description" content="<?php echo (C("WEB_DESRIPTION")); ?>" />
<meta name="keywords" content="<?php echo (C("WEB_KEYWORDS")); ?>" />
<link rel="stylesheet" href="__PUBLIC__/Css/index.css" />
<link rel="stylesheet" href="__PUBLIC__/Css/common.css" />
<link rel="stylesheet" href="__PUBLIC__/Css/font-awesome.min.css" />
<link rel="stylesheet" href="__PUBLIC__/FontAwesome/css/font-awesome.min.css" />
<script type="text/JavaScript" src='__PUBLIC__/Js/jquery.js'></script>
<script type="text/JavaScript" src='__PUBLIC__/Js/bckToTop.js'></script>
<script type="text/JavaScript" src='__PUBLIC__/Js/qq_focus.js'></script>
<script type="text/JavaScript" src='__PUBLIC__/Js/tab.js'></script>
</head>
<body>
<!--导航 -->
<div class='top-list-wrap'>
	<div class='top-list'>
		<h1><a href="/" class='logo' title="<?php echo (C("WEB_NAME")); ?>-<?php echo (C("WEB_TITLE")); ?>"><img src="__PUBLIC__/Images/logo.png"/></a></h1>
		<ul class="l-nav">
			<li><a href="/" title="<?php echo (C("WEB_NAME")); ?>" <?php if($cateinfo["id"] == ''): ?>class="cur"<?php endif; ?>>首 页</a></li>
			<li><a href="/gif" title="搞笑gif" <?php if($cateinfo["id"] == 1): ?>class="cur"<?php endif; ?>>搞笑gif</a></li>
			<li><a href="/pic" title="搞笑图片" <?php if($cateinfo["id"] == 2): ?>class="cur"<?php endif; ?>>搞笑图片</a></li>
			<li><a href="/news" title="搞笑网文" <?php if($cateinfo["id"] == 3): ?>class="cur"<?php endif; ?>>搞笑网文</a></li>
			<li><a href="/video" title="搞笑视频" <?php if($cateinfo["id"] == 4): ?>class="cur"<?php endif; ?>>搞笑视频</a></li>
		</ul>  
	    <div class="search-box">
	        <form method="get" action="<?php echo U(GROUP_NAME.'/Search/index');?>">
	            <input type="text" class="input" name="keyword" value="撸管动态图..."/>
	            <input type="submit" class="submit"/>
	        </form>
	    </div>                                                      
	</div>    
</div>
<div class="indexFocusWrap">
  <div class="indexFocus">
    <div id="focus">
        <ul>
          <?php if(is_array($focus)): foreach($focus as $key=>$v): ?><li>
              <a href="/<?php echo ($v["id"]); ?>.html" title="<?php echo ($v["title"]); ?>" target="_blank" class="img"><img src="/Uploads/<?php echo ($v['thumbnail']); ?>" alt="" /></a>
              <div class="textinfo">
                <a href="/<?php echo ($v["id"]); ?>.html" title="<?php echo ($v["title"]); ?>"><h2><?php echo ($v["title"]); ?></h2></a>
                <span>已经有<?php echo ($v["click"]); ?>人看过~~</span>
                <p><?php echo ($v["summary"]); ?>...</p>
                <a href="/<?php echo ($v["id"]); ?>.html" title="<?php echo ($v["title"]); ?>" class="a">+点击查看+</a>
              </div>
            </li><?php endforeach; endif; ?>
        </ul>
    </div> 
    <div class="tag">
      <h2>热门标签：</h2>
      <div class="taglist">
        <?php echo (C("WEB_TAG")); ?>
      </div>
      <div class="share">
        <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧"></a><a href="#" class="bds_douban" data-cmd="douban" title="分享到豆瓣网"></a></div>
        <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"32"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin","tieba","douban"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin","tieba","douban"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
      </div>
      <h3>搞笑死了：www.gaoxiaosile.com</h3>
    </div>   
  </div>
</div>

<!-- <div class="indexAdWrap">
  <div class="indexAd">
    <div class="indexAdbox">
      <div class="indexAdLeft">
        <script type="text/javascript">
        var cpro_id = "u1621220";
        </script>
        <script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
      </div>
      <div class="indexAdRight">
        <a href="/pic"><img src="/Public/Images/jiong.gif"/></a>
      </div>
    </div>
  </div>
</div> -->

<div class="indexWrap">
    <div class="index">
      <div class="indexLeft">
        <div class="indexLeftTitle"><a href="/pic" title="搞笑图片">搞笑图片</a></div>
          <ul class="indexCatalogUl">
            <?php if(is_array($pic)): foreach($pic as $key=>$v): ?><li>
              <a href="/<?php echo ($v["id"]); ?>.html" title="<?php echo ($v["title"]); ?>">
              <div class="hover"><img src="/Public/Images/look.png"/></div>
              <div class="text"><?php echo ($v["title"]); ?></div>
              <img src="/Uploads/<?php echo ($v['thumbnail']); ?>"  class="img"/>
              </a>
            </li><?php endforeach; endif; ?>                        
          </ul>
      </div>
      <div class="indexRight">
        <div class="CatelogTab">
          <ul class="pageTab">
            <li id="tabpic1" onclick="setTab('tabpic',1,3)" class="hover">最热排行</li>
            <li id="tabpic2" onclick="setTab('tabpic',2,3)" class="">顶TOP</li>
            <li id="tabpic3" onclick="setTab('tabpic',3,3)" class="">踩TOP</li>
          </ul>
          <div id="con_tabpic_1" class="hover" style="display: block;">
              <ul class="pageTabBox">
              <?php if(is_array($picclick)): foreach($picclick as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div>
          <div id="con_tabpic_2" style="display: none;">
              <ul class="pageTabBox">
              <?php if(is_array($picding)): foreach($picding as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div>
          <div id="con_tabpic_3" style="display: none;">
              <ul class="pageTabBox">
              <?php if(is_array($piccai)): foreach($piccai as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div> 
      </div>
      </div>
    </div>
</div>


<div class="indexWrap">
    <div class="index">
      <div class="indexLeft">
        <div class="indexLeftTitle"><a href="/gif" title="搞笑GIF">搞笑GIF</a></div>
          <ul class="indexCatalogUl">
            <?php if(is_array($gif)): foreach($gif as $key=>$v): ?><li>
              <a href="/<?php echo ($v["id"]); ?>.html" title="<?php echo ($v["title"]); ?>">
              <div class="hover"><img src="/Public/Images/look.png"/></div>
              <div class="text"><?php echo ($v["title"]); ?></div>
              <img src="/Uploads/<?php echo ($v['thumbnail']); ?>"  class="img"/>
              </a>
            </li><?php endforeach; endif; ?>                        
          </ul>
      </div>
      <div class="indexRight">
        <div class="CatelogTab">
          <ul class="pageTab">
            <li id="tabgif1" onclick="setTab('tabgif',1,3)" class="hover">最热排行</li>
            <li id="tabgif2" onclick="setTab('tabgif',2,3)" class="">顶TOP</li>
            <li id="tabgif3" onclick="setTab('tabgif',3,3)" class="">踩TOP</li>
          </ul>
          <div id="con_tabgif_1" class="hover" style="display: block;">
              <ul class="pageTabBox">
              <?php if(is_array($gifclick)): foreach($gifclick as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div>
          <div id="con_tabgif_2" style="display: none;">
              <ul class="pageTabBox">
              <?php if(is_array($gifding)): foreach($gifding as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div>
          <div id="con_tabgif_3" style="display: none;">
              <ul class="pageTabBox">
              <?php if(is_array($gifcai)): foreach($gifcai as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div> 
      </div>
      </div>
    </div>
</div>

<div class="indexWrap">
    <div class="index">
      <div class="indexLeft">
        <div class="indexLeftTitle"><a href="/video" title="搞笑视频">搞笑视频</a></div>
          <ul class="indexCatalogUl">
            <?php if(is_array($video)): foreach($video as $key=>$v): ?><li>
              <a href="/<?php echo ($v["id"]); ?>.html" title="<?php echo ($v["title"]); ?>">
              <div class="hover"><img src="/Public/Images/look.png"/></div>
              <div class="text"><?php echo ($v["title"]); ?></div>
              <img src="/Uploads/<?php echo ($v['thumbnail']); ?>"  class="img"/>
              </a>
            </li><?php endforeach; endif; ?>                        
          </ul>
      </div>
      <div class="indexRight">
        <div class="CatelogTab">
          <ul class="pageTab">
            <li id="tabvideo1" onclick="setTab('tabvideo',1,3)" class="hover">最热排行</li>
            <li id="tabvideo2" onclick="setTab('tabvideo',2,3)" class="">顶TOP</li>
            <li id="tabvideo3" onclick="setTab('tabvideo',3,3)" class="">踩TOP</li>
          </ul>
          <div id="con_tabvideo_1" class="hover" style="display: block;">
              <ul class="pageTabBox">
              <?php if(is_array($videoclick)): foreach($videoclick as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div>
          <div id="con_tabvideo_2" style="display: none;">
              <ul class="pageTabBox">
              <?php if(is_array($videoding)): foreach($videoding as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>" title="<?php echo ($v["title"]); ?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div>
          <div id="con_tabvideo_3" style="display: none;">
              <ul class="pageTabBox">
              <?php if(is_array($videocai)): foreach($videocai as $key=>$v): ?><li><a href="<?php echo U('/'.$v['id']);?>">+&nbsp;&nbsp;&nbsp;<?php echo ($v["title"]); ?></a></li><?php endforeach; endif; ?>
              </ul>
          </div> 
      </div>
      </div>
    </div>
</div>

<div class="indexWrap">
    <div class="index">
        <div class="indexCatalog">
          <div class="rankbox">
                <div class="hotTab">
                    <?php echo W('Hot',array('limit'=>5));?>
                </div>
                <div class="CateTab">
                    <?php echo W('Cate',array('limit'=>5));?>
                </div>
                <div class="ad300">
                  <script type="text/javascript">
                  /*300*250 全图*/
                  var cpro_id = "u1095741";
                  </script>
                  <script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
                </div>
          </div>
        </div>
    </div>
</div>
<!--底部-->
<div class="footerWrap">
	<div class="footer">
		<p><strong>友情链接：</strong>
			<?php echo W('Friendlink');?>
		</p>
		<?php echo (C("WEB_COPYRIGHT")); ?>
		<div class="copyright">
			<span>Copyright © 2014 - 2015 bigha.cn 大笑 版权所有 广告联系：yhec@foxmail.com 浙ICP备12039933号-1 技术支持：<a href="http://www.yhec.cn" target="_blank" title="月河城">月河城科技</a> - 专注网站建设</span>
			<div class="cnzz">
				<div class="bdtj">
					<script type="text/javascript">
					var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
					document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F26ab253a3a86d8557137a2269bdf931b' type='text/javascript'%3E%3C/script%3E"));
					</script>
				</div>
				<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_5914494'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/stat.php%3Fid%3D5914494%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>

			</div>
		</div>
	</div>
</div>
<script>TMall.BackTop.init();</script>
<!-- <script src="http://f.ku63.com/f.asp?u=47780&m=0&n=" charset="gb2312"></script> -->
</body>
</html>