<?php
namespace Store\Widget;
use Think\Controller;

class DefaultLiTplWidget extends Controller
{
    /**
     * @param mixed $data
     * infos 全部信息
     * class 类名
     * type 类型
     * @return string
     */
    public function render($data)
    {
        if ($data['type'] == 'list') {
            if ($data['infos']['data'] == '') {
                return '';
            }
            $data['entity'] = D('store_entity')->find($data['infos']['data'][0]['entity_id']);
            foreach ($data['infos']['data'] as $key => $vo) {
                $data['infos']['data'][$key]['user'] = query_user(array('nickname', 'avatar64','avatar32', 'space_url'), $vo['uid']);
                $data['infos']['data'][$key]['data']=D('Data')->getByInfoId($vo['id']);
            }
        } else {
            if ($data['infos'] == '') {
                return '';
            }

            $data['entity'] = D('store_entity')->find($data['infos'][0]['entity_id']);
            foreach ($data['infos'] as $key => $vo) {
                $data['infos'][$key]['user'] = query_user(array('nickname', 'avatar64','avatar32', 'space_url'), $vo['uid']);
                $data['infos'][$key]['data']=D('Data')->getByInfoId($vo['id']);
            }
        }
        if ($data['entity']['name'] == 'shop') {
            if($data['type']=='list')
            {
            /*获取商品信息*/
            foreach ($data['infos']['data'] as $key => $v) {
                $data['infos']['data'][$key]['goods'] = D('Info')->getLimit("shop_id=" . $v['id'].' and status=1', 8, 'create_time desc');
                foreach ($data['infos']['data'][$key]['goods'] as $k => $vo) {
                    $data['infos']['data'][$key]['goods'][$k]['data'] = D('Data')->getByInfoId($vo['id']);
                }
            }
            $this->assign($data);
            $this->display('Widget/DefaultLiTpl/shop');
            }
            else{
                /*获取商品信息*/
                foreach ($data['infos'] as $key => $v) {
                    $data['infos'][$key]['goods'] = D('Info')->getLimit("shop_id=" . $v['id'].' and status=1', 12, 'create_time desc');
                    foreach ($data['infos'][$key]['goods'] as $k => $vo) {
                        $data['infos'][$key]['goods'][$k]['data'] = D('Data')->getByInfoId($vo['id']);
                    }
                }
                $this->assign($data);
                $this->display('Widget/DefaultLiTpl/shop');
            }
        } else if($data['entity']['name']=='good')
        {

            if($data['type']!='list')
            {
                foreach ($data['infos'] as $key => $v) {
                    $data['infos'][$key]['shop'] = D('Info')->getById($v['shop_id']);
                }
            }else{
                foreach ($data['infos']['data'] as $key => $v) {
                    $data['infos']['data'][$key]['shop'] = D('Info')->getById($v['shop_id']);
                }
            }
            $this->assign($data);
            $this->display('Widget/DefaultLiTpl/good');
        }else
        {
            $this->assign($data);
            $this->display('Widget/DefaultLiTpl/tpl');
        }

    }
}