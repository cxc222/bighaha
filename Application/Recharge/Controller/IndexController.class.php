<?php


namespace Recharge\Controller;

use Think\Controller;


class IndexController extends Controller
{


    public function recharge()
    {
        if (modC('OPEN_RECHARGE', 0, 'recharge') == 0) {
            $this->error('404，充值未开启！');
        }

        if (IS_POST) {
            $this->createOrder();
        } else {
            $fields_config = modC('RE_FIELD', "", 'recharge');
            $fields = json_decode($fields_config, true);
            foreach ($fields as &$v) {
                $v['scoreType'] = D('Ucenter/Score')->getType(array('status' => 1, 'id' => $v['FIELD']));
                $v['have'] = D('Member')->where(array('uid' => is_login()))->getField('score' . $v['FIELD']);
            }
            $rcAmount = modC('RECHARGE_AMOUNT', "", 'recharge');
            $rcAmount && $amount = explode("\n", str_replace("\r", '', $rcAmount));
            $method = modC('METHOD', 'alipay', 'recharge');
            $this->assign('fields', $fields);
            $this->assign('method', explode(',', $method));
            $this->assign('amount', $amount);
            $this->assign('tab', 'recharge');

            $this->display();
        }
    }

    public function index()
    {
        $rechargeOpen = modC('OPEN_RECHARGE', 0, 'recharge');
        if ($rechargeOpen) {
            $this->redirect('recharge');
        } else {
            $this->redirect('rechargeList');
        }

    }


    /**订单确认
     * @auth 陈一枭
     */
    public function order()
    {
        $aOrderId = I('get.id', 0);
        $order = D('Order')->getOrder($aOrderId);
        if ($order['record_id']) {
            $record = D('Record')->getRecord($order['record_id'], $order['method']);
            $this->assign('record', $record);
        }

        $this->assign('order', $order);
        $this->assign('tab', 'rechargeList');
        $this->display('order' . $order['method']);
    }


    private function createOrder()
    {
        if (!is_login()) {
            $this->error('请登陆后再操作');
        }


        $this->checkActionLimit('create_order', 'RechargeOrder', 0, is_login());

        $aAmount = I('post.amount', 0, 'floatval');
        $aMethod = I('post.method', '', 'op_t');
        $aField = I('post.field', '', 'intval');

        $aAmount = number_format($aAmount, 2, ".", "");
        $minAmount = modC('MIN_AMOUNT', 0, 'recharge');
        if ($aAmount <= 0) {
            $this->error('充值金额不能小于等于0。');
        }
        $canInput = modC('CAN_INPUT', 1, 'recharge');
        if ($aAmount <= $minAmount && $canInput && $minAmount != 0) {
            $this->error('充值金额不能小于' . $minAmount . '。');
        }
        $method = modC('METHOD', 'alipay', 'recharge');
        if (!check_is_in_config($aMethod, $method)) {
            $this->error('不支持该支付方式，请尝试其他支付方式');
        }

        $data['field'] = $aField;
        $data['amount'] = $aAmount;
        $data['method'] = $aMethod;
        $data['uid'] = is_login();
        $order_id = D('Order')->addOrder($data);
        if ($order_id) {
            $this->redirect('order', array('id' => $order_id));
        }

    }

    public function rechargeList()
    {
        $aPage = I('get.page', 1, 'intval');
        $aPayOk = I('get.payOk', 'all', 'intval');
        $r = 10;
        $model = D('Order');
        $map = array('uid' => get_uid(), 'status' => 1);
        if ($aPayOk === 1) {
            $map['payok'] = array('neq', 0);
            $this->assign('payOk_1', 'active');
        } elseif ($aPayOk === 0) {
            $map['payok'] = 0;
            $this->assign('payOk_0', 'active');
        } else {
            $this->assign('payOk_all', 'active');
        }

        $list = $model->getList(array('where' => $map, 'page' => $aPage, 'count' => $r, 'order' => 'create_time desc'));

        foreach ($list as &$v) {
            $v = $model->getOrder($v);
        }
        unset($v);
        $this->assign('list', $list);
        $this->assign('totalCount', $model->where($map)->count());
        $this->assign('r', $r);
        $this->assign('tab', 'rechargeList');
        $this->display();
    }


    public function withdraw()
    {

        if (IS_POST) {
            $this->createWithdraw();
            $this->success('提现成功。即将跳转到提现列表页。', U('withdrawList'));
        } else {
            if (modC('OPEN_WITHDRAW', 0, 'recharge') == 0) {
                $this->error('404，提现未开启');
            }
            $this->assign('tab', 'withdraw');


            $fields_config = modC('WITHDRAW_FIELD', "", 'recharge');
            $fields = json_decode($fields_config, true);
            foreach ($fields as &$v) {
                $v['scoreType'] = D('Ucenter/Score')->getType(array('status' => 1, 'id' => $v['FIELD']));
                $v['have'] = D('Member')->where(array('uid' => is_login()))->getField('score' . $v['FIELD']);
            }

            $wdAmount = modC('WITHDRAW_AMOUNT', "", 'recharge');
            $wdAmount && $amount = explode("\n", str_replace("\r", '', $wdAmount));
            $method = modC('WITHDRAW_METHOD', 'alipay', 'recharge');
            $this->assign('fields', $fields);
            $this->assign('method', explode(',', $method));
            $this->assign('amount', $amount);

            $this->assign('fields', $fields);
            $this->assign('method', explode(',', $method));
            $this->assign('amount', $amount);
            $this->display();
        }

    }


    private function createWithdraw()
    {

        $loginUid = is_login();
        $this->checkActionLimit('create_withdraw', 'RechargeWithdraw', 0, $loginUid);

        if (!$loginUid) {
            $this->error('请登陆后再操作');
        }
        $aAmount = I('post.amount', 0, 'floatval');
        $aMethod = I('post.method', '', 'op_t');
        $aField = I('post.field', '', 'intval');
        $aAccountInfo = I('post.account_info', '', 'op_t');

        if (strlen($aAccountInfo) <= 0) {
            $this->error('请填写完整的收款信息。');
        }

        $method = modC('WITHDRAW_METHOD', 'alipay', 'recharge');
        if (!check_is_in_config($aMethod, $method)) {
            $this->error('不支持该支付方式，请尝试其他支付方式');
        }


        $aAmount = number_format($aAmount, 2, ".", "");
        $minAmount = modC('WITHDRAW_MIN_AMOUNT', 0, 'recharge');
        if ($aAmount <= 0) {
            $this->error('提现金额不能小于等于0。');
        }
        $canInput = modC('WITHDRAW_CAN_INPUT', 1, 'recharge');
        if ($canInput && $aAmount < $minAmount && $minAmount != 0) {
            $this->error('最小提现金额不能小于' . $minAmount . '。');
        }
        $type = get_withdraw_type($aField);
        $memberModel = D('Member');
        $score = $memberModel->where(array('uid' => $loginUid))->getField('score' . $aField);
        $forzen_count = $type['UNIT'] * $aAmount;

        if ($score - $forzen_count < 0) {
            $this->error('余额不足，无法提现。提现需' . $forzen_count . '，账户余额' . $score);
        }
        $result = $memberModel->where(array('uid' => $loginUid))->setDec('score' . $aField, $forzen_count);
        clean_query_user_cache($loginUid, 'score' . $aField);
        if (!$result) {
            $this->error('冻结账户余额失败。');
        }

        $data['field'] = $aField;
        $data['amount'] = $aAmount;
        $data['method'] = $aMethod;
        $data['uid'] = $loginUid;
        $data['account_info'] = $aAccountInfo;
        $data['frozen_amount'] = $forzen_count;
        $withdraw_id = D('Withdraw')->addWithdraw($data);
        return $withdraw_id;
    }


    public function withdrawList()
    {
        $aPage = I('get.page', 1, 'intval');

        $aPayOk = I('get.payOk', 'all', 'intval');

        $r = 10;
        $map = array('uid' => get_uid(), 'status' => 1);

        if ($aPayOk === 1) {
            $map['payok'] = 1;
            $this->assign('payOk_1', 'active');
        } elseif ($aPayOk === 0) {
            $map['payok'] = 0;
            $this->assign('payOk_0', 'active');
        } elseif ($aPayOk === 2) {
            $map['payok'] = array('in', array(-1, 2));
            $this->assign('payOk_2', 'active');
        } else {
            $this->assign('payOk_all', 'active');
        }

        $model = D('Withdraw');
        $list = $model->getList(array('where' => $map, 'order' => 'create_time desc', 'page' => $aPage, 'count' => $r));
        foreach ($list as &$v) {
            $v = $model->getWithdraw($v);
        }

        unset($v);
        $this->assign('list', $list);
        $this->assign('tab', 'withdrawList');
        $this->assign('totalCount', $model->where($map)->count());
        $this->assign('r', $r);
        $this->display();
    }

    public function cancelWithdraw()
    {
        $aId = I('post.id', 0, 'intval');
        //取消提现，可以是管理员或者当事人
        $withdrawModel = D('Withdraw');
        $withdraw = $withdrawModel->getWithdraw($aId);
        if (empty($withdraw) || $aId <= 0) {
            $this->error('提现不存在。');
        }
        $this->checkAuth(null, $withdraw['uid']);


        if ($withdraw['payok'] != 0) {
            $this->error('该提现不能被取消');
        }
        //取消订单
        $rs = $withdrawModel->where(array('id' => $withdraw['id']))->setField('payok', -1);
        S('withdraw_order_' . $withdraw['id'], null);
        //返还现金
        D('Member')->where(array('uid' => $withdraw['uid']))->setInc('score' . $withdraw['field'], $withdraw['frozen_amount']);
        clean_query_user_cache($withdraw['uid'], 'score' . $withdraw['field']);
        if (!$rs) {
            $withdrawModel->where(array('id' => $withdraw['id']))->setField('payok', 2); //待返还状态
            $this->error('返还金额失败。请联系管理员。');
        }
        $this->success('取消订单成功。冻结' . $withdraw['score_type']['title'] . $withdraw['frozen_amount'] . $withdraw['score_type']['unit'] . '已返还到您的指定账户。');
    }


}