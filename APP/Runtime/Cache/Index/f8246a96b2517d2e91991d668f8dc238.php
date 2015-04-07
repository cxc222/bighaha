<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title><?php echo ($cateinfo['catetitle']); ?>-<?php echo ($cateinfo['name']); ?>-<?php echo (C("WEB_NAME")); ?></title>
<meta name="description" content="<?php if(empty($cateinfo['description'])): echo (C("WEB_DESRIPTION")); else: echo ($cateinfo['description']); endif; ?>" />
<meta name="keywords" content="<?php if(empty($cateinfo['keywords'])): echo (C("WEB_KEYWORDS")); else: echo ($cateinfo['keywords']); endif; ?>" />
<link rel="stylesheet" href="__PUBLIC__/Css/common.css" />
<link rel="stylesheet" href="__PUBLIC__/Css/font-awesome.min.css" />
<link rel="stylesheet" href="__PUBLIC__/FontAwesome/css/font-awesome.min.css" />
<script type="text/JavaScript" src='__PUBLIC__/Js/jquery.js'></script>
<script type="text/JavaScript" src='__PUBLIC__/Js/jquery.lazyload.js'></script>
<script type="text/JavaScript" src='__PUBLIC__/Js/bckToTop.js'></script>
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
<div class="mainWrap">
    <div class="main">
        <div class="leftBar mT15">
            <div class="box">
                <div class="content">
                  <h2>
                    <a href="/">首页 </a>>
                    <?php $last = count($parent)-1; ?>
                    <?php if(is_array($parent)): foreach($parent as $key=>$v): ?><a href="<?php echo U('/'.$v['filename']);?>"> <?php echo ($v["name"]); ?> </a><?php if($key != $last): ?>><?php endif; endforeach; endif; ?>
                  </h2>
                    <?php if(!empty($sister)): ?><div class="sonnav">
                        <?php if(is_array($sister)): foreach($sister as $key=>$v): if($v['id'] == $nowid): ?><a href="<?php echo U('/'.$v['filename']);?>" class="cur"><?php echo ($v["name"]); ?></a>
                            <?php else: ?>
                                <a href="<?php echo U('/'.$v['filename']);?>"><?php echo ($v["name"]); ?></a><?php endif; endforeach; endif; ?>
                    
                    </div><?php endif; ?>
                    <div style="float:left;width:755px;overflow:hidden;">
	<!--<script type="text/JavaScript" charset="gb2312">
	s_noadid="";
	 s_width=760;
	 s_height=130;
	 s_id=47780;
	 s_px=1;
	</script>
	<script src="http://e.70e.com/js/2013_new.js" type=text/javascript charset="gb2312"></script>-->
	<script type="text/javascript">
	/*自定义标签云，创建于2014-7-15 760130*/
	var cpro_id = "u1621431";
	</script>
	<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
</div>




                    <ul class="pageul">
                      <?php if(is_array($article)): foreach($article as $key=>$v): ?><li>
                        <a href="/<?php echo ($v["id"]); ?>.html"class="hover" title="<?php echo ($v["title"]); ?>"><img src="/Public/Images/look.png"/></a>
                        <a href="/<?php echo ($v["id"]); ?>.html"class="text" title="<?php echo ($v["title"]); ?>"><?php echo ($v["title"]); ?></a>
                        <a href="/<?php echo ($v["id"]); ?>.html"class="img" title="<?php echo ($v["title"]); ?>"><img src="/Uploads/<?php echo (($v["thumbnail"])?($v["thumbnail"]):'default.gif'); ?>"/></a>
                      </li><?php endforeach; endif; ?>
                    </ul>
                    <div class="pagesize"><?php echo ($page); ?></div>
                    <div style="float:left;width:755px;overflow:hidden;">
	<!--<script type="text/JavaScript" charset="gb2312">
	s_noadid="";
	 s_width=760;
	 s_height=130;
	 s_id=47780;
	 s_px=1;
	</script>
	<script src="http://e.70e.com/js/2013_new.js" type=text/javascript charset="gb2312"></script>-->
	<script type="text/javascript">
	/*自定义标签云，创建于2014-7-15 760130*/
	var cpro_id = "u1621431";
	</script>
	<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
</div>




                    <?php echo W('CommandArticle',array('limit'=>12,'cid'=>$cateinfo['id']));?>
                    <div style="float:left;width:755px;margin-top:20px;overflow:hidden;">
	<script src="http://e.70e.com/cpc_img.asp?u=47780&m=4&n=&s_px=1" charset="gb2312"></script>
</div>




                </div>
            </div>
        </div>
        <div class="rightBar mT15">
            <script type="text/JavaScript" src='__PUBLIC__/Js/tab.js'></script>
            <div class="pageTabWrap mB20" style="background: #fff url(/Public/Images/share.gif) no-repeat 29px 15px;padding: 53px 20px 15px;">
              <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_bdysc" data-cmd="bdysc" title="分享到百度云收藏"></a><a href="#" class="bds_kaixin001" data-cmd="kaixin001" title="分享到开心网"></a><a href="#" class="bds_tqf" data-cmd="tqf" title="分享到腾讯朋友"></a><a href="#" class="bds_douban" data-cmd="douban" title="分享到豆瓣网"></a><a href="#" class="bds_diandian" data-cmd="diandian" title="分享到点点网"></a><a href="#" class="bds_mogujie" data-cmd="mogujie" title="分享到蘑菇街"></a><a href="#" class="bds_meilishuo" data-cmd="meilishuo" title="分享到美丽说"></a></div>
            <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"32"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin","bdysc","kaixin001","tqf","douban","diandian","mogujie","meilishuo"],"viewText":"分享到：","viewSize":"16"}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
            </div>
            <script>
  <!--
  /*第一种形式 第二种形式 更换显示样式*/
  function setTab(name,cursel,n){
      for(i=1;i<=n;i++){
          var menu=document.getElementById(name+i);
          var con=document.getElementById("con_"+name+"_"+i);
          menu.className=i==cursel?"hover":"";
          con.style.display=i==cursel?"block":"none";
      }
  }
  //-->
</script>
<div class="pageTabWrap mB20">
<script type="text/javascript">
/*300*250 全图*/
var cpro_id = "u1095741";
</script>
<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
</div>




<div class="pageTabWrap mB20">
  <script src="http://e.70e.com/cpc_img.asp?u=47780&m=6&n=&s_px=1" charset="gb2312"></script>
</div>




<div class="pageTabWrap mB20">
	<?php echo W('Hot',array('limit'=>5));?>
</div>

<div class="pageTabWrap mB20">
<?php echo W('CommandPic',array('limit'=>5));?>
</div>

<!-- <div class="pageTabWrap mB20">
<?php echo W('Cate',array('limit'=>5));?>
</div> -->

<div class="pageTabWrap mB20">
  <script type="text/JavaScript" charset="gb2312">
   s_noadid="";
   s_width=300;
   s_height=250;
   s_id=47780;
   s_px=1;
  </script>
  <script src="http://e.70e.com/js/2013_new.js" type=text/javascript charset="gb2312"></script>
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