<?php


namespace Recharge\Controller;

use Think\Controller;


class AliController extends Controller
{

    public function notify()
    {
        require_once('./Application/Recharge/Lib/Alipay/alipay.config.php');
        require_once('./Application/Recharge/Lib/Alipay/lib/alipay_notify.class.php');
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) { //验证成功

        $alipayRecordModel = D('recharge_record_alipay');
        $record['body'] = I('post.body', '未获取', 'op_t');
        $record['buyer_email'] = I('post.buyer_email', '未获取', 'op_t');
        $record['buyer_id'] = I('post.buyer_id', '未获取', 'op_t');
        $record['exterface'] = I('post.exterface', '未获取', 'op_t');
        $record['is_success'] = I('post.is_success', '未获取', 'op_t');
        $record['notify_id'] = I('post.notify_id', '未获取', 'op_t');
        $record['notify_time'] = I('post.notify_time', '0', 'strtotime');
        $record['notify_type'] = I('post.notify_type', '未获取', 'op_t');
        $record['out_trade_no'] = I('post.out_trade_no', '未获取', 'op_t');
        $record['payment_type'] = I('post.payment_type', '未获取', 'op_t');
        $record['seller_email'] = I('post.seller_email', '未获取', 'op_t');
        $record['seller_id'] = I('post.seller_id', '未获取', 'op_t');
        $record['subject'] = I('post.subject', '未获取', 'op_t');
        $record['total_fee'] = I('post.total_fee', '未获取', 'op_t');
        $record['trade_no'] = I('post.trade_no', '未获取', 'op_t');
        $record['trade_status'] = I('post.trade_status', '未获取', 'op_t');
        $record['sign'] = I('post.sign', '未获取', 'op_t');
        $record['sign_type'] = I('post.sign', '未获取', 'op_t');
        if (!$rs = $alipayRecordModel->add($record)) {
            exit('失败——保存支付结果失败。请联系管理员。');
            /*                $this->error('保存支付结果失败。请联系管理员。');*/
        };
        //商户订单号
        $order_id = $record['out_trade_no'];
        //交易状态
        if ($record['trade_status'] == 'TRADE_FINISHED' || $record['trade_status'] == 'TRADE_SUCCESS') {
            $rechargeModel = D('Order');
            $order = $rechargeModel->getOrder($order_id);
            if ($order['record_id'] == 0) {
                //未作处理
                if (!$order['amount'] == $record['total_fee']) {
                    exit('失败——付款订单出错，数额与订单不符，付款失败。请联系管理员。' . $order_id);
                    /*  $this->error('付款订单出错，数额与订单不符，付款失败。请联系管理员。');*/
                }
                if (!$rechargeModel->where(array('id' => $order_id))->setField('record_id', $rs)) {
                    exit('失败——更改订单状态失败。' . $order_id);
                    /*   $this->error('更改订单状态失败。');*/
                };
                $rechargeType = $order['recharge_type'];
                if (!$order['recharge_type']) {
                    exit('失败——充值字段合法性验证失败，请联系管理员。' . $order_id);
                    /*  $this->error('充值字段合法性验证失败，请联系管理员。');*/
                }
                $scoreType = $order['score_type'];
                $ratio = $rechargeType['UNIT'];
                $name = $scoreType['title'];

                $step = floor($order['amount'] * $ratio);

                $memberModel = D('Member');

                if ($memberModel->where(array('uid' => $order['uid']))->setInc('score' . $order['field'], $step)) {
                    $rechargeModel->where(array('id' => $order_id))->setField('payok', 1);

                    S('recharge_order_'.$order_id,null);
                    exit('成功——充值成功。' . get_nickname($order['uid']) . '[' . $order['uid'] . ']' . '的' . $name . ' 增加 ' . $step);
                    /*  $this->success('充值成功。您的' . $name . ' 增加 ' . $step . '。即将跳转回充值页面。', U('recharge/index/index'), 10);*/
                } else {
                    exit('失败——支付成功，但充值到数据库失败。请联系管理员。' . $order_id);
                    /*  $this->error('支付成功，但充值到数据库失败。请联系管理员。');*/
                }

            } else {
                exit('失败——该订单已经支付，请勿重复支付。' . $order_id);
                /*  $this->error('该订单已经支付，请勿重复支付。');*/
            }
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        } else {
            exit('失败——支付状态出错。' . $record['trade_status'] . $order_id);
            /* $this->error('支付状态出错。' . $record['trade_status']);*/
        }
        }else{
            exit('error');
        }
    }
}