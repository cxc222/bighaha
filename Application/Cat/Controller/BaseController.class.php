<?php

namespace Cat\Controller;

use Think\Controller;

/*
 * 最基础的类，实现对默认应用名的支持
 */

class BaseController extends Controller
{

    protected $view = null;
    protected $APP_NAME = '';

    public function  _initialize()

    {
        $APP_NAME = $this->app['app_alias'];
        $this->APP_NAME = $APP_NAME;
        $data['APP_NAME'] = $APP_NAME;

        $havent_read = S('cat_havent_read' . is_login());
        if (empty($havent_read)) {
            $map['rec_uid'] = is_login();
            $map['readed'] = 0;
            $havent_read = D('cat_send')->where($map)->count();
            S('cat_havent_read' . is_login(), $havent_read, 60);
        }
        $data['havent_read'] = $havent_read;

        $map['status']=1;
        $tree = D('cat_entity')->where($map)->order('sort desc')->select();
        $menu_list = array(
            'left' =>
                array(
                    array('tab' => 'home', 'title' => '首页', 'href' => U('Cat/index/index')),
                ),
            'right' =>
                array(
                    array('tab' => 'post', 'title' => '发布'),
                    array('tab' => 'orders', 'title' => '个人中心', 'href' => U('Cat/center/my')),
                )
        );
        foreach ($tree as $category) {
            if($category['show_nav']){
                $menu = array('tab' => 'category_' . $category['id'], 'title' => $category['alias'], 'href' => U('Cat/index/li', array('name' => $category['name'])));
                $menu_list['left'][] = $menu;
            }

            if (CheckCanPostEntity(is_login(), $category['id']) && $category['show_post']) {

                $menu = array('tab' => 'category_' . $category['id'], 'title' => $category['alias'], 'href' => U('Cat/index/post', array('name' => $category['name'])));

                $menu_list['right'][0]['children'][] = $menu;
            }

        }


        $this->assign('sub_menu', $menu_list);
        $this->assign('current', 'home');

        $this->assign($data);

    }

    public function ajaxReturnS()
    {
        $this->ajaxReturn(null, null, 1);
    }

    public function ajaxReturnF()
    {
        $this->ajaxReturn(null, null, 0);
    }

    public function quickReturn($rs)
    {
        if ($rs) {
            $this->ajaxReturnS();
        } else {
            $this->ajaxReturnF();
        }
    }
}