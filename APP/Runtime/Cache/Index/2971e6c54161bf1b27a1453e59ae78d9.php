<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title><?php echo ($article["title"]); ?>-<?php echo ($cateinfo["name"]); ?>-<?php echo (C("WEB_NAME")); ?></title>
<meta name="description" content="<?php if(empty($article['summary'])): echo (C("WEB_DESRIPTION")); else: echo ($article['summary']); endif; ?>" />
<meta name="keywords" content="<?php if(empty($article['keyword'])): echo (C("WEB_KEYWORDS")); else: echo ($article['keyword']); endif; ?>" />
<link rel="stylesheet" href="__PUBLIC__/Css/common.css" />
<link rel="stylesheet" href="__PUBLIC__/Css/font-awesome.min.css" />
<link rel="stylesheet" href="__PUBLIC__/FontAwesome/css/font-awesome.min.css" />
<script type="text/JavaScript" src='__PUBLIC__/Js/jquery.js'></script>
<script type="text/JavaScript" src='__PUBLIC__/Js/jquery.lazyload.js'></script>
<script type="text/JavaScript" src='__PUBLIC__/Js/bckToTop.js'></script>
<link rel="stylesheet" href="__ROOT__/Data/Ueditor2/third-party/SyntaxHighlighter/shCoreDefault.css" />
<script type="text/javascript" src="/Data/Ueditor2/third-party/SyntaxHighlighter/shCore.js"></script>
<script src="__PUBLIC__/Js/digg.js" type="text/javascript"></script>
<script type="text/javascript">
    SyntaxHighlighter.all();
    var myXmlHttpRequest; //ueditor 高亮
    var aid = <?php echo ($article["id"]); ?>;//获取文档id
    var type = "Article";//获取文档id
</script>
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
                    <div class="gifContent">
                        <div class="gifListBox">
                             <div class="info">
                                <h1><?php echo ($article["title"]); ?></h1>
                                <div class="infocatalog">
                                    Author:<?php echo ($article["username"]); ?>&nbsp;&nbsp;&nbsp;<?php echo (date("Y-m-d H:i:s",$article["time"])); ?>&nbsp;&nbsp;&nbsp;
                                    <a href="/<?php echo ($cateinfo["filename"]); ?>"><?php echo ($cateinfo["name"]); ?></a>
                                </div>
                                <div class="ad755">
                                    <div class="ad336">
                                        <script type="text/javascript">
                                        /*336*280，创建于2014-7-15 waamei page*/
                                        var cpro_id = "u1621412";
                                        </script>
                                        <script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
                                    </div>
                                    <div class="ad336" style="margin:0 0 0 20px;border-left:1px dotted #ddd;padding:0 0 0 20px;">
                                        <script type="text/javascript">
                                        /*336*280，创建于2014-7-15 waamei page*/
                                        var cpro_id = "u1621412";
                                        </script>
                                        <script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>
                                    </div>
                                </div>
                                <div class="text">
                                    <?php if(($cateinfo["id"] == 1) OR ($cateinfo["id"] == 2)): ?><center><p><img src="/Uploads/<?php echo ($article["thumbnail"]); ?>" title="<?php echo ($article["title"]); ?>"/></p></center>
                                        <center><p><?php echo ($article["summary"]); ?></p></center>
                                        <?php if($article["content"] != ''): echo ($article["content"]); endif; endif; ?>

                                    <?php if($cateinfo["id"] == 3): ?><center><p><img src="/Uploads/<?php echo ($article["thumbnail"]); ?>" title="<?php echo ($article["title"]); ?>"/></p></center>
                                        <p><?php echo ($article["content"]); ?></p><?php endif; ?>
                                    <?php if($cateinfo["id"] == 4): ?><center><?php echo ($article["content"]); ?></center>
                                        <p><?php echo ($article["summary"]); ?></p><?php endif; ?>
                                </div>
                                <div class="tagpage">
                                    <a href="javascript:;">Tag</a>
                                    <?php if(is_array($tag)): foreach($tag as $key=>$v): ?><a href="/Index/Tag/index.html?tagname=<?php echo ($v["tagname"]); ?>"><?php echo ($v["tagname"]); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; ?>
                                </div>
                            </div>
                            <div class="diggbox">
                                <?php if(empty($front)): ?><a href="javascript:alert('没有了噢');" title="没有了" class="prebutton icon-chevron-left  icon-4x "></a>
                                <?php else: ?>
                                    <a href="/<?php echo ($front["id"]); ?>.html" title="上一页：<?php echo ($front["title"]); ?>"  class="prebutton icon-chevron-left icon-4x"></a><?php endif; ?>
                                <a href="javascript:;" onclick="ding();"  class="diggboxBox">
                                    <strong class=" icon-thumbs-up icon-3x "></strong>
                                    <b class=""  id="ding" >
                                        <script type="text/javascript" src="<?php echo U(GROUP_NAME.'/Article/dingNum',array('id'=>$article['id']));?>"></script>
                                    </b>
                                </a>
                                <a href="javascript:;" onclick="cai();"  class="diggboxBox">
                                    <strong class=" icon-thumbs-down icon-3x "></strong>
                                    <b class=""  id="cai" >
                                        <script type="text/javascript" src="<?php echo U(GROUP_NAME.'/Article/caiNum',array('id'=>$article['id']));?>"></script>
                                    </b>
                                </a>
                                <a href="javascript:;"  class="diggboxBox">
                                    <strong class=" icon-eye-open icon-3x "></strong>
                                    <b class="">
                                        <script type="text/javascript" src="<?php echo U(GROUP_NAME.'/Article/clickNum',array('id'=>$article['id']));?>"></script>
                                    </b>
                                </a>
                                <div class="sharebutton">
                                    <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a></div>
                                    <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"2","bdSize":"32"},"share":{},"image":{"viewList":[],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":[]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
                                </div>
                                <?php if(empty($next)): ?><a href="javascript:alert('没有了噢');" class="nextbutton icon-chevron-right icon-4x"></a>
                                <?php else: ?>
                                    <a href="/<?php echo ($next["id"]); ?>.html" class="nextbutton icon-chevron-right icon-4x" title="下一页：<?php echo ($next["title"]); ?>"></a><?php endif; ?>
                            </div>
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





                            <div >
                            <?php echo W('CommandArticle',array('limit'=>8,'cid'=>$cateinfo['id']));?>
                            </div>
                            <div style="float:left;width:755px;margin-top:20px;overflow:hidden;">
	<script src="http://e.70e.com/cpc_img.asp?u=47780&m=4&n=&s_px=1" charset="gb2312"></script>
</div>




                            <h2 style="margin:20px 0 5px 0 ;">闲言粹语...</h2>
                            <div>
                                <!-- UY BEGIN -->
                                <div id="uyan_frame"></div>
                                <script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid=890350"></script>
                                <!-- UY END -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rightBar mT15">
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