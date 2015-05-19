<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;

use Ucenter\Widget\RegStepWidget;

/**
 * Class UserConfigController  后台用户配置控制器
 * @package Admin\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class UserConfigController extends AdminController
{

    public function index()
    {

        $admin_config = new AdminConfigBuilder();
        $data = $admin_config->handleConfig();

        $mStep = A('Ucenter/RegStep', 'Widget')->mStep;
        $step = array();
        foreach ($mStep as $key => $v) {
            $step[] = array('data-id' => $key, 'title' => $v);
        }



        $default = array(array('data-id' => 'disable', 'title' => '禁用', 'items' => $step), array('data-id' => 'enable', 'title' => '启用', 'items' => array()));
        //$default=array('禁用'=>$step,'启用并可跳过'=>array(),'启用但不可跳过'=>array());
        $data['REG_STEP'] = $admin_config->parseKanbanArray($data['REG_STEP'],$step,$default);

            empty($data['LEVEL']) && $data['LEVEL'] = <<<str
0:Lv1 实习
50:Lv2 试用
100:Lv3 转正
200:Lv4 助理
400:Lv 5 经理
800:Lv6 董事
1600:Lv7 董事长
str;
        empty($data['OPEN_QUICK_LOGIN']) && $data['OPEN_QUICK_LOGIN'] = 0;





        $admin_config->title('用户配置')
            ->keyCheckBox('REG_SWITCH', '注册开关', '允许使用的注册选项,全不选即为关闭注册', array('username' => '用户名', 'email' => '邮箱', 'mobile' => '手机'))
            ->keyRadio('EMAIL_VERIFY_TYPE', '邮箱验证类型', '邮箱验证的类型', array(0 => '不验证', 1 => '注册后发送激活邮件', 2 => '注册前发送验证邮件'))
            ->keyRadio('MOBILE_VERIFY_TYPE', '手机验证类型', '手机验证的类型', array(0 => '不验证', 1 => '注册前发送验证短信'))
            ->keyText('NEW_USER_FOLLOW', '新用户关注', '输入用户id，多个用户以‘,’分割')
            ->keyText('NEW_USER_FANS', '新用户粉丝', '输入用户id，多个用户以‘,’分割')
            ->keyText('NEW_USER_FRIENDS', '新用户好友', '输入用户id，多个用户以‘,’分割')

            ->keyKanban('REG_STEP', '注册步骤', '注册后需要进行的步骤')

            ->keyCheckBox('REG_CAN_SKIP', '注册步骤是否可跳过', '勾选为可跳过，默认不可跳过',$mStep)

            ->keyEditor('REG_EMAIL_VERIFY', '邮箱验证模版', '用于进行邮箱的验证','all')
            ->keyEditor('REG_EMAIL_ACTIVATE', '邮箱激活模版', '用于进行用户的激活')

            ->keyText('SMS_HTTP', '短信平台HTTP', '短信平台HTTP')
            ->keyText('SMS_UID', '短信平台帐号', '短信平台帐号')
            ->keyText('SMS_PWD', '短信平台密码', '短信平台密码')
            ->keyTextArea('SMS_CONTENT', '短信内容', '短信内容')
            ->keyTextArea('LEVEL', '等级配置', '每行一条，名称和积分之间用冒号分隔')

            ->keyRadio('OPEN_QUICK_LOGIN','快捷登录','默认关闭，开启后用户登录方式更换成快捷登录！', array(0 => '关闭', 1 => '开启'))

            ->group('注册配置', 'REG_SWITCH,EMAIL_VERIFY_TYPE,MOBILE_VERIFY_TYPE,REG_STEP,REG_CAN_SKIP,NEW_USER_FOLLOW,NEW_USER_FANS,NEW_USER_FRIENDS')
            ->group('登录配置', 'OPEN_QUICK_LOGIN')
            ->group('邮箱验证模版', 'REG_EMAIL_VERIFY')
            ->group('邮箱激活模版', 'REG_EMAIL_ACTIVATE')
            ->group('短信配置', 'SMS_HTTP,SMS_UID,SMS_PWD,SMS_CONTENT')
            ->group('基础设置', 'LEVEL')
            ->buttonSubmit('', '保存')->data($data);
        $admin_config->display();
    }
}
