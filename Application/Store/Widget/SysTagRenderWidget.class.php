<?php
namespace Store\Widget;
use Think\Controller;

/**
 * Created by JetBrains PhpStorm.
 * User: 95
 * Date: 13-7-5
 * Time: 下午2:14
 * To change this template use File | Settings | File Templates.
 */
class SysTagRenderWidget extends Controller
{
    public function render($data)
    {
        $tpl_section = $data['tpl'];
        $info = $data['info'];
        $tpl_section = $this->handle($tpl_section, '{$[title]}', op_t($info['title']));
        $tpl_section = $this->handle($tpl_section, '{$[cTime]}', friendlyDate($info['create_time']));
        $tpl_section = $this->handle($tpl_section, '{$[cTimeD]}', date('n j', $info['create_time']));
        $tpl_section = $this->handle($tpl_section, '{$[url]}', U('Cat/Index/info', array('info_id' => $info['id'])));


        /*用户标签*/
        $user = query_user(array('nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'space_url'), $info['uid']);
        $tpl_section = $this->handle($tpl_section, '{$[user_avatar32]}', $user['avatar32']);
        $tpl_section = $this->handle($tpl_section, '{$[user_avatar64]}', $user['avatar64']);
        $tpl_section = $this->handle($tpl_section, '{$[user_avatar128]}', $user['avatar128']);
        $tpl_section = $this->handle($tpl_section, '{$[user_avatar256]}', $user['avatar256']);
        $tpl_section = $this->handle($tpl_section, '{$[user_nickname]}', $user['nickname']);
        $tpl_section = $this->handle($tpl_section, '{$[user_space_url]}', $user['space_url']);
        $tpl_section = $this->handle($tpl_section, '{$[user_uid]}', $user['uid']);
        /*用户标签end*/

        $tpl_section = $this->handle($tpl_section, '{$[fav_btn]}', R('FavBtn/render', array(array('info' => $info)), 'Widget'));


        $entity = D('store_entity')->find($info['entity_id']);
        if ($entity['can_over']) {
            $tpl_section = $this->handle($tpl_section, '{$[over_time]}', date('Y-m-d', $info['over_time']));
        } else {
            $tpl_section = $this->handle($tpl_section, '{$[over_time]}', '');
        }
        return $tpl_section;
    }

    /**替换文本
     * @param $rs 原字符串
     * @param $name 被替换的文字
     * @param $value 用于替换的文字
     * @return mixed
     */
    public function handle($rs, $name, $value)
    {

        $rs = str_replace($name, $value, $rs);
        return $rs;
    }
}