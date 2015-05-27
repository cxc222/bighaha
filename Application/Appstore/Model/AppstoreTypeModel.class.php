<?php

namespace Appstore\Model;


use Think\Model;

class AppstoreTypeModel  extends Model implements BaseModel
{
    public function getList($map = array(), $limit = 10, $order = 'id desc', $more = 0, $field = '*')
    {

       $list=$this->where($map)->order($order)->field($field)->findPage($limit);
        if ($more) {

            unset($v);
        }
        return $list;
    }

    public function getLimit($map = array(), $limit = 10, $order = 'id desc', $more = 0, $field = '*')
    {
        $map['entity'] = 3;
        $plugin = D('AppstoreGoods')->field($field)->where($map)->order($order)->limit($limit)->select();

        if ($more) {
            foreach ($plugin as &$v) {
                $v['user'] = query_user(array('nickname', 'space_url'), $v['uid']);
                $resource = D('AppstoreResource')->find($v['id']);
                $ext = D('AppstoreTheme')->find($v['id']);
                if ($resource)
                    $v = array_merge($v, $resource);
                if ($ext)
                    $v = array_merge($v, $ext);
            }
            unset($v);
        }
        return $plugin;
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }
}