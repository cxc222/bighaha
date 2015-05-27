<?php
namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;

require_once './Application/Recharge/Common/function.php';

class RechargeController extends AdminController
{

    public function configCallback($config)
    {
        if (check_is_in_config('alipay',$config['METHOD'])) {
            require_once './Application/Recharge/Lib/Alipay/alipay.config.php';
            $content = file_get_contents('./Application/Recharge/Lib/Alipay/alipay.config.php');
            $content = preg_replace('/partner.*?;/', "partner']	= '".$config['ALIPAY_PARTNER']."';",$content);
            $content = preg_replace('/seller_email.*?;/',"seller_email']	= '".$config['ALIPAY_SELLER_EMAIL']."';",$content);
            $content = preg_replace("/'key'.*?;/","'key']= '".$config['ALIPAY_KEY']."';",$content);
            file_put_contents('./Application/Recharge/Lib/Alipay/alipay.config.php',$content);
        }
    }

    public function config()
    {

        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        $configBuilder = new AdminConfigBuilder();
        $data = $configBuilder->callback('configCallback')->handleConfig();

        $param = array();
        $param['opt'] = $field;
        $de_data = $data['RE_FIELD'];
        $param['jsonData'] = $de_data;
        $param['data'] = json_decode($de_data, true);

        $param_w = array();
        $param_w['opt'] = $field;
        $w_data = $data['WITHDRAW_FIELD'];
        $param_w['jsonData'] = $w_data;
        $param_w['data'] = json_decode($w_data, true);

        $configBuilder->title('充值设置')->data($data)


            ->keyBool('OPEN_RECHARGE', '开启充值')
            ->keyTextArea('RECHARGE_AMOUNT', '充值面额', '一行一个')
            ->keyBool('CAN_INPUT', '允许自由充值')
            ->keyText('MIN_AMOUNT', '最小充值面额，0为不限制，只对自由充值开启有效')
            ->keyCheckBox('METHOD', '支付方式', '选择支付种类', array('alipay' => '支付宝'))
            ->keyUserDefined('RE_FIELD', '支持充值的积分类型', '选择支持充值的积分类型和积分的兑率，如填写100则表示1RMB=100积分', T('Recharge@Recharge/config'), $param)
            ->keyDefault('METHOD', 'alipay')
            ->keyDefault('MIN_AMOUNT', 0)


            ->keyBool('OPEN_WITHDRAW', '开启提现')
            ->keyTextArea('WITHDRAW_AMOUNT', '提现面额', '一行一个')
            ->keyBool('WITHDRAW_CAN_INPUT', '允许自由提现')
            ->keyText('WITHDRAW_MIN_AMOUNT', '最小提现面额，0为不限制，只对自由提现开启有效')
            ->keyCheckBox('WITHDRAW_METHOD', '支付方式', '选择支付种类', array('alipay' => '支付宝'))
            ->keyUserDefined('WITHDRAW_FIELD', '支持提现的积分类型', '选择支持提现的积分类型和积分的兑率，如填写100则表示100积分=1RMB', T('Recharge@Recharge/config'), $param_w)
            ->keyDefault('WITHDRAW_METHOD', 'alipay')
            ->keyDefault('WITHDRAW_MIN_AMOUNT', 0)


            ->group('充值设置', 'OPEN_RECHARGE,RECHARGE_AMOUNT,CAN_INPUT,MIN_AMOUNT,METHOD,RE_FIELD')
            ->group('提现设置', 'OPEN_WITHDRAW,WITHDRAW_AMOUNT,WITHDRAW_CAN_INPUT,WITHDRAW_MIN_AMOUNT,WITHDRAW_METHOD,WITHDRAW_FIELD');

        if (check_is_in_config('alipay', $data['METHOD'])) {
            $configBuilder->keyText('ALIPAY_PARTNER', '合作身份者id', '以2088开头的16位纯数字')
                ->keyText('ALIPAY_SELLER_EMAIL', '收款支付宝账号')
                ->keyText('ALIPAY_KEY', '安全检验码', '以数字和字母组成的32位字符')
                ->group('支付宝配置', 'ALIPAY_PARTNER,ALIPAY_SELLER_EMAIL,ALIPAY_KEY');
        }

        $configBuilder->buttonSubmit()
            ->buttonBack();
        $configBuilder->display();
    }



    public function alipayList($r = 15, $page = 1)
    {
        $aBuyerEmail = I('buyer_email', '', 'op_t');
        if ($aBuyerEmail != '') {
            $map['buyer_email'] = array('like', '%' . $aBuyerEmail . '%');
        }
        $listBuilder = new AdminListBuilder();
        $recordModel = D('recharge_record_alipay');
        $data = $recordModel->where($map)->order('notify_time desc')->page($page, $r)->select();
        $totalCount = $recordModel->where($map)->count();
        foreach ($data as &$v) {
            $v['is_success'] = $v['is_success'] == 'T' ? 1 : 0;

        }
        unset($v);
        $listBuilder->title('支付宝充值订单');
        $listBuilder->keyId()->keyText('out_trade_no', '订单编号')->keyText('buyer_email', '付款人支付宝')->keyText('seller_email', '收款账户')
            ->keyText('total_fee', '充值金额')->keyText('trade_no', '支付宝订单号')->keyBool('is_success', '支付成功')->keyTime('notify_time', '付款时间');
        $listBuilder->search('付款人支付宝', 'buyer_email');
        $listBuilder->data($data)->pagination($totalCount, $r);
        $listBuilder->display();
    }


    public function rechargeList($r = 15, $page = 1)
    {
        $listBuilder = new AdminListBuilder();
        $recordModel = D('recharge_order');
        $data = $recordModel->order('create_time desc')->page($page, $r)->select();
        $totalCount = $recordModel->count();
        foreach ($data as &$v) {
            $type = D('Ucenter/Score')->getType(array('id' => $v['field'], 'status' => 1));
            $v['type_title'] = $type['title'];
            $v['method_name'] = get_pay_method($v['method']);
        }
        unset($v);
        $listBuilder->title('充值记录');
        $listBuilder->keyId()->keyText('type_title', '充值字段')->keyText('amount', '充值金额')->keyText('method_name', '充值方式')
            ->keyUid()->keyCreateTime()->keyStatus()->keyText('record_id', '关联的支付记录ID')->keyBool('payok', '付款成功');
        $listBuilder->data($data)->pagination($totalCount, $r);
        $listBuilder->display();
    }

    public function withdrawList($r = 15, $page = 1)
    {
        $listBuilder = new AdminListBuilder();
        $recordModel = D('recharge_withdraw');
        $data = $recordModel->order('create_time desc')->page($p, $r)->select();
        $totalCount = $recordModel->count();
        foreach ($data as &$v) {
            $type = D('Ucenter/Score')->getType(array('id' => $v['field'], 'status' => 1));
            $v['type_title'] = $type['title'];
            $v['method_name'] = get_pay_method($v['method']);
            $v['pay_condition'] = $this->getConditionText($v['payok']);
            if ($v['pay_uid'] != 0) {
                $user = query_user(array('space_link'), $v['pay_uid']);
                $v['operator'] = $user['space_link'];
            } else {
                $v['operator'] = '-';
            }
            $v['pay_time'] = $v['pay_time'] == 0 ? '-' : $v['pay_time'];
        }
        unset($v);
        $listBuilder->title('提现记录');
        $listBuilder->keyId()->keyText('type_title', '提现字段')->keyText('amount', '提现金额')->keyText('frozen_amount', '冻结积分')->keyUid()->keyText('method_name', '提现方式')
            ->keyCreateTime()->keyText('pay_condition', '支付状态')->keyText('operator', '操作者')->keyTime('pay_time', '提现操作时间')->keyText('account_info', '收款账户信息');
        $listBuilder->data($data)->pagination($totalCount, $r);


        $listBuilder->ajaxButton(U('recharge/doWithdraw'), null, '提现');
        $listBuilder->ajaxButton(U('recharge/cancelWithdraw'), null, '关闭提现');
        $listBuilder->display();
    }


    public function doWithdraw($ids = array())
    {
        if (empty($ids)) {
            $this->error('请选择要操作的选项');
        }
        $withdrawModel = D('Recharge/Withdraw');
        foreach ($ids as $id) {
            $withdraw = $withdrawModel->getWithdraw($id);
            if (empty($withdraw) || $id <= 0) {
                continue;
            }
            if ($withdraw['payok'] != 0) {
                continue;
            }
            $withdraw['payok'] = 1;
            $withdraw['pay_uid'] = get_uid();
            $withdraw['pay_time'] = time();
            $rs = $withdrawModel->save($withdraw);
            if (!$rs) {
                continue;
            }
            S('withdraw_order_' . $withdraw['id'], null);
            //提现成功，向用户发送消息
            D("Common/Message")->sendMessageWithoutCheckSelf($withdraw['uid'], '您的提现已经受理，请注意查收。', '【充值中心】提现完成通知', U('recharge/index/withdrawList'), is_login());
        }

        $this->success('提现成功。');
    }


    public function cancelWithdraw($ids = array())
    {
        if (empty($ids)) {
            $this->error('请选择要操作的选项');
        }
        $withdrawModel = D('Recharge/Withdraw');
        foreach ($ids as $id) {
            $withdraw = $withdrawModel->getWithdraw($id);
            if (empty($withdraw) || $id <= 0) {
                continue;
            }

            if ($withdraw['payok'] != 0) {
                continue;
            }

            //取消订单
            $rs = $withdrawModel->where(array('id' => $withdraw['id']))->setField('payok', -1);
            S('withdraw_order_' . $withdraw['id'], null);
            //返还现金
            D('Member')->where(array('uid' => $withdraw['uid']))->setInc('score' . $withdraw['field'], $withdraw['frozen_amount']);
            clean_query_user_cache($withdraw['uid'], 'score' . $withdraw['field']);
            if (!$rs) {
                $withdrawModel->where(array('id' => $withdraw['id']))->setField('payok', 2); //待返还状态
                continue;
            }
            D("Common/Message")->sendMessageWithoutCheckSelf($withdraw['uid'], '您的提现已被关闭，冻结' . $withdraw['score_type']['title'] . $withdraw['frozen_amount'] . $withdraw['score_type']['unit'] . '已返还到您的账户。', '【充值中心】提现关闭通知', U('recharge/index/withdrawList'), is_login());
        }
        $this->success('关闭订单成功。');

    }


    private function getConditionText($payok)
    {
        switch ($payok) {
            case 0:
                return '提现中';
            case 1:
                return '完成';
            case 2:
                return '异常，未退款';
            case -1:
                return '已被取消';
        }

    }
}
