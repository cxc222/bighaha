<?php


namespace Mob\Widget;

use Think\Action;


class UserMenuWidget extends Action
{
    public function index()
    {

            $uid=is_login();

            $user['user']=query_user(array('nickname','avatar64'),$uid);
            $this->assign('user',$user);
            $this->display(T('Mob@UserMenu/index'));


    }
}