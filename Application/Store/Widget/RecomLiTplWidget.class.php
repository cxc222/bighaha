<?php

namespace Store\Widget;

use Think\Controller;
class RecomLiTplWidget extends Controller
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
                $data['infos']['data'][$key]['user'] =query_user(array('nickname','avatar32','space_url'),$vo['uid']) ;
            }
        } else {
            if ($data['infos'] == '') {
                return '';
            }

            $data['entity'] = D('store_entity')->find($data['infos'][0]['entity_id']);
            foreach ($data['infos'] as $key => $vo) {
                $data['infos'][$key]['user'] = query_user(array('nickname','avatar64','space_url'),$vo['uid']) ;
            }
        }

        $this->assign( $data);

        return $this->fetch('Widget/RecomLiTpl/tpl');

    }
}