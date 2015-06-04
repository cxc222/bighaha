<?php
/**信息模型
 * Class InfoModel
 */
class SendModel extends Model
{
    protected $tableName = 'store_send';

    function getList($map = '', $num = 10, $order = 'cTime desc')
    {
        $rec= $this->where($map)->order($order)->findPage($num);

        foreach($rec['data'] as $key=>$v)
        {
            $rec['data'][$key]['user']=D('User')->getUserInfo($v['send_uid']);
            $rec['data'][$key]['rec_user']=D('User')->getUserInfo($v['rec_uid']);
            $rec['data'][$key]['s_info']=D('Info')->getById($v['s_info_id']);
            $rec['data'][$key]['info']=D('Info')->getById($v['info_id']);
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