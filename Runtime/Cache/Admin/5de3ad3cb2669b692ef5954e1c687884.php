<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|OneThink管理平台</title>
    <link href="/bighaha/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/bighaha/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/bighaha/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/bighaha/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/bighaha/Public/Admin/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/bighaha/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/bighaha/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/bighaha/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/bighaha/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
                <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                <?php if(!empty($_extra_menu)): ?>
                    <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                        <ul class="side-sub-menu">
                            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                    <a class="item" href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>您的位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            

            
	<div class="main-title cf">
		<h2><?php echo ($info['id']?'编辑':'新增'); ?> [<?php echo get_model_by_id($info['model_id']);?>] 属性 : <a href="<?php echo U('index','model_id='.$info['model_id']);?>">返回列表</a></h2>
	</div>

	<!-- 标签页导航 -->
	<div class="tab-wrap">
		<ul class="tab-nav nav">
			<li data-tab="tab1" class="current"><a href="javascript:void(0);">基 础</a></li>
			<li data-tab="tab2"><a href="javascript:void(0);">高 级</a></li>
		</ul>
		<div class="tab-content">
			<!-- 表单 -->
			<form id="form" action="<?php echo U('update');?>" method="post" class="form-horizontal doc-modal-form">
				<!-- 基础 -->
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item cf">
						<label class="item-label">字段名<span class="check-tips">（请输入字段名 英文字母开头，长度不超过30）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="name" value="<?php echo ($info["name"]); ?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段标题<span class="check-tips">（请输入字段标题，用于表单显示）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="title" value="<?php echo ($info["title"]); ?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段类型<span class="check-tips">（用于表单中的展示方式）</span></label>
						<div class="controls">
							<select name="type" id="data-type">
								<option value="">----请选择----</option>
								<?php $_result=get_attribute_type();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" rule="<?php echo ($type[1]); ?>"><?php echo ($type[0]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段定义<span class="check-tips">（字段属性的sql表示）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="field" value="<?php echo ($info["field"]); ?>" id="data-field">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">参数<span class="check-tips">（布尔、枚举、多选字段类型的定义数据）</span></label>
						<div class="controls">
							<label class="textarea input-large">
								<textarea name="extra"><?php echo ($info["extra"]); ?></textarea>
							</label>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">默认值<span class="check-tips">（字段的默认值）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="value" value="<?php echo ($info["value"]); ?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段备注<span class="check-tips">(用于表单中的提示)</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="remark" value="<?php echo ($info["remark"]); ?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">是否显示<span class="check-tips">（是否显示在表单中）</span></label>
						<div class="controls">
							<select name="is_show">
								<option value="1">始终显示</option>
								<option value="2">新增显示</option>
								<option value="3">编辑显示</option>
								<option value="0">不显示</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">是否必填<span class="check-tips">（用于自动验证）</span></label>
						<div class="controls">
							<select name="is_must">
								<option value="0">否</option>
								<option value="1">是</option>
							</select>
						</div>
					</div>
                    </div>
                <div id="tab2" class="tab-pane tab2">
					<div class="form-item cf">
						<label class="item-label">验证方式<span class="check-tips"></span></label>
						<div class="controls">
							<select name="validate_type">
								<option value="regex">正则验证</option>
								<option value="function">函数验证</option>
								<option value="unique">唯一验证</option>
								<option value="length">长度验证</option>
                                <option value="in">验证在范围内</option>
                                <option value="notin">验证不在范围内</option>
                                <option value="between">区间验证</option>
                                <option value="notbetween">不在区间验证</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">验证规则<span class="check-tips">（根据验证方式定义相关验证规则）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="validate_rule" value="<?php echo ($info["validate_rule"]); ?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">出错提示<span class="check-tips"></span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="error_info" value="<?php echo ($info["error_info"]); ?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">验证时间<span class="check-tips"></span></label>
						<div class="controls">
							<select name="validate_time">
                                <option value="3">始 终</option>
								<option value="1">新 增</option>
								<option value="2">编 辑</option>
								</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">自动完成方式<span class="check-tips"></span></label>
						<div class="controls">
							<select name="auto_type">
								<option value="function">函数</option>
								<option value="field">字段</option>
								<option value="string">字符串</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">自动完成规则<span class="check-tips">（根据完成方式订阅相关规则）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="auto_rule" value="<?php echo ($info["auto_rule"]); ?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">自动完成时间<span class="check-tips"></span></label>
						<div class="controls">
							<select name="auto_time">
								<option value="3">始 终</option>
								<option value="1">新 增</option>
								<option value="2">编 辑</option>
							</select>
						</div>
					</div>
				</div>

				<!-- 按钮 -->
				<div class="form-item cf">
					<label class="item-label"></label>
					<div class="controls edit_sort_btn">
						<input type="hidden" name="id" value="<?php echo ($info['id']); ?>"/>
						<input type="hidden" name="model_id" value="<?php echo ($info['model_id']); ?>"/>
						<button class="btn submit-btn ajax-post no-refresh" type="submit" target-form="form-horizontal">确 定</button>
						<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
					</div>
				</div>
			</form>
		</div>
	</div>

        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用<a href="http://www.onethink.cn" target="_blank">OneThink</a>管理平台</div>
                <div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "/bighaha", //当前网站地址
            "APP"    : "/bighaha/admin.php?s=", //当前项目地址
            "PUBLIC" : "/bighaha/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/bighaha/Public/static/think.js"></script>
    <script type="text/javascript" src="/bighaha/Public/Admin/js/common.js"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
    
<script type="text/javascript" charset="utf-8">
//导航高亮
highlight_subnav('<?php echo U('Model/index');?>');
Think.setValue('type', "<?php echo ((isset($info["type"]) && ($info["type"] !== ""))?($info["type"]):''); ?>");
Think.setValue('is_show', "<?php echo ((isset($info["is_show"]) && ($info["is_show"] !== ""))?($info["is_show"]):1); ?>");
Think.setValue('is_must', "<?php echo ((isset($info["is_must"]) && ($info["is_must"] !== ""))?($info["is_must"]):0); ?>");
Think.setValue('validate_time', "<?php echo ((isset($info["validate_time"]) && ($info["validate_time"] !== ""))?($info["validate_time"]):3); ?>");
Think.setValue('auto_time', "<?php echo ((isset($info["auto_time"]) && ($info["auto_time"] !== ""))?($info["auto_time"]):3); ?>");
Think.setValue('validate_type', "<?php echo ((isset($info["validate_type"]) && ($info["validate_type"] !== ""))?($info["validate_type"]):'regex'); ?>");
Think.setValue('auto_type', "<?php echo ((isset($info["auto_type"]) && ($info["auto_type"] !== ""))?($info["auto_type"]):'function'); ?>");
$(function(){
	showTab();
})
<?php if((ACTION_NAME) == "add"): ?>$(function(){
	$('#data-type').change(function(){
		$('#data-field').val($(this).find('option:selected').attr('rule'));
	});
})<?php endif; ?>
</script>

</body>
</html>