<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class MobController extends AdminController
{


    public function config()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();

        $builder->title('基本配置');
        $builder->keyText('WEBSITE_NAME', '网站名称显示')
            ->keyEditor('COPY_RIGHT','网站版权信息')
            ->group('通用设置', 'WEBSITE_NAME,COPY_RIGHT');


        $builder->keyTextArea('SUMMARY', '登陆页导语','不要过长，一句话即可')
            ->group('登陆页设置', 'SUMMARY');


        $builder->buttonSubmit();


        $builder->data($data);


        $builder->display();
    }


}
