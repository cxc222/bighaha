<?php

namespace Store\Controller;

use Store\Model\CurrencyModel;
use Think\Controller;

class CenterController extends BaseController
{
    protected $messageModel;
    protected $shopModel;

    public function _initialize()
    {

        $map['uid'] = is_login();
        $map['status'] = 1;
        $count['posted'] = D('Goods')->where($map)->count();

        $count['fav'] = D('store_fav')->where($map)->count();
        $this->assign('count', $count);
        if (!is_login()) {
            $this->error('请登录后使用个人中心。');
        }
        $this->assign('tab', 'my');
        $this->messageModel = D('Message');
        $this->shopModel=D('Store/Shop');
        parent::_initialize();
    }

    /**页面 账户详情
     * @auth 陈一枭
     */
    public function detail()
    {
        $currencyModel = new CurrencyModel();
        $self = query_user(array('nickname', 'avatar128', 'space_url', 'uid'));
        $self['currency'] = $currencyModel->getCurrency();

        $orderModel = D('Order');
        $order['waiting'] = $orderModel->where(array('condition' => 0, 'uid' => get_uid()))->count();
        $order['payed'] = $orderModel->where(array('condition' => 1, 'uid' => get_uid()))->count();
        $order['response'] = $orderModel->where(array('condition' => 3, 'uid' => get_uid(), 'response_time' => 0))->count();
        $order['completed'] = $orderModel->where(array('condition' => 3, 'uid' => get_uid(), 'response_time' => array('neq', 0)))->count();

        $this->assign('order', $order);
        $this->assign('self', $self);
        $this->setTitle('账户详情——个人中心');
        $this->display();
    }

    /**页面 确认付款
     * @auth 陈一枭
     */
    public function pay()
    {
        $this->checkAuth('Store/Center/pay',-1,'你没有下单权限！');
        $map['uid'] = is_login();
        $address = D('store_order')->where($map)->order('create_time desc ')->limit(1)->select();
        $this->assign('address', $address[0]);
        $goods = $_POST;
        if (!$goods['good_id']) {
            $this->error('购物车为空，无法结算。');
        }
        foreach ($goods['good_id'] as $v) {
            $goods['goods'][]['good'] = D('Info')->getById($v);
        }
        $this->assign('goods', $goods['goods']);
        $this->setTitle('确认付款');
        $this->display();
    }

    /**页面 订单详情
     * @auth 陈一枭
     */
    public function order()
    {
        $aId = I('get.id', 0, 'intval');
        $order = D('Order')->getById($aId);
        if ($order['uid'] != get_uid() && $order['s_uid'] != get_uid()) {
            $this->error('此订单不存在。');
        }
        $this->assign('order', $order);
        $this->setTitle('订单详情');
        $this->display();
    }

    /**ajax 关闭订单
     * @auth 陈一枭
     */
    public function closeOrder()
    {
        $aOrderId = I('post.id', 0, 'intval');


        $orderModel = D('Order');

        if ($orderModel->closeOrder($aOrderId)) {
            $this->success('关闭成功。');
        } else {
            $this->error($orderModel->getError());
        }

    }

    /**页面 下单
     * @auth 陈一枭
     */
    public function pay_ok()
    {
        $this->checkAuth('Store/Center/pay',-1,'你没有下单权限！');
        $this->checkActionLimit('store_pay','store',null,is_login());
        $aGoodsId=I('post.good_id',0,'intval');
        $aCount=I('post.count',0,'intval');
        $aPost = I('post.r_pos', '', 'op_t');
        if ($aPost == '') {
            $this->error('收件地址必须填写。');
        }
        $aCode = I('post.r_code', 0, 'intval');
        if ($aCode == 0) {
            $this->error('邮编号码必填。');
        }
        $aPhone = I('post.r_phone', '', 'op_t');
        if ($aPhone == '') {
            $this->error('手机号码必须填写。');
        }
        $aName = I('post.r_name', '', 'op_t');
        if ($aName == '') {
            $this->error('收件人姓名必填');
        }
        $order['create_time'] = time();
        $order['uid'] = get_uid();
        $order['r_pos'] = $aPost;
        $order['r_code'] = $aCode;
        $order['r_phone'] = $aPhone;
        $order['condition'] = ORDER_CON_WAITFORPAY;
        $order['r_name'] = $aName;
        $rs = D('Order')->addOrder($order,$aGoodsId,$aCount);
        if ($rs[0]) {
            action_log('store_pay','store',null,is_login());
            $this->assign('jumpUrl', U('store/Center/orders'));
            $this->success('下单成功。' . $rs[1]);
        } else {
            $this->error('下单失败，错误信息：' . $rs[1]);
        }
    }

    /**页面 已买到的商品
     * @auth 陈一枭
     */
    public function orders()
    {
        $this->assign('tab', 'my');
        $time_limit = modC('TIME_LIMIT', 1800, 'store');
        $this->assign('time_limit', $time_limit);
        $map['uid'] = is_login();
        $orders = D('Order')->getList($map, 8);
        $this->assign('orders', $orders);
        $this->setTitle('已买到的商品——个人中心');
        $this->display();
    }

    /**ajax 调整价格      ***********安全性************
     * @auth 陈一枭
     */
    public function adjPrice()
    {
        $aOrderId = I('order_id', 0, 'intval');
        $aAdjCny = I('post.adj_cny', 0, 'floatval');
        if ($aOrderId == 0) {
            $this->error('修改价格失败，订单不存在。');
        }
        //确认是卖家的订单
        //确认订单状态
        $map['s_uid'] = is_login();
        $map['order_id'] = $aOrderId;
        $map['condition'] = ORDER_CON_WAITFORPAY;

        $orderModel = D('Order');
        $order = $orderModel->where($map)->find();
        if (!$order) {
            $this->error('订单不存在。修改失败。');
        }

        if ($order['total_cny'] + $aAdjCny <= 0) {
            $this->error('调价失败。调价之后价格不能小于0元。');
        }

        $rs = $orderModel->where($map)->setField('adj_cny', floatval($aAdjCny));
        if ($rs) {
            $this->success('修改成功。');
        } else {
            $this->error('修改失败。');
        }
    }

    /**页面 购物车
     * @auth 陈一枭
     */
    public function buy()
    {
        $items = D('Cart')->getLimit();

        $cny_total = 0;
        foreach ($items as $key => $v) {
            $cny_total += $v['good']['price'] * $v['count'];
        }
        $this->assign('items', $items);
        $this->assign('cny_total', $cny_total);
        $this->setTitle('购物车');
        $this->display();
    }

    /**页面 支付订单
     * @auth 陈一枭
     */
    public function payOrder()
    {
        $aOrderId = I('id', 0, 'intval');
        if ($aOrderId == 0) {
            $this->error('404');
        }

        $order = D('Order')->getById($aOrderId);
        if ($order['uid'] != is_login()) {
            $this->error('该订单不是您自己的订单。');
        } elseif (!$order) {
            $this->error('订单不存在。');
        } else {

            $self['currency'] = D('Currency')->getCurrency(is_login());
            $self['lost'] = $self['currency'] - getFinalPrice($order);
            $this->assign('self', $self);
            $this->assign('order', $order);
        }
        $this->setTitle('付款确认');
        $this->display();
    }

    /**页面 最终付款
     * @auth 陈一枭
     */
    public function payout()
    {
        $aId = I('get.id', 0, 'intval');
        if ($aId != 0) {
            $orderModel = D('Order');
            if ($orderModel->pay($aId)) {
                $this->success('支付成功。', U('store/center/order', array('id' => $aId)));
            } else {
                $this->error($orderModel->getError());
            }

        } else {
            $this->error('订单信息获取错误。');
        }
    }

    /**付款***********安全性************
     * @auth 陈一枭
     */
    public function doPay()
    {
        $aOrderId = I('post.id', 0, 'intval');
        $orderModel = D('Order');
        if ($orderModel->pay($aOrderId)) {
            $this->success('付款成功。');

        } else {
            $this->error('付款失败。' . $orderModel->getError());
        }
    }

    /**页面 已卖出的商品
     * @auth 陈一枭
     */
    public function sold()
    {
        $map['s_uid'] = is_login();
        $orders = D('Order')->getList($map, 8);
        $this->assign('orders', $orders);

        $this->setTitle('已卖出的商品——卖家中心');
        $this->display();
    }

    /**页面 评价管理
     * @auth 陈一枭
     */
    public function response()
    {
        if (I('get.s', 0, 'intval')) {
            $map['s_uid'] = is_login();
        } else {
            $map['uid'] = is_login();
        }
        $map['condition'] = ORDER_CON_DONE;
        $responses = D('store_order')->where($map)->order('response_time desc')->findPage(10);
        foreach ($responses['data'] as $k => $v) {
            $responses['data'][$k]['user'] = query_user(array('nickname', 'avatar64', 'avatar128', 'space_url'), $v['uid']);
            $responses['data'][$k]['s_user'] = query_user(array('nickname', 'avatar64', 'avatar128', 'space_url'), $v['s_uid']);
        }

        $this->assign('responses', $responses);
        $this->setTitle('评价——个人中心');
        $this->display();
    }

    /**页面 评论
     * @auth 陈一枭
     */
    public function doresponse()
    {

        $aId = I('post.order_id', 0, 'intval');
        $aResponse = I('post.response', '', 'op_t');
        $aContent = I('post.content', '', 'op_t');

        $orderModel = D('Order');
        $rs = $orderModel->response($aId, $aResponse, $aContent);
        if ($rs) {
            $this->success('修改评价成功。');
        } else {
            $this->success('修改评价失败。' . $orderModel->getError());
        }


    }

    /**ajax 确认收货
     * @auth 陈一枭
     */
    public function buyer_mksure_order()
    {

        $aId = I('post.order_id', 0, 'intval');
        $orderModel = D('Order');
        if ($orderModel->done($aId)) {
            $this->success('确认收货成功。');
        } else {
            $this->error('确认收货失败。' . $orderModel->getError());
        }

    }


    /**ajax 发货
     * @auth 陈一枭
     */
    public function send()
    {
        $aId = I('post.order_id', 0, 'intval');
        $aTransName = I('post.trans_name', '', 'op_t');
        $aTransCode = I('post.trans_code', '', 'op_t');

        $orderModel = D('Order');
        $order = $orderModel->find($aId);
        if ($order['s_uid'] != is_login()) {
            $this->error('抱歉，您没有操作此订单的权限。');
        }
        $order['trans_name'] = $aTransName;
        $order['trans_code'] = $aTransCode;
        $order['condition'] = ORDER_CON_WAITFORSURE;
        $order['trans_time'] = time();
        $rs = $orderModel->save($order);
        if ($rs) {
            $this->messageModel->sendMessage($order['uid'], $content = '【微店】订单.' . $order['id'] . '商家已发货！', $title = '微店订单发货通知', U('store/center/orders'), is_login());
            $this->success('发货成功。');
        } else {
            $this->error('发货失败。' . $orderModel->getError());
        }
    }


    /**ajax 购物车添加商品
     * @auth 陈一枭
     */
    public function cart_add_item()
    {
        $item = $_POST;
        D('Cart')->addItem($item);
        exit(json_encode(array('status' => 1)));
    }

    /**ajax 购物车移除商品
     * @auth 陈一枭
     */
    public function cartRemoveItem()
    {
        $aGoods_id = I('post.id', 0, 'intval');
        if ($aGoods_id == 0) {
            $this->error('商品不存在。');
        }
        D('Cart')->removeItem($aGoods_id);
        $this->success('删除商品成功。');
    }

    public function _cart_set_item_count()
    {
        D('Cart')->setItemCount($_POST['good_id'], $_POST['count']);
        exit(json_encode(array('status' => 1)));
    }


    public function selling()
    {
        $this->assignShop();
        $this->setTitle('销售中的商品——卖家中心');
        $this->display();
    }


    /**页面 个人商品收藏列表
     * @auth 陈一枭
     */
    public function fav()
    {
        $this->setTitle('我的收藏——个人中心');

        //获取该用户发布的全部用户组
        $t_map['uid'] = get_uid();
        $list = D('store_fav')->where($t_map)->findPage(4);
        $goodsModel = D('Goods');
        foreach ($list['data'] as &$li) {
            $li = $goodsModel->getById($li['info_id']);
        }
        unset($li);
        $this->assign('list', $list);
        $this->display();

    }

    /**表单 创建店铺页面
     * @auth 陈一枭
     */
    public function createShop()
    {
        $this->checkAuth('Store/Center/createShop',-1,'你没有开店权限！');
        if (IS_POST) {
            $aId = I('post.id', '0', 'intval');
            if ($aId != 0) {
                $shop = $this->shopModel->find($aId);
                if ($shop['uid'] != get_uid()) {
                    $this->error('抱歉，您无编辑该店铺的权限。');
                }
                $this->checkActionLimit('store_edit','store',$aId,get_uid());
            }
            $aTitle = I('post.title', '', 'op_t,trim');
            $aSummary = I('post.summary', '', 'op_t,trim');
            $aLogo = I('post.logo', 0, 'intval');
            $aPosition = I('post.position', '', 'op_t');

            $shop['id'] = $aId;
            $shop['title'] = $aTitle;
            $shop['summary'] = $aSummary;
            $shop['logo'] = $aLogo;
            $shop['position'] = $aPosition;
            $shop['uid'] = is_login();
            $shop = $this->shopModel->create($shop);
            if (!$shop) {
                $this->error($this->shopModel->getError());
            }
            if ($aId) {
                $this->shopModel->save($shop);
                action_log('store_edit','store',$aId,get_uid());
                $this->success('店铺保存设置成功。');
            } else {
                $this->shopModel->add($shop);
                $this->success('店铺创建成功。');
            }
        } else {
            $shop = $this->shopModel->where(array('uid' => is_login()))->find();
            $this->assign('shop', $shop);
            $this->setTitle('创建店铺——卖家中心');
            $this->display();
        }
    }


    /**表单 发布商品页面
     * @auth 陈一枭
     */
    public function post()
    {
        $this->assign('tab', 'my');

        $aInfoId = I('get.info_id', 0, 'intval');
        $aEntityId = I('get.entity_id', 0, 'intval');


        $this->assign('info_id', $aInfoId);
        $this->setTitle('发布商品');
        if (!(is_login())) {
            $this->error('请登陆后发布。');
        }
        /*得到实体信息*/
        if ($aEntityId != 0) {
            $entity = D('store_entity')->find($aEntityId);
        }
        $data['entity'] = $entity;
        /*检查是否在可发布组内*/
        $can_post = CheckCanPostEntity(is_login(), $entity['id']);
        if (!$can_post) {
            $this->error('对不起，您无权发布。');
        }
        /*得到实体信息end*/


        if ($aInfoId != 0) {
            $goods = D('Goods')->getById($aInfoId);
            if (!$goods) {
                $this->error('404不存在。');
            }
            $this->checkAuth('Store/Center/postEdit',$goods['uid'],'你没有编辑该商品的权限！');
            $this->assign('info', $goods);
        }else{
            $this->checkAuth('Store/Center/postAdd',-1,'你没有发布商品的权限！');
        }
        /*检查是否在可发布组内end*/

        /*构建发布模板*/
        $tpl = '';

        $map_field['entity_id'] = $entity['id'];
        $map_field['status'] = 1;
        $fields = D('store_field')->where($map_field)->order('sort desc')->select();

        $my_shop = $this->shopModel->getShopByUid(is_login());
        if ($my_shop != false && $entity['name'] == 'shop') {
            $aInfoId = $my_shop['id'];

        } else {
            $this->assign('shop_id', $my_shop['id']);
        }


        if ($data['entity']['can_over']) {
            $over_time = array('input_type' => IT_DATE, 'can_empty' => 0, 'name' => 'over_time', 'tip' => '请输入截止日期', 'alias' => '截止日期', 'args' => 'min=1&error=请选择日期');
            $tpl .= R('InputRender/render', array(array('field' => $over_time, 'info_id' => $aInfoId, $tpl)), 'Widget');
        }


        foreach ($fields as $v) {
            $tpl .= R('InputRender/render', array(array('field' => $v, 'info_id' => $aInfoId)), 'Widget');
        }
        $data['tpl'] = $tpl;
        /*构建发布模板end*/

        $this->assign($data);

        $this->assign('entity_id', $entity['id']);
        $this->assign('cats', D('Store/Category')->getCatAll());
        if (is_null($my_shop)) {
            $this->error('您还没有创建店铺，无法发布商品。即将跳转到创建店铺页面。', U('store/Index/post', array('name' => 'shop')));
        }
        $this->assign('myshop', $my_shop);
        $this->display('post_good');
    }


    /**表单提交页面 添加商品
     * 执行添加信息
     */
    public function doAddInfo()
    {
        unset($_POST['__hash__']);

        $aEntityId = I('post.entity_id', 0, 'intval');
        $aInfoId = I('post.info_id', 0, 'intval');
        $aCat1 = I('post.cat1', 0, 'intval');
        $aCat2 = I('post.cat2', 0, 'intval');
        $aCat3 = I('post.cat3', 0, 'intval');
        $aTitle = I('post.title', '', 'op_t');
        $aShopId = I('post.shop_id', 0, 'intval');
        $aPrice = I('post.price', 0, 'floatval');
        if ($aPrice <= 0) {
            $this->error('价格必须大于0');
        }
        $aCoverId = I('post.cover_id', 0, 'intval');
        if ($aCoverId == 0) {
            $this->error('商品主图必须上传。');
        }
        $aGallary = I('post.gallary', array(), 'intval');

        if (count($aGallary) > 9) {
            $this->error('相册图片不能超过9张！');
        }
        $aTransFee = I('post.trans_fee', 0, 'intval');
        $aDes = I('post.des', '', 'op_h');
        if ($aDes == '') {
            $this->error('商品描述必填。');
        }


        $entity = $this->requireCanPost($aEntityId);

        $info = D('Goods')->find($aInfoId);
        $info['title'] = $aTitle;
        $info['cat1'] = $aCat1;
        $info['cat2'] = $aCat2;
        $info['cat3'] = $aCat3;
        $info['price'] = $aPrice;
        $info['cover_id'] = $aCoverId;
        $info['trans_fee'] = $aTransFee == 1 ? 1 : 0;

        $info['gallary'] = encodeGallary($aGallary); //implode(',', $aGallary);

        $info['des'] = $aDes;
        if ($info['title'] == '') {
            $this->error('必须输入标题');

        }
        if (mb_strlen($info['title'], 'utf-8') > 40) {
            $this->error('标题过长。');
        }


        if ($aInfoId != 0) {
            $this->checkAuth('Store/Center/postEdit',$info['uid'],'你没有编辑该商品的权限！');
            $this->checkActionLimit('goods_edit','store',$aInfoId,is_login());
            $info['update_time'] = time();
            //保存逻辑
            $info['id'] = $aInfoId;
            D('Goods')->save($info);
            $rs_info = $info['id'];
        } else {
            $this->checkAuth('Store/Center/postAdd',-1,'你没有发布商品的权限！');
            $this->checkActionLimit('goods_add','store',null,is_login());
            $info['create_time'] = time();
            //新增逻辑
            $info['entity_id'] = $aEntityId;
            $info['uid'] = is_login();
            if ($entity['need_active'] && !is_administrator()) {
                $info['status'] = 2;
            } else {
                $info['status'] = 1;
            }

            //如果是商品就新增字段
            if ($entity['name'] == 'good') {
                $info['shop_id'] = $aShopId;
            }
            $rs_info = D('Goods')->add($info);
        }

        $rs_data = 1;

        if ($rs_info != 0) //如果info保存成功
        {

            if ($aInfoId != 0) {
                action_log('goods_edit','store',$aInfoId,is_login());
                $map_data['info_id'] = $aInfoId;
                D('Data')->where($map_data)->delete();
            }else{
                action_log('goods_add','store',$rs_info,is_login());
            }

            $dataModel = D('Data');
            foreach ($_POST as $key => $v) {
                $band = 'entity_id,over_time,ignore,info_id,title,__hash__,shop_id,cat1,cat2,cat3,price,cat,cover_id,gallary,trans_fee,des,file';
                if (!in_array($key, explode(',', $band))) {
                    if (is_array($v)) {
                        $rs_data = $rs_data && $dataModel->addData($key, implode(',', $v), $rs_info, $aEntityId);
                    } else {
                        $v = op_h($v);
                        $rs_data = $rs_data && $dataModel->addData($key, $v, $rs_info, $aEntityId);
                    }
                }
                if ($rs_data == 0) {
                    $this->error($dataModel->getError());
                }
            }
            if ($rs_info && $rs_data) {
                $this->assign('jumpUrl', U('store/Index/info', array('info_id' => $rs_info)));

                if ($entity['need_active']) {
                    // $this->success('发布成功。请耐心等待管理员审核。通过审核后该信息将出现在前台页面中。');
                } else {
                    if ($entity['show_nav']) {
                        $postUrl = U('store/index/info', array('info_id' => $rs_info), null, true);
                    }
                }
            }
        } else {
            $this->error('发布失败。');
        }

        if ($entity['name'] == 'shop') {
            if ($rs_info == 0) {
                $this->error('新增店铺失败。');
            } elseif ($rs_data == 0) {
                if (I('post.info_id', 0, 'intval')) {
                    $this->success('修改店铺信息失败。');
                } else {
                    $this->success('店铺创建成功，但相关信息添加失败，请联系管理员。');
                }
            } else {
                if (I('post.info_id', 0, 'intval')) {
                    $this->success('修改店铺信息成功。');
                } else {
                    $this->success('创建店铺成功。请耐心等待管理员审核。通过审核后即可上传商品。', U('store/center/post', array('name' => 'shop')));
                }
            }
            return;
        }

        if ($entity['name'] == 'good') {
            if ($rs_info && $rs_data) {
                $entity = D('store_entity')->find($info['entity_id']);
                if ($entity['need_active']) {
                    $this->success('发布成功。请耐心等待管理员审核。通过审核后该信息将出现在前台页面中。');
                } else {
                    if ($entity['show_nav']) {
                        if (D('Common/Module')->isInstalled('Weibo')) { //安装了微博模块
                            $weiboModel = D('Weibo/Weibo');
                            $weiboModel->addWeibo("我上架了一个新的 " . $entity['alias'] . " 【" . $info['title'] . "】：" . $postUrl);
                        }
                    }
                    $this->success('发布商品成功。即将跳转到商品页面。点击<a href="' . U('store/center/post', array('name' => 'good')) . '">继续发布</a>。', U('store/Index/info', array('info_id' => $rs_info)), 5);
                }
            } else {
                $this->error('商品发布失败，请联系管理员。。');
            }
        }

    }

    /**私有 发布权限
     * @param $aEntityId
     * @return mixed
     * @auth 陈一枭
     */
    private function requireCanPost($aEntityId)
    {
        $entity = D('store_entity')->find($aEntityId);
        /**权限认证**/
        $can_post = CheckCanPostEntity(is_login(), $aEntityId);
        if (!$can_post) {
            $this->error('对不起，您无权发布。');
            return $entity;
        }
        return $entity;
        /**权限认证end*/
    }

    private function assignShop()
    {
        $shop = $this->shopModel->where(array('uid' => is_login()))->find();
        if (!$shop) {
            $this->error('您还没有创建店铺，无法查看出售中的商品。即将跳转到创建店铺页面。', U('store/Index/post', array('name' => 'shop')));
        }
        $this->assign('shop', $shop);
    }

}