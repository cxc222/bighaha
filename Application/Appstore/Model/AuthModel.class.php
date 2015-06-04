<?php
/**
 * 所属项目 cox.
 * 开发者: 陈一枭
 * 创建日期: 8/5/14
 * 创建时间: 10:12 AM
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Appstore\Model;

/**appstore专用权限检测模型,每一种权限都需要在此进行验证
 * Class AuthModel
 * @package Appstore\Model
 * @auth 陈一枭
 */
class AuthModel
{


    /*———————————————Private区，区内函数仅内部调用，不能在外部调用——————————————————*/
    /**是否是商品所有者
     * @param int $uid
     * @auth 陈一枭
     */
    public function isGoodOwner($uid = 0)
    {
        $this->isOwner('AppstoreGoods', $uid);
    }


    /**是否为管理员
     * @param $uid
     * @return bool
     * @auth 陈一枭
     */
    private function isAdmin($uid)
    {
        $this->getUid($uid);
        return is_administrator($uid);
    }

    /**获取uid
     * @param $uid
     * @return int
     * @auth 陈一枭
     */
    private function getUid($uid)
    {
        if (!$uid) {
            return is_login();
        }
        return $uid;
    }


    /**是否为某个表记录的所有者
     * @param     $table
     * @param int $uid
     * @return mixed
     * @auth 陈一枭
     */
    private function isOwner($table, $uid = 0)
    {
        $this->getUid($uid);
        return D($table)->where(array('uid' => intval($uid)))->count();
    }
    /*———————————————Private区，区内函数仅内部调用，不能在外部调用end—————————————————*/
} 