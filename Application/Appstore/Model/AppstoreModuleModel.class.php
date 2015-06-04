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

class AppstoreModuleModel extends AppstoreGoodsModel implements BaseModel
{
    protected $entity = 'Module';

    public function getList($map = array(), $limit = 10, $order = 'id desc', $more = 0, $field = '*')
    {
        $map['entity'] = 2;
        $plugin = D('AppstoreGoods')->field($field)->where($map)->order($order)->findPage($limit);

        if ($more) {
            foreach ($plugin['data'] as &$v) {
                $v = $this->combineData($v);
            }
            unset($v);
        }
        return $plugin;
    }

    public function getLimit($map = array(), $limit = 10, $order = 'id desc', $more = 0, $field = '*')
    {
        $map['entity'] = 2;
        $plugin = D('AppstoreGoods')->field($field)->where($map)->order($order)->limit($limit)->select();

        if ($more) {
            foreach ($plugin as &$v) {
                $v = array_merge($v, D('AppstoreResource')->find($v['id']));
                $v = array_merge($v, D('AppstoreModule')->find($v['id']));
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
}