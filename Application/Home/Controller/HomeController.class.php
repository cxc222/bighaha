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


class HomeController extends AdminController
{


    public function config()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();

        $data['OPEN_LOGIN_PANEL'] = $data['OPEN_LOGIN_PANEL'] ? $data['OPEN_LOGIN_PANEL'] : 1;


        $builder->title('首页设置');

        $builder->keySingleImage('LOGO', 'Logo图标', '此处不同于网站信息内的LOGO，只在首页显示');
        $builder->keyText('ENTER_URL', '进入网站Url','不填则隐藏此按钮，不留入口，支持形如weibo/index/index之类的tp写法和http://等常规写法');
        $builder->keyEditor('SUMMARY', '导语', '顶部导语，用一句话来介绍你的网站');

        $builder->group('内容设置', 'LOGO,SUMMARY,OPEN_LOGIN_PANEL,ENTER_URL');

        $modules = D('Common/Module')->getAll();
        foreach ($modules as $m) {
            if ($m['is_setup'] == 1 && $m['entry'] != '') {
                if (file_exists(APP_PATH . $m['name'] . '/Widget/HomeBlockWidget.class.php')) {
                    $module[] = array('data-id' => $m['name'], 'title' => $m['alias']);
                }
            }
        }
        $module[] = array('data-id' => 'slider', 'title' => '轮播');

        $default = array(array('data-id' => 'disable', 'title' => '禁用', 'items' => $module), array('data-id' => 'enable', 'title' => '启用', 'items' => array()));
        $builder->keyKanban('BLOCK', '展示模块');
        $data['BLOCK'] = $builder->parseKanbanArray($data['BLOCK'], $module, $default);
        $builder->group('展示模块', 'BLOCK');


        $builder->keySingleImage('PIC1', '图片');
        $builder->keyText('URL1', '链接');
        $builder->keyText('TITLE1', '标题');
        $builder->keyRadio('TARGET1', '新窗口打开', '', array('_blank' => '新窗口', '_self' => '本窗口'));

        $builder->group('幻灯片1', 'PIC1,URL1,TITLE1,TARGET1');

        $builder->keySingleImage('PIC2', '图片');
        $builder->keyText('URL2', '链接');
        $builder->keyText('TITLE2', '标题');
        $builder->keyRadio('TARGET2', '新窗口打开', '', array('_blank' => '新窗口', '_self' => '本窗口'));

        $builder->group('幻灯片2', 'PIC2,URL2,TITLE2,TARGET2');


        $builder->keySingleImage('PIC3', '图片');
        $builder->keyText('URL3', '链接');
        $builder->keyText('TITLE3', '标题');
        $builder->keyRadio('TARGET3', '新窗口打开', '', array('_blank' => '新窗口', '_self' => '本窗口'));

        $builder->group('幻灯片3', 'PIC3,URL3,TITLE3,TARGET3');

        $show_blocks = get_kanban_config('BLOCK_SORT', 'enable', array(), 'Home');


        $builder->buttonSubmit();


        $builder->data($data);


        $builder->display();
    }


}
