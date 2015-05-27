<?php
/**
 * 所属项目 cox.
 * 开发者: 陈一枭
 * 创建日期: 8/5/14
 * 创建时间: 10:31 AM
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Appstore\Model;


use Think\Model;

class AppstorePluginModel extends AppstoreGoodsModel implements BaseModel
{
    protected $entity = 'Plugin';
    public function getList($map = array(), $limit = 10, $order = 'update_time desc', $more = 0, $field = '*')
    {
        $map['entity'] = 1;
        $plugin = D('AppstoreGoods')->field($field)->where($map)->order($order)->findPage($limit);

        if ($more) {
            foreach ($plugin['data'] as &$v) {
                $v = $this->combineData($v);
            }
            unset($v);
        }
        return $plugin;
    }

    public function getById($id)
    {
        $data = D('AppstoreGoods')->find($id);
        $data = $this->combineData($data);
        return $data;
    }

    public function getLimit($map = array(), $limit = 10, $order = 'update_time desc', $more = 0, $field = '*')
    {
        $map['entity'] = 1;
        $plugin = D('AppstoreGoods')->field($field)->where($map)->order($order)->limit($limit)->select();

        if ($more) {
            foreach ($plugin as &$v) {
                $v = $this->combineData($v);
            }
            unset($v);
        }
        return $plugin;
    }


} 