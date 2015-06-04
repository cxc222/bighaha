<?php
namespace Store\Widget;

use Think\Controller;

class DefaultInfoTplWidget extends Controller
{
    public function render($data)
    {

        $data['entity'] = D('store_entity')->find($data['info']['entity_id']);
        $data['data'] = D('Data')->getByInfoId($data['info']['id']);
        $data['user'] = query_user(array('nickname', 'space_url', 'avatar64', 'avatar128'), $data['info']['uid']);
        $data['user']['info_count'] = D('Goods')->where('uid=' . $data['info']['uid'])->count();
        $map['info_id'] = $data['info']['id'];
        $data['mid'] = is_login();
        // $shop=D('Shop')->getById($data['info']['shop_id']);
        $this->assign($data);

        if ($data['entity']['name'] == 'shop') {
            $content = $this->fetch('Widget/DefaultInfoTpl/shop');
        } elseif ($data['entity']['name'] == 'good') {
            $data['shop'] = D('Shop')->getById($data['info']['shop_id']);
            $this->assign($data);
            $content = $this->fetch('Widget/DefaultInfoTpl/good');
        } else {
            $content = $this->fetch('Widget/DefaultInfoTpl/tpl');
        }
        return $content;
    }

    public function show($data)
    {
        $data['entity'] = D('store_entity')->find($data['info']['entity_id']);
        $data['data'] = D('Data')->getByInfoId($data['info']['id']);
        $data['user'] = query_user(array('nickname', 'space_url', 'avatar64', 'avatar128'), $data['info']['uid']);
        $data['user']['info_count'] = D('Goods')->where('uid=' . $data['info']['uid'])->count();
        $map['info_id'] = $data['info']['id'];
        $data['mid'] = is_login();

        $items = D('store_item')->where('good_id=' . $data['info']['id'])->select();
        $ids = getSubByKey($items, 'order_id');
        $ids_uni = array_unique($ids);
        $m_com['id'] = array('in', implode(',', $ids_uni));
        $data['info']['com'] = D('Order')->where($m_com)->findPage(10);

        foreach ($data['info']['com']['data'] as $k => &$v) {
            $data['info']['com']['data'][$k]['user'] = query_user(array('nickname', 'space_url', 'avatar64'), $v['uid']);
            $v['response_time_format'] = $v['response_time'] ? friendlyDate($v['response_time']) : '系统自动';

        }

        $this->assign($data);
        if ($data['entity']['name'] == 'shop') {
            $this->display('Widget/DefaultInfoTpl/shop');
        } elseif ($data['entity']['name'] == 'good') {
            $data['shop'] = D('Shop')->getById($data['info']['shop_id']);
            $this->assign($data);
            $this->display('Widget/DefaultInfoTpl/good');
        } else {
            $this->display('Widget/DefaultInfoTpl/tpl');
        }
    }

    public function render1($data)
    {

        $data['entity'] = D('store_entity')->find($data['info']['entity_id']);
        $data['data'] = D('Data')->getByInfoId($data['info']['info_id']);
        $data['user'] = D('User')->getUserInfo($data['info']['uid']);
        $map['info_id'] = $data['info']['id'];
        $m_com['condition'] = ORDER_CON_DONE;
        $m_com['response_time'] = array('neq', 0);

        $items = D('store_item')->where('good_id=' . $data['info']['info_id'])->findAll();
        $ids = getSubByKey($items, 'order_id');
        $ids_uni = array_unique($ids);
        $m_com['order_id'] = array('in', implode(',', $ids_uni));
        $data['info']['com'] = D('store_order')->where($m_com)->findPage(5);
        foreach ($data['info']['com']['data'] as $k => $v) {
            $data['info']['com']['data'][$k]['user'] = D('User')->getUserInfo($v['uid']);
        }
        $data['info']['data'] = D('Data')->getByInfoId($data['info']['info_id']);
        $data['mid'] = $this->mid;
        if ($data['entity']['name'] == 'shop') {
            $content = $this->renderFile(dirname(__FILE__) . '/shop.html', $data);
        } elseif ($data['entity']['name'] == 'good') {
            $data['shop'] = D('Info')->getById($data['info']['shop_id']);
            $content = $this->renderFile(dirname(__FILE__) . '/good.html', $data);
        } else {
            $content = $this->renderFile(dirname(__FILE__) . '/tpl.html', $data);
        }
        return $content;
    }
}