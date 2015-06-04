<?php
/**
 * 所属项目 商业版.
 * 开发者: 陈一枭
 * 创建日期: 2014-11-10
 * 创建时间: 13:08
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Recharge\Model;


use Think\Model;

class WithdrawModel extends Model
{
    protected $tableName = 'recharge_withdraw';


    protected $_auto = array(
        array('status', '1', self::MODEL_INSERT),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    public function addWithdraw($data){
        $data = $this->create($data);
        if(!$data) return false;
        $result = $this->add($data);
        if(!$result) {
            return false;
        }
        action_log('create_withdraw', 'RechargeWithdraw', $result, is_login());
        return $result;
    }

    public function getWithdraw($id){
        $order = S('withdraw_order_'.$id);
        if(is_bool($order)){
            $order = $this->where(array('id'=>$id,'status'=>1))->find();
            if($order){
                $order['recharge_type'] =  get_withdraw_type($order['field']);
                $order['pay_method'] = get_pay_method($order['method']);
                $order['score_type'] = D('Ucenter/Score')->getType(array('id'=>$order['field'],'status'=>1));
            }
            S('withdraw_order_'.$id,$order,60*5);
        }
        return $order;
    }


} 