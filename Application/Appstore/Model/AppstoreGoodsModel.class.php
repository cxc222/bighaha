<?php
/**
 * 所属项目 商业版.
 * 开发者: 陈一枭
 * 创建日期: 8/7/14
 * 创建时间: 9:52 AM
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Appstore\Model;


use Think\Model;

class AppstoreGoodsModel extends Model
{
    protected $entity = 'Plugin'; //1插件2模块3主题4服务

    /**
     * @param $data
     * @return array
     * @auth 陈一枭
     */
    public function combineData($data)
    {
        $data['user'] = query_user(array('nickname', 'space_url'), $data['uid']);
        $resource = D('AppstoreResource')->find($data['id']);
        $ext = D('Appstore' . $this->entity)->find($data['id']);

        if ($resource)
            $data = array_merge($data, $resource);
        if ($ext)
            $data = array_merge($data, $ext);

        $versions = D('AppstoreVersion')->where(array('goods_id' => $data['id']))->order('update_time desc')->limit(5)->select();
        $data['versions'] = $versions;
        return $data;
    }

    public function getLimit($map = array(), $limit = 10, $order = 'update_time desc', $field = '*')
    {
        $data = D('AppstoreGoods')->field($field)->where($map)->order($order)->limit($limit)->select();
        return $data;
    }
} 