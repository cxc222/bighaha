<?php

function get_pay_method($method){
    switch ($method) {
        case 'alipay':
            return '支付宝';
    }
}



function get_recharge_type($field){
    $fields_config = modC('RE_FIELD', "", 'recharge');
    $fields = json_decode($fields_config,true);
    $res = array_search_key($fields,'FIELD',$field);
    return $res;
}

function get_withdraw_type($field){
    $fields_config = modC('WITHDRAW_FIELD', "", 'recharge');
    $fields = json_decode($fields_config,true);
    $res = array_search_key($fields,'FIELD',$field);
    return $res;
}



function get_order_status_cn($order_id){
    $status = get_order_status($order_id);
     switch($status){
        case 1: return '支付成功！';
        case 2: return '支付成功，但写入数据库失败，请联系管理员！';
        case 0:   return '未支付';
    }
}


function get_order_status($order_id){
    $order = D('Order')->getOrder($order_id);
    $record = D('Record')->getRecord($order['record_id'],$order['method']);

    if($record['trade_status'] == 'TRADE_FINISHED' || $record['trade_status'] == 'TRADE_SUCCESS'){
        if($order['payok']){
            return 1;
        }else{
            return 2;
        }
    }else{
        return 0;
    }
}