<?php


class PayModel extends Model
{
    protected $site_url = 'http://blockchain.info';
    protected $tx_url = 'rawtx';
    protected $tx_full_url;

    public function _initialize()
    {
        $this->tx_full_url = $this->site_url . '/' . $this->tx_url;
    }

    public function mkRequestTxUrl($txId)
    {
        return $this->tx_full_url . '/' . $txId;
    }

    public function makeSure($payAddress, $recAddress, $recValue, $txId)
    {
        $payAddress=trim($payAddress);
        $recAddress=trim($recAddress);

        define('UsedTxId',1);
        define('PayNotPass',2);
        define('RecNotPass',3);
        define('Passed',4);
        //define('')
        $con = get_web_page($this->mkRequestTxUrl($txId));
        $txinfo = json_decode($con['content']);
        $outs = $txinfo->out;
        $inputs = $txinfo->inputs;
        //1.检测txid唯一性
        $map['tx_id'] = $txId;
        $has_used = D('store_order')->where($map)->count();
        if ($has_used > 0) {
            //发现已经使用过了该ID
            return array(0, '已经使用过的ID。');
        }
        //2.查找付款的地址是否存在
        $isPayAddressPassed=false;
        foreach($inputs as $k=>$v){
            if($v->prev_out->addr==$payAddress)
                $isPayAddressPassed=true;
        }
        if(!$isPayAddressPassed)
        {
            return array(0,'出款地址不正确，请检查输入。');
        }
        //3.比对输出中是否存在全额付款的地址
        $recValue = number_format($recValue, 5);
        $isRecAddressPassed=false;
        foreach ($outs as $k => $v) {
            $value = number_format($v->value * 0.00000001, 5);
            if (($v->addr == $recAddress) && $value = $recValue) {
                $isRecAddressPassed=true;
            }
        }
        if(!$isRecAddressPassed)
        {
            return array(0,'付款信息不正确。请检查输入。');
        }
        else{
            return array(1,'通过验证。');
        }


    }
}