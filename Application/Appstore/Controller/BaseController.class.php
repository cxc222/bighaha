<?php


namespace Appstore\Controller;

use Appstore\Model;
use Think\Controller;

class BaseController extends Controller
{
    protected $mAuthModel;

    public function  _initialize()
    {
        $this->setTitle('云商店');
        $this->mAuthModel = new Model\AuthModel();
        if (is_login()) {
            $sub_menu['right'][] = array('tab' => 'center', 'title' => '我是开发者', 'href' => U('admin/index'),
                'children' => array(
                    array('title' => '开发者认证', 'href' => U('admin/verify')),
                    array('title' => '控制面板', 'href' => U('admin/my')),
                    array('title' => '我的插件', 'href' => U('admin/myplugin')),
                    array('title' => '我的模块', 'href' => U('admin/mymodule'))
                ),

            );
        } else {
            $sub_menu = array('right' => array());
        }
        $sub_menu['left'] =
            array(
                array('tab' => 'home', 'href' => U('index/index'), 'title' => '首页'),
                array('tab' => 'plugin', 'href' => U('index/plugin'), 'title' => '插件'),
                array('tab' => 'module', 'href' => U('index/module'), 'title' => '模块'),
                array('tab' => 'theme', 'href' => U('index/theme'), 'title' => '主题'),
                array('tab' => 'service', 'href' => 'javascript:toast.error(\'即将开放，敬请期待。\')', 'title' => '服务'),
            );

        $this->assign('sub_menu', $sub_menu);
        $this->assign('current', 'home');

    }

    public function index()
    {
        $this->display();
    }

    public function _empty()
    {
        $this->error('404');
    }
}