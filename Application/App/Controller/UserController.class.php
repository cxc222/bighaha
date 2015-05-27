<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 1/16/14
 * Time: 9:40 PM
 */

namespace App\Controller;



class UserController extends BaseController
{
    /*    public function register($username, $password)
        {
            //调用用户中心
            $api = new UserApi();
            $uid = $api->register($username, $username, $password, $username . '@username.com'); // 邮箱为空
            if ($uid <= 0) {
                $message = $this->getRegisterErrorMessage($uid);
                $code = $this->getRegisterErrorCode($uid);
                $this->apiError( $message,$code);
            }
            //返回成功信息
            $extra = array();
            $extra['uid'] = $uid;
            $this->apiSuccess("注册成功", $extra);
        }*/
    public function beforeRegister()
    {
        $aCode = I('code', '', 'op_t');

            $this->checkRegisterType($aCode);

    }

    public function register()
    {


        if (!modC('REG_SWITCH', '', 'USERCONFIG')) {
            $this->apiError('注册已关闭');
        }


        //获取参数
        $aUsername = $username = I('username', '', 'op_t');
        $aNickname = I('nickname', '', 'op_t');
        $aPassword = I('password', '', 'op_t');
        $aVerify = I('verify', '', 'op_t');
        $aRegVerify = I('reg_verify', 0, 'intval');
        $aRegType = I('reg_type', '', 'op_t');
        $aRole = I('role', '', 'op_t');
        $aType = I('type', '', 'op_t');


        //注册用户

        $return = check_action_limit('reg', 'ucenter_member', 1, 1, true);
        if ($return && !$return['state']) {
            $this->apiError($return['info']);
        }
        /* 检测验证码 */
        if (check_verify_open('reg')) {
            if (!check_verify($aVerify)) {
                $this->apiError('验证码输入错误。');
            }
        }

        if (!$aRole) {
            $this->apiError('请选择角色。');
        }

        if (($aRegType == 'mobile' && modC('MOBILE_VERIFY_TYPE', 0, 'USERCONFIG') == 1) || (modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 2 && $aRegType == 'email')) {

            if (!D('Verify')->checkVerify($aUsername, $aRegType, $aRegVerify, 0)) {
                $str = $aRegType == 'mobile' ? '手机' : '邮箱';
                $this->apiError($str . '验证失败');
            }
        }
        $aUnType = 0;
        //获取注册类型

        check_username($aUsername, $email, $mobile, $aUnType);
        if ($aRegType == 'email' && $aUnType != 2) {
            $this->apiError('邮箱格式不正确');
        }
        if ($aRegType == 'mobile' && $aUnType != 3) {
            $this->apiError('手机格式不正确');
        }
        if ($aRegType == 'username' && $aUnType != 1) {
            $this->apiError('用户名格式不正确');
        }
        if (!check_reg_type($aUnType)) {
            $this->apiError('该类型未开放注册。');
        }

        $aCode = I('post.code', '', 'op_t');

        if (!$this->checkInviteCode($aCode)) {
            $this->apiError('非法邀请码！');
        }


        /* 注册用户 */
        $uid = UCenterMember()->register($aUsername, $aNickname, $aPassword, $email, $mobile, $aUnType);


        if (0 < $uid) {

            //注册成功
            $this->initInviteUser($uid, $aCode, $aRole);
            $this->initRoleUser($aRole, $uid); //初始化角色用户
            if (modC('EMAIL_VERIFY_TYPE', 0, 'USERCONFIG') == 1 && $aUnType == 2) {
                set_user_status($uid, 3);
                $verify = D('Verify')->addVerify($email, 'email', $uid);
                dump($verify);
                $res = $this->sendActivateEmail($email, $verify, $uid); //发送激活邮件
                $this->apiSuccess('注册成功，请登录邮箱进行激活');
            }

            $uid = UCenterMember()->login($username, $aPassword, $aUnType); //通过账号密码取到uid
            D('Member')->login($uid, false, $aRole);
            //登陆

            $this->apiSuccess('注册成功，并登陆');

        } else {
            //注册失败，显示错误信息
            $this->apiError($this->showRegError($uid));
        }
    }

    public
    function inCode()
    {

        $aType = I('type', '', 'op_t');
        $aCode = I('code', '', 'op_t');
        $result['status'] = 0;
        if (!mb_strlen($aCode)) {
            $result['info'] = "请输入邀请码！";
            $this->apiError($result);
        }
        $invite = D('Ucenter/Invite')->getByCode($aCode);
        if ($invite) {
            if ($invite['end_time'] > time()) {
                $result['status'] = 1;
                $result['url'] = U('Ucenter/Member/register', array('code' => $aCode, 'type' => $aType));
            } else {
                $result['info'] = "该邀请码已过期！请更换其他邀请码！";
                $this->apiError($result);
            }
        } else {
            $result['info'] = "不存在该邀请码！请核对邀请码！";
            $this->apiError($result);
        }
        $this->apiSuccess('邀请码 验证成功');

    }

    public
    function upRole()
    {
        $aRoleId = I('role_id', 0, 'intval');
        if (IS_POST) {
            $uid = is_login();
            $data['status'] = 0;
            if ($uid > 0 && $aRoleId != get_login_role()) {
                $aCode = I('post.code', '', 'op_t');
                $result['status'] = 0;
                if (!mb_strlen($aCode)) {
                    $result['info'] = "请输入邀请码！";
                    $this->ajaxReturn($result);
                }
                $invite = D('Ucenter/Invite')->getByCode($aCode);
                if ($invite) {
                    if ($invite['end_time'] > time()) {
                        $map['id'] = $invite['invite_type'];
                        $map['roles'] = array('like', '%[' . $aRoleId . ']%');
                        $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
                        if ($invite_type) {
                            $roleUser = D('UserRole')->where(array('uid' => $uid, 'role_id' => $aRoleId))->find();
                            if ($roleUser) {
                                $data['info'] = "已持有该身份！";
                            } else {
                                $memberModel = D('Common/Member');
                                $memberModel->logout();
                                $this->initInviteUser($uid, $aCode, $aRoleId);
                                $this->initRoleUser($aRoleId, $uid);
                                clean_query_user_cache($uid, array('avatar64', 'avatar128', 'avatar32', 'avatar256', 'avatar512', 'rank_link'));
                                $memberModel->login($uid, false, $aRoleId); //登陆
                                $result['status'] = 1;
                                $result['url'] = U('Ucenter/Member/register', array('code' => $aCode));
                            }
                        } else {
                            $result['info'] = "该身份需要更高级的邀请码才能升级！";
                        }
                    } else {
                        $result['info'] = "该邀请码已过期！请更换其他邀请码！";
                    }
                } else {
                    $result['info'] = "不存在该邀请码！请核对邀请码！";
                }
            } else {
                $data['info'] = "非法操作！";
            }
            $this->apiError($result);
        } else {
            $this->apiSuccess('role_id', $aRoleId);

        }
    }


    public
    function login()
    {

        $aUsername = $username = I('username', '', 'op_t');
        $aPassword = I('password', '', 'op_t');
        $aVerify = I('verify', '', 'op_t');
        $aRemember = I('remember', 0, 'intval');
        // 检测验证码
        if (check_verify_open('login')) {
            if (!check_verify($aVerify)) {
                $res['info'] = "验证码输入错误。";
                return $res;
            }
        }

        // 调用UC登录接口登录
        check_username($aUsername, $email, $mobile, $aUnType);

        if (!check_reg_type($aUnType)) {
            $res['info'] = "该类型未开放登录。";
        }
        $uid = UCenterMember()->login($username, $aPassword, $aUnType);

        if (0 < $uid) {
            //UC登录成功
            // 登录用户
            $Member = D('Member');
            $args['uid'] = $uid;
            $args = array('uid' => $uid, 'nickname' => $username);

            check_and_add($args);

            if ($Member->mobileLogin($uid, $aRemember) == 1) {
             //登录用户

                if (UC_SYNC && $uid != 1) {
                    //同步登录到UC
                    $ref = M('ucenter_user_link')->where(array('uid' => $uid))->find();

                }

                $extra = array();
                $extra['session_id'] = session_id();
                $extra['uid'] = $uid;
                C(api('Config/lists'));
                $extra['weibo_words_limit'] = C('WEIBO_WORDS_COUNT');
                $extra['version'] = C('APP_VERSION');
                $extra['self']=query_user(array('uid', 'nickname','avatar128','avatar256'), is_login());

                $this->apiSuccess("登录成功", $extra);
            } else {

                $this->apiError('登陆成功');
            }
        } else {
            //登录失败

            switch ($uid) {
                case -1:
                    $res['info'] = '用户不存在或被禁用！';
                    break; //系统级别禁用
                case -2:
                    $res['info'] = '密码错误！';
                    break;
                default:
                    $res['info'] = $uid;
                    break; // 0-接口参数错误（调试阶段使用）
            }
        }
        dump(1111);
        $this->apiError($res['info']);
    }


    public
    function logout()
    {
        $this->requireLogin();
        //调用用户中心
        $model = D('Home/Member');
        $model->logout();
        session_destroy();
        //返回成功信息
        $this->apiSuccess("登出成功");
    }


    public
    function getProfile($uid = null, $fields = 'avatar256,sex,nickname,username,score,tox_money,email,weibo_count,rank_link,expand_info,fans,following')
    {
        //默认查看自己的详细资料
        if (!$uid) {
            $this->requireLogin();
            $uid = $this->getUid();
        }
        $fileds = explode(',', $fields);
        $user = query_user($fileds, $uid);
        foreach ($fileds as $key => $value) {
            if ($value == 'password') {
                unset($fileds[$key]);
            }
        }
        //只返回必要的详细资料
        $this->apiSuccess("获取成功", $user);
    }


    public
    function setProfile($signature = null, $email = null, $name = null, $sex = null, $birthday = null)
    {
        $this->requireLogin();
        //获取用户编号
        $uid = $this->getUid();
        //将需要修改的字段填入数组
        $fields = array();
        if ($signature !== null) $fields['signature'] = $signature;
        if ($email !== null) $fields['email'] = $email;
        if ($name !== null) $fields['name'] = $name;
        if ($sex !== null) $fields['sex'] = $sex;
        if ($birthday !== null) $fields['birthday'] = $birthday;

        foreach ($fields as $key => $field) {
            clean_query_user_cache($this->getUid(), $key); //删除缓存
        }
        //将字段分割成两部分，一部分属于ucenter，一部分属于home
        $split = $this->splitUserFields($fields);
        $home = $split['home'];
        $ucenter = $split['ucenter'];
        //分别将数据保存到不同的数据表中
        if ($home) {
            /*if (isset($home['sex'])) {
                $home['sex'] = $this->decodeSex($home['sex']);
            }*/
            $home['uid'] = $uid;
            $model = D('Home/Member');
            $home = $model->create($home);
            $result = $model->where(array('uid' => $uid))->save($home);
            if (!$result) {
                $this->apiError('设置失败，请检查输入格式!', 0);
            }
        }
        if ($ucenter) {
            $model = D('User/UcenterMember');
            $ucenter['id'] = $uid;
            $ucenter = $model->create($ucenter);
            $result = $model->where(array('id' => $uid))->save($ucenter);
            if (!$result) {
                $this->apiError('设置失败，请检查输入格式!', 0);
            }
        }
        //返回成功信息
        $this->apiSuccess("设置成功!");
    }

//邀请码或邀请链接
    private function checkRegisterType($aCode)
    {

        $register_type = modC('REGISTER_TYPE', 'normal', 'Invite');
        $register_type = explode(',', $register_type);

        if (!in_array('invite', $register_type) && !in_array('normal', $register_type)) {
            $this->apiError("网站已关闭注册！");
        }

        if (in_array('invite', $register_type) && $aCode != '') {
            //邀请注册开启且有邀请码
            $invite = D('Ucenter/Invite')->getByCode($aCode);
            if ($invite) {
                if ($invite['end_time'] <= time()) {
                    $this->apiError("该邀请码或邀请链接已过期！");
                } else {
                    //获取注册角色
                    $map['id'] = $invite['invite_type'];
                    $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
                    if ($invite_type) {

                        if (count($invite_type['roles'])) {
                            //角色
                            $map_role['status'] = 1;
                            $map_role['id'] = array('in', $invite_type['roles']);
                            $roleList = D('Admin/Role')->selectByMap($map_role, 'sort asc', 'id,title');
                            if (!count($roleList)) {
                                $this->apiError('邀请码绑定角色错误！');
                            }
                            //角色end
                        } else {
                            //角色
                            $map_role['status'] = 1;
                            $map_role['invite'] = 0;
                            $roleList = D('Admin/Role')->selectByMap($map_role, 'sort asc', 'id,title');
                            //角色end
                        }
                        $this->apiSuccess('返回成功', $roleList);
                        $this->apiSuccess('返回成功', $invite['user']);
                    } else {
                        $this->apiError("该邀请码或邀请链接已被禁用！");
                    }
                }
            } else {
                $this->apiError("不存在该邀请码或邀请链接！");
            }
        } else {
            //（开启邀请注册且无邀请码）或（只开启了普通注册）
            if (in_array('invite', $register_type)) {
                $open_invite_register['open_invite_register'] = 1;
                $this->apiSuccess('返回成功', $open_invite_register);
            }

            if (in_array('normal', $register_type)) {
                //角色
                $map_role['status'] = 1;
                $map_role['invite'] = 0;
                $roleList = D('Admin/Role')->selectByMap($map_role, 'sort asc', 'id,title');
                $this->apiSuccess('返回成功', $roleList);
                //角色end
            } else {
                //（只开启了邀请注册）
                $this->apiError("收到邀请的用户才能注册该网站！");
            }
        }
    }

    /**
     * 判断邀请码是否可用
     * @param string $code
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private
    function checkInviteCode($code = '')
    {
        if ($code == '') {
            return true;
        }
        $invite = D('Ucenter/Invite')->getByCode($code);
        if ($invite['end_time'] >= time()) {
            $map['id'] = $invite['invite_type'];
            $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
            if ($invite_type) {
                return true;
            }
        }
        return false;
    }

    private
    function initInviteUser($uid = 0, $code = '', $role = 0)
    {
        if ($code != '') {
            $inviteModel = D('Ucenter/Invite');
            $invite = $inviteModel->getByCode($code);
            $data['inviter_id'] = abs($invite['uid']);
            $data['uid'] = $uid;
            $data['invite_id'] = $invite['id'];
            $result = D('Ucenter/InviteLog')->addData($data, $role);
            if ($result) {
                D('Ucenter/InviteUserInfo')->addSuccessNum($invite['invite_type'], abs($invite['uid']));

                $invite_info['already_num'] = $invite['already_num'] + 1;
                if ($invite_info['already_num'] == $invite['can_num']) {
                    $invite_info['status'] = 0;
                }
                $inviteModel->where(array('id' => $invite['id']))->save($invite_info);

                $map['id'] = $invite['invite_type'];
                $invite_type = D('Ucenter/InviteType')->getSimpleData($map);
                if ($invite_type['is_follow']) {
                    $followModel = D('Common/Follow');
                    $followModel->addFollow($uid, abs($invite['uid']));
                    $followModel->addFollow(abs($invite['uid']), $uid);
                }
                if ($invite['uid'] > 0) {
                    D('Ucenter/Score')->setUserScore(array($invite['uid']), $invite_type['income_score'], $invite_type['income_score_type'], 'inc');//扣积分
                }
            }
        }
        return true;
    }

    /**
     * 初始化角色用户信息
     * @param $role_id
     * @param $uid
     * @return bool
     * @author 郑钟良<zzl@ourstu.com>
     */
    private
    function initRoleUser($role_id = 0, $uid)
    {
        $memberModel = D('Member');
        $role = D('Role')->where(array('id' => $role_id))->find();
        $user_role = array('uid' => $uid, 'role_id' => $role_id, 'step' => "start");
        if ($role['audit']) { //该角色需要审核
            $user_role['status'] = 2; //未审核
        } else {
            $user_role['status'] = 1;
        }
        $result = D('UserRole')->add($user_role);
        if (!$role['audit']) { //该角色不需要审核
            $memberModel->initUserRoleInfo($role_id, $uid);
        }
        $memberModel->initDefaultShowRole($role_id, $uid);

        return $result;
    }

    /**
     * activateVerify 添加激活验证
     * @return bool|string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private
    function activateVerify()
    {
        $aUid = session('temp_login_uid');
        $email = UCenterMember()->where(array('id' => $aUid))->getField('email');
        $verify = D('Verify')->addVerify($email, 'email', $aUid);
        $res = $this->sendActivateEmail($email, $verify, $aUid); //发送激活邮件
        return $res;
    }

    /* 验证码，用于登录和注册 */
    public
    function verify()
    {
        verify();
        //  $verify = new \Think\Verify();
        //  $verify->entry(1);
    }

    /**
     * checkAccount  ajax验证用户帐号是否符合要求
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public
    function checkAccount()
    {
        $aAccount = I('post.account', '', 'op_t');
        $aType = I('post.type', '', 'op_t');
        if (empty($aAccount)) {
            $this->apiError('不能为空！');
        }
        check_username($aAccount, $email, $mobile, $aUnType);
        $mUcenter = UCenterMember();
        switch ($aType) {
            case 'username':
                empty($aAccount) && $this->error('用户名格式不正确！');
                $length = mb_strlen($aAccount, 'utf-8'); // 当前数据长度
                if ($length < 4 || $length > 32) {
                    $this->apiError('用户名长度在4-32之间');
                }


                $id = $mUcenter->where(array('username' => $aAccount))->getField('id');
                if ($id) {
                    $this->apiError('该用户名已经存在！');
                }
                preg_match("/^[a-zA-Z0-9_]{4,32}$/", $aAccount, $result);
                if (!$result) {
                    $this->apiError('只允许字母和数字和下划线！');
                }
                break;
            case 'email':
                empty($email) && $this->error('邮箱格式不正确！');
                $length = mb_strlen($email, 'utf-8'); // 当前数据长度
                if ($length < 4 || $length > 32) {
                    $this->apiError('邮箱长度在4-32之间');
                }

                $id = $mUcenter->where(array('email' => $email))->getField('id');
                if ($id) {
                    $this->apiError('该邮箱已经存在！');
                }
                break;
            case 'mobile':
                empty($mobile) && $this->error('手机格式不正确！');
                $id = $mUcenter->where(array('mobile' => $mobile))->getField('id');
                if ($id) {
                    $this->apiError('该手机号已经存在！');
                }
                break;
        }
        $this->apiSuccess('验证成功');
    }

    /**
     * checkNickname  ajax验证昵称是否符合要求
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public
    function checkNickname()
    {
        $aNickname = I('post.nickname', '', 'op_t');

        if (empty($aNickname)) {
            $this->apiError('不能为空！');
        }

        $length = mb_strlen($aNickname, 'utf-8'); // 当前数据长度
        if ($length < 4 || $length > 32) {
            $this->apiError('昵称长度在4-32之间');
        }

        $memberModel = D('member');
        $uid = $memberModel->where(array('nickname' => $aNickname))->getField('uid');
        if ($uid) {
            $this->apiError('该昵称已经存在！');
        }
        preg_match('/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']+$/', $aNickname, $result);
        if (!$result) {
            $this->apiError('只允许中文、字母和数字和下划线！');
        }

        $this->apiSuccess('验证成功');
    }

    /**
     * 持有新身份
     * @author 郑钟良<zzl@ourstu.com>
     */
    public
    function registerRole()
    {
        $aRoleId = I('post.role_id', 0, 'intval');
        $uid = is_login();
        $data['status'] = 0;
        if ($uid > 0 && $aRoleId != get_login_role()) {
            $roleUser = D('UserRole')->where(array('uid' => $uid, 'role_id' => $aRoleId))->find();
            if ($roleUser) {
                $data['info'] = "已持有该身份！";
                $this->ajaxReturn($data);
            } else {
                $memberModel = D('Common/Member');
                $memberModel->logout();
                $this->initRoleUser($aRoleId, $uid);
                clean_query_user_cache($uid, array('avatar64', 'avatar128', 'avatar32', 'avatar256', 'avatar512', 'rank_link'));
                $memberModel->login($uid, false, $aRoleId); //登陆
            }
        } else {
            $data['info'] = "非法操作！";
            $this->ajaxReturn($data);
        }
    }


    /**
     * sendActivateEmail   发送激活邮件
     * @param $account
     * @param $verify
     * @return bool|string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private
    function sendActivateEmail($account, $verify, $uid)
    {

        $url = 'http://' . $_SERVER['HTTP_HOST'] . U('ucenter/member/doActivate?account=' . $account . '&verify=' . $verify . '&type=email&uid=' . $uid);
        $content = modC('REG_EMAIL_ACTIVATE', '{$url}', 'USERCONFIG');
        $content = str_replace('{$url}', $url, $content);
        $content = str_replace('{$title}', modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config'), $content);
        $res = send_mail($account, modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config') . '激活信', $content);
        return $res;
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    public
    function showRegError($code = 0)
    {
        switch ($code) {
            case -1:
                $error = '用户名长度必须在4-32个字符以内！';
                break;
            case -2:
                $error = '用户名被禁止注册！';
                break;
            case -3:
                $error = '用户名被占用！';
                break;
            case -4:
                $error = '密码长度必须在6-30个字符之间！';
                break;
            case -5:
                $error = '邮箱格式不正确！';
                break;
            case -6:
                $error = '邮箱长度必须在4-32个字符之间！';
                break;
            case -7:
                $error = '邮箱被禁止注册！';
                break;
            case -8:
                $error = '邮箱被占用！';
                break;
            case -9:
                $error = '手机格式不正确！';
                break;
            case -10:
                $error = '手机被禁止注册！';
                break;
            case -11:
                $error = '手机号被占用！';
                break;
            case -20:
                $error = '用户名只能由数字、字母和"_"组成！';
                break;
            case -30:
                $error = '昵称被占用！';
                break;
            case -31:
                $error = '昵称被禁止注册！';
                break;
            case -32:
                $error = '昵称只能由数字、字母、汉字和"_"组成！';
                break;
            case -33:
                $error = '昵称不能少于两个字！';
                break;
            default:
                $error = '未知错误24';
        }
        return $error;
    }


}