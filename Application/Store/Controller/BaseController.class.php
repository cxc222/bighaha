<?php
namespace Store\Controller;

use Think\Controller;
class BaseController extends Controller
{

    protected $view = null;

    public function  _initialize()
    {
        $sub_menu =
            array(
                'left' =>
                    array(
                        array('tab' => 'li_good', 'title' => '商品', 'href' => U('Store/Index/li',array('name'=>'good'))),
                        array('tab' => 'lists', 'title' => '店铺街', 'href' => U('Store/Shop/lists')),
                        array('tab' => 'myStore','title' => "我的" . $this->MODULE_ALIAS ,
                            'children' => array(
                                array('tab' => 'center', 'title' => '个人中心', 'href' => is_login() ? U('Store/Center/detail') : "javascript:toast.error('登录后才能操作')"),
                                array('tab' => 'buy', 'title' => '结算购物车','href' => is_login() ? U('Store/Center/buy') : "javascript:toast.error('登录后才能操作')"),
                                array('tab' => 'order', 'title' => '订单管理','href' => is_login() ? U('Store/Center/orders') : "javascript:toast.error('登录后才能操作')"),
                            )
                        ),
                    ),
                'right' =>
                    array(
                        array('tab'=>'create','title' => '<i class="icon-signin"></i> 店铺入驻', 'href' =>is_login()?U('Store/Center/createShop',array('name'=>'shop')):"javascript:toast.error('登录后才能操作')"),
                    )
            );
        $this->assign('menu_list', $sub_menu);
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