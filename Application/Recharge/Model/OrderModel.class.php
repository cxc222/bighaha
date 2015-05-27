<?php
namespace Recharge\Model;
use Think\Model;


class OrderModel extends Model
{
    protected $tableName='recharge_order';
    protected $_auto = array(
        array('id', 'randId', self::MODEL_INSERT, 'callback'),
        array('status', '1', self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('payok', '0', self::MODEL_INSERT),
        array('record_id', '0', self::MODEL_INSERT),
    );

    public function addOrder($data){
        $data = $this->create($data);
        if(!$data) return false;
        $result = $this->add($data);
        if(!$result) {
            return false;
        }
        action_log('create_order', 'RechargeOrder', $result, is_login());
        return $result;
    }


    public function getOrder($id){
        $order = S('recharge_order_'.$id);
        if(is_bool($order)){
            $order = $this->where(array('id'=>$id,'status'=>1))->find();
            if($order){
                $order['recharge_type'] =  get_recharge_type($order['field']);
                $order['pay_method'] = get_pay_method($order['method']);
                $order['score_type'] = D('Ucenter/Score')->getType(array('id'=>$order['field'],'status'=>1));
            }
            S('recharge_order_'.$id,$order,60*5);
        }
        return $order;
    }

    protected function randId(){
        $id = time().create_rand(4,'num');
        return doubleval($id);
    }


}