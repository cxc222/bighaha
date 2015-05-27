<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 4/4/14
 * Time: 9:29 AM
 */

namespace App\Controller;

use Think\Controller;
use Weibo\Api\WeiboApi;

class ShopController extends BaseController
{
    /* 获取当前分类信息 */
    public function getShopCategory()
    {
        $Category = D('Shop/ShopCategory')->getTree();
        $list = array('list' => $Category);
        $this->apiSuccess('返回成功', $list);
    }

    /* 获取商品信息 */
    public function  getgoods()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aId = I('category_id', 0, 'intval');
        $map['status'] = 1;
        $aId && $map['category_id'] = $aId;

        $goods = D('Shop/Shop')->where($map)->page($aPage, $aCount)->order('createtime desc')->select();

        $list = array('list' => $goods);
        $this->apiSuccess('返回成功', $list);
    }

    /* 兑换商品 */
    public function  getbuygoods()
    {
        //if (!is_login()) {
           // exit($this->apiError('请登录后再兑换。'));
      //  }
        $aId = I('goods_id', 0, 'intval');
        $aMessage_uid = intval(I('uid'));
        $aAddress=I('address', 0, 'intval');
        $aZipcode=I('zipcode', 0, 'intval');
        $aName=I('name', 0,  'op_h');
        $map['status'] = 1;
        $aGoods_id = I('goods_id');
        $aId && $map['$aGoods_id'] = $aId;
        $goods_num=D('Shop/Shop')->where($map)->field('goods_num')->find();
        dump($goods_num);

        if (!$goods_num) {
            exit ($this->apiError('商品已经没有了'));

        } else {
            $shopbuy['create_time'] = time();
            }
        }









}
