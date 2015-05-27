<?php
namespace Cat\Model;

use Think\Model;

/**信息模型
 * Class InfoModel
 */
class SendModel extends Model implements IBaseModel
{
    protected $tableName = 'cat_send';

    function getList($map = '', $num = 10, $order = 'create_time desc')
    {
        $rec = $this->where($map)->order($order)->findPage($num);
        foreach ($rec['data'] as $key => $v) {
            $rec['data'][$key]['user'] = query_user(array('nickname', 'space_url', 'avatar64'), $v['send_uid']);
            $rec['data'][$key]['rec_user'] = query_user(array('nickname', 'space_url', 'avatar64'), $v['rec_uid']);
            $rec['data'][$key]['s_info'] = D('Info')->getById($v['s_info_id']);
            $rec['data'][$key]['info'] = D('Info')->getById($v['info_id']);
        }
        return $rec;
    }

    function getLimit($map = '', $num = 10, $order = 'cTime desc')
    {

    }

    function getById($id)
    {

    }
}