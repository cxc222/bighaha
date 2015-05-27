<?php
namespace Store\Controller;

use Think\Controller;

class ShopController extends BaseController
{
    public function  _initialize()
    {
        parent::_initialize();


    }

    public function detail()
    {
        $aId = I('get.id', 0, 'intval');

        $shop = D('Shop')->getById($aId);

        $this->checkStatus($shop);
        $this->assign('shop', $shop);

        $this->setTitle('{$shop.title|op_t}');
        $this->display();
    }

    public function lists()
    {
        $list = D('Shop')->getList();

        $this->assign('list', $list);
        $this->assign('tab', 'shop');
        $this->setTitle('店铺街');
        $this->display();
    }

    public function goods()
    {
        $aId = I('get.id', 0, 'intval');
        $shop = D('Shop')->getById($aId);
        $this->checkStatus($shop);
        $this->assign('shop', $shop);
        $this->setTitle('全部商品 —— {$shop.title|op_t}');
        $this->display();
    }

    private function checkStatus($shop)
    {
        if (!$shop || $shop['status'] != 1) {
            $this->error('店铺不存在。');
        }
    }
} 