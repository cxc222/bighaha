<?php


namespace Mob\Controller;

use Think\Controller;


class IndexController extends Controller
{


    public function index($page = 1, $issue_id = 0)
    {
        if(is_login()){
            $this->redirect('Mob/Weibo/index');
        }else{
            $ph = array();
            check_reg_type('username') && $ph[] = L("username");
            check_reg_type('email') && $ph[] = L("email");
            check_reg_type('mobile') && $ph[] = L("phone_number");
            $this->assign('ph', implode('/', $ph));
            $this->display();
        }
    }

}