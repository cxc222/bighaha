<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Core\Controller;

use Think\Controller;

/**
 * Class PublicController  公共控制器
 * @package Core\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class PublicController extends Controller
{

    /**关注某人
     * @param int $uid
     * @auth 陈一枭
     */
    public function follow($uid = 0)
    {
        if (!is_login()) {
            $this->ajaxReturn(array('status' => 0, 'info' => '请登陆'));
        }

        if (D('Follow')->follow($uid)) {
            $this->ajaxReturn(array('status' => 1, 'info' => '关注成功'));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => '关注失败'));
        }
    }

    /**取消对某人的关注
     * @param int $uid
     * @auth 陈一枭
     */
    public function unfollow($uid = 0)
    {
        if (!is_login()) {
            $this->ajaxReturn(array('status' => 0, 'info' => '请登陆'));
        }

        if (D('Follow')->unfollow($uid)) {
            $this->ajaxReturn(array('status' => 1, 'info' => '取消关注成功'));
        } else {
            $this->ajaxReturn(array('status' => 0, 'info' => '取消关注失败'));
        }
    }


    /**
     * atWhoJson
     * @author:陈一枭
     */
    public function atWhoJson()
    {
        exit(json_encode($this->getAtWhoUsersCached()));
    }

    private function getAtWhoUsersCached()
    {
        $cacheKey = 'weibo_at_who_users_' . get_uid();
        $atusers = S($cacheKey);
        if (empty($atusers)) {
            $atusers = $this->getAtWhoUsers();
            S($cacheKey, $atusers, 600);
        }
        return $atusers;
    }

    /**
     * getAtWhoUsers  获取@列表
     * @return array
     * @author:陈一枭
     */
    private function getAtWhoUsers()
    {
        //获取能AT的人，UID列表
        $uid = get_uid();
        $follows = D('Follow')->where(array('who_follow' => $uid, 'follow_who' => $uid, '_logic' => 'or'))->limit(999)->select();
        $uids = array();
        foreach ($follows as &$e) {
            $uids[] = $e['who_follow'];
            $uids[] = $e['follow_who'];
        }
        unset($e);
        $uids = array_unique($uids);

        //加入拼音检索
        $users = array();
        foreach ($uids as $uid) {
            $user = query_user(array('nickname', 'id', 'avatar32'), $uid);
            $user['search_key'] = $user['nickname'] . D('PinYin')->Pinyin($user['nickname']);
            $users[] = $user;
        }

        //返回at用户列表
        return $users;
    }

}
