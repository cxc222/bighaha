<?php

return array(
    //模块名
    'name' => 'Recharge',
    //别名
    'alias' => '充值',
    //版本号
    'version' => '2.0.0',
    //是否商业模块,1是，0，否
    'is_com' => 1,
    //是否显示在导航栏内？  1是，0否
    'show_nav' => 1,
    //模块描述
    'summary' => '充值模块，适用于积分充值',
    //开发者
    'developer' => '嘉兴想天信息科技有限公司',
    //开发者网站
    'website' => 'http://www.ourstu.com',
    //前台入口，可用U函数
    'entry' => 'Recharge/index/index',

    'admin_entry' => 'Admin/Recharge/config',

    'icon' => 'credit',

    'can_uninstall' => 1
);