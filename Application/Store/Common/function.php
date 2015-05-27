<?php
define('IT_SINGLE_TEXT', 0);
define('IT_MULTI_TEXT', 1);
define('IT_SELECT', 2);
define('IT_EDITOR', 6);
define('IT_DATE', 5);
define('IT_RADIO', 3);
define('IT_PIC', 7);
define('IT_CHECKBOX', 4);
define('IT_NUM', 8);

define('ORDER_CON_WAITFORPAY', 0); //等待付款
define('ORDER_CON_WAITFORSEND', 1); //等待发货
define('ORDER_CON_WAITFORSURE', 2); //等待确认收货
define('ORDER_CON_DONE', 3); //交易成功
define('ORDER_CON_CLOSED', -1); //关闭
define('ORDER_CON_TIMEOUT', 5); //超时

function checkShopHasGoods($uid)
{
    $myshop = D('Shop')->getShopByUid($uid);
    $shop_id = $myshop['id'];
    return D('Goods')->where('shop_id=' . $shop_id)->count();
}

function checkHasCreatedShop($uid)
{
    $my_shop = D('Shop')->getShopByUid($uid);
    return $my_shop;
}

/**
 * google api 二维码生成【QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式】
 * @param string $chl 二维码包含的信息，可以是数字、字符、二进制信息、汉字。不能混合数据类型，数据必须经过UTF-8 URL-encoded.如果需要传递的信息超过2K个字节，请使用POST方式
 * @param int    $widhtHeight 生成二维码的尺寸设置
 * @param string $EC_level 可选纠错级别，QR码支持四个等级纠错，用来恢复丢失的、读错的、模糊的、数据。
 *                           L-默认：可以识别已损失的7%的数据
 *                           M-可以识别已损失15%的数据
 *                           Q-可以识别已损失25%的数据
 *                           H-可以识别已损失30%的数据
 * @param int    $margin 生成的二维码离图片边框的距离
 */
function generateQRfromGoogle($chl, $widhtHeight = '150', $EC_level = 'L', $margin = '0')
{
    $chl = urlencode($chl);
    echo '<img src="http://chart.apis.google.com/chart?chs=' . $widhtHeight . 'x' . $widhtHeight . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $chl . '" alt="QR code" widhtHeight="' . $widhtHeight . '" widhtHeight="' . $widhtHeight . '"/>';
}

/**获取订单状态
 * @param $condition 状态码，取自数据库，整数型
 * @return string 文字形式的提示信息
 */
function getOrderCondition($condition)
{
    switch ($condition) {
        case ORDER_CON_DONE:
            return '<b style="color: green">交易成功</b>';
        case ORDER_CON_CLOSED:
            return '<b style="color: grey">交易关闭</b>';
        case ORDER_CON_WAITFORSURE:
            return '等待确认收货';
        case ORDER_CON_TIMEOUT:
            return '超时关闭';
        case ORDER_CON_WAITFORPAY:
            return '等待买家付款';
        case ORDER_CON_WAITFORSEND:
            return '等待发货';
    }
}

/**通过item[] 形式取出调价后的实际价格
 * @param $good
 * @return mixed
 */
function getFinalPrice($order)
{
    return $order['total_cny'] + $order['adj_cny'];
}



/**获取某个时间戳到现在的经过时间
 * @param $time
 * @return string
 */
function getDaysPass($time)
{
    return number_format(($time - time()) / (24 * 60 * 60) + 1, 0);
}


/**正则表达式获取html中首张图片
 * @param $str_img
 * @return mixed
 */
function getpic($str_img)
{
    preg_match_all("/<img.*\>/isU", $str_img, $ereg); //正则表达式把图片的整个都获取出来了
    $img = $ereg[0][0]; //图片
    $p = "#src=('|\")(.*)('|\")#isU"; //正则表达式
    preg_match_all($p, $img, $img1);
    $img_path = $img1[2][0]; //获取第一张图片路径
    return $img_path;
}


/**配置函数，用于替换原有的C
 * @param $name
 * @return array
 */
function catC($name)
{
    $conf = D('Xdata')->lget('store_Admin');
    foreach ($conf as $v) {
        if (isset($v[$name])) {
            return arrayComplie($v[$name]);
        } else {
            continue;
        }
    }

    return arrayComplie($conf);
}

/**把逗号分隔文本分解为数组
 * @param $data
 * @return array
 */
function arrayComplie($data)
{
    $rs = explode(',', $data);
    if (count($rs) == 1) {
        return $data;
    }
    return $rs;
}

/**获取配置图片
 * @param $name 配置项名
 * @return bool|string
 */
function catCP($name)
{
    $conf = D('Xdata')->lget('mag_Admin');
    foreach ($conf as $v) {
        if (isset($v[$name])) {
            return getImageUrlByAttachId($v[$name]);
        }
    }


}


/**通过信息来检查是否可阅读
 * @param $uid 用户ID
 * @param $goods_id 信息ID
 * @return int
 */
function CheckCanRead($uid, $goods_id)
{
    $goods = D('Goods')->find($goods_id);
    return CheckCanReadEntity($uid, $goods['entity_id']);
}

/**通过实体ID来检查是否可阅读
 * @param $uid
 * @param $id
 * @return int
 */
function CheckCanReadEntity($uid, $entity_id)
{

    return CheckCan($uid, $entity_id, 'can_read_gid');
}

function CheckCanPostEntityN($uid, $entity_name)
{
    $map['name'] = $entity_name;
    $entity = D('store_entity')->where($map)->limit(1)->select();
    return CheckCan($uid, $entity[0]['id'], 'can_post_gid');
}

/**通过实体ID来检查是否可发布
 * @param $uid
 * @param $entity_id
 * @return int
 */
function CheckCanPostEntity($uid, $entity_id)
{

    return CheckCan($uid, $entity_id, 'can_post_gid');
}

/**通用检查权限方法
 * @param $uid
 * @param $entity_id
 * @param $can_type
 * @return int
 */
function CheckCan($uid, $entity_id, $can_type)
{
    if (is_administrator())
        return true;
    $entity = D('store_entity')->find($entity_id);
    if (trim($entity[$can_type]) == '') {
        return true;
    }
    $gids = explode(',', $entity[$can_type]);

    $group_result = D('auth_group_access')->field('group_id')->where(array('uid' => $uid))->select();

    $user_groups = getSubByKey($group_result, 'group_id');
    $has = array_intersect($gids, $user_groups);

    if (count($has))
        return true;
    else
        return false;

}

/**解析选项
 * @param $option_str
 * @return array
 * @auth 陈一枭
 */
function parseOption($option_str)
{
    $option_str = str_replace("\r", '', $option_str);
    $values = explode("\n", $option_str);
    foreach ($values as &$v) {
        $v = trim($v);
    }
    return $values;
}

/**解析相册
 * @param $gallary
 * @return array
 * @auth 陈一枭
 */
function decodeGallary($gallary)
{
    $gallary = json_decode($gallary, true);
    foreach ($gallary as $g) {
        $gallary_array[] = array('id' => $g['id'], 'img' => getThumbImageById($g['id'], 80, 80));
    }
    return $gallary_array;
}

/**编译相册
 * @param $gallary
 * @auth 陈一枭
 */
function encodeGallary($gallary)
{
    foreach ($gallary as $g) {
        $gallary_array[] = array('id' => $g);
    }

    return json_encode($gallary_array);

}

/**在模板中统一获取到聊天按钮
 * @auth 陈一枭
 */
function getTalkBtn(){

}

/**
 * 获取微店交易积分类型和值
 * @param $uid
 * @return mixed
 * @author 郑钟良<zzl@ourstu.com>
 */
function get_currency($uid)
{
    !$uid&&$uid=is_login();
    $score_id=modC('CURRENCY_TYPE',4,'Store');
    $scoreModel=D('Ucenter/Score');
    $currency=$scoreModel->getType($score_id);
    $currency['num']=$scoreModel->getUserScore($uid,$score_id);
    return $currency;
}