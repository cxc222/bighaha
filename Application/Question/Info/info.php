<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-5
 * Time: 上午10:12
 * @author 郑钟良<zzl@ourstu.com>
 */


return array(
    //模块名
    'name' => 'Question',
    //别名
    'alias' => '问答',
    //版本号
    'version' => '2.0.0',
    //是否商业模块,1是，0，否
    'is_com' => 1,
    //是否显示在导航栏内？  1是，0否
    'show_nav' => 1,
    //模块描述
    'summary' => '问答模块，用户可前台发布、回答问题',
    //开发者
    'developer' => '嘉兴想天信息科技有限公司',
    //开发者网站
    'website' => 'http://www.ourstu.com',
    //前台入口，可用U函数
    'entry' => 'Question/index/index',

    'admin_entry' => 'Admin/Question/index',

    'icon' => 'question',

    'can_uninstall' => 1

);