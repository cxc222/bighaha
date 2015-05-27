<?php
define('IT_SINGLE_TEXT', 0);
define('IT_MULTI_TEXT', 1);
define('IT_SELECT', 2);
define('IT_EDITOR', 6);
define('IT_DATE', 5);
define('IT_RADIO', 3);
define('IT_PIC', 7);
define('IT_CHECKBOX', 4);


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
    $conf = D('Xdata')->lget('cat_Admin');
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



/**通过信息来检查是否可阅读
 * @param $uid 用户ID
 * @param $info_id 信息ID
 * @return int
 */
function CheckCanRead($uid, $info_id)
{
    $info = D('cat_info')->find($info_id);
    return CheckCanReadEntity($uid, $info['entity_id']);
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
    $entity = D('cat_entity')->where($map)->limit(1)->select();
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
    if(is_administrator())
        return true;
    $entity = D('cat_entity')->find($entity_id);
    if(trim($entity[$can_type])=='' || intval($entity[$can_type])==0){
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

function getImageSCById($id, $width = 180, $heigth = 180)
{
    $attach = model('Attach')->getAttachById($id);
    return getImageUrl($attach['save_path'] . $attach['save_name'], $width, $heigth, true);
}


/**调用模型字段
 * @param        $info
 * @param string $value_name
 * @return mixed
 */
function rField($info, $value_name = '')
{
    $field = $info['data'][$value_name];
    $html = W('FieldRender', array('field' => $field, 'only_value' => 1), true);
    return $html;
}

/**调用模型字段中用户字段
 * @param        $info
 * @param string $field_name
 * @return mixed
 */
function rUser($info, $field_name = '')
{
    return $info['user'][$field_name];
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