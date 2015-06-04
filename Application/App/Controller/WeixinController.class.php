<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-9-11
 * Time: 下午2:57
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace App\Controller;

use App\Com\Wechat;
use Think\Controller;


class WeixinController extends BaseController
{
    /**
     * @auth zzl
     */
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */

    private function C()
    {
        return require_once(APP_PATH . '/Mobile/Conf/config.php');
    }

    private function getToken(){
        $config=$this->C();
        return $config['WEIXIN_TOKEN'];
    }
    public function index()
    {


        $token =$this->getToken(); //微信后台填写的TOKEN
        /* 加载微信SDK */
        $wechat = new Wechat($token);
        /* 获取请求信息 */
        $data = $wechat->request();
        if ($data && is_array($data)) {
            $content = str_replace('/api.php','/index.php',U('Mobile/index/index',array('fromusername'=>$data['FromUserName'],'rand'=>time()),null,true))  ; //回复内容，回复不同类型消息，内容的格式有所不同
            $type = Wechat::MSG_TYPE_TEXT; //回复消息的类型
            /* 响应当前请求(自动回复) */
            $wechat->response($content, $type);
        }

        /**
         * 你可以在这里分析数据，决定要返回给用户什么样的信息
         * 接受到的信息类型有9种，分别使用下面九个常量标识
         * Wechat::MSG_TYPE_TEXT       //文本消息
         * Wechat::MSG_TYPE_IMAGE      //图片消息
         * Wechat::MSG_TYPE_VOICE      //音频消息
         * Wechat::MSG_TYPE_VIDEO      //视频消息
         * Wechat::MSG_TYPE_MUSIC      //音乐消息
         * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
         * Wechat::MSG_TYPE_LOCATION   //位置消息
         * Wechat::MSG_TYPE_LINK       //连接消息
         * Wechat::MSG_TYPE_EVENT      //事件消息
         *
         * 事件消息又分为下面五种
         * Wechat::MSG_EVENT_SUBSCRIBE          //订阅
         * Wechat::MSG_EVENT_SCAN               //二维码扫描
         * Wechat::MSG_EVENT_LOCATION           //报告位置
         * Wechat::MSG_EVENT_CLICK              //菜单点击
         * Wechat::MSG_EVENT_MASSSENDJOBFINISH  //群发消息成功
         */

        /**
         * 响应当前请求还有以下方法可以只使用
         * 具体参数格式说明请参考文档
         *
         * $wechat->replyText($text); //回复文本消息
         * $wechat->replyImage($media_id); //回复图片消息
         * $wechat->replyVoice($media_id); //回复音频消息
         * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
         * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
         * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
         * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
         */
    }

    public function checkUser()
    {
        $aFromUserName = I('post.FromUserName', '', 'op_t');
        $data['exit'] = 0;
        if (is_login()) {
            $Member = D('Home/Member');
            $Member->logout();
        }
        if ($aFromUserName != '' && $aFromUserName != null) {
            session('FROM_USER_NAME', $aFromUserName);
            $user_weixin = D('UserWeixin')->where(array('openid' => $aFromUserName))->find();
            if ($user_weixin) {
                $data['exit'] = 1;
                if (!is_login() || is_login() != $user_weixin['uid']) {
                    D('Home/Member')->login($user_weixin['uid'], true);
                }
                $data['uid'] = is_login();
                $user_info = query_user(array('uid', 'nickname','avatar128','avatar256'), is_login());
                $data['user_info'] = $user_info;
            }
        }
        $this->ajaxReturn($data);
    }

    public function nowLogin()
    {
        if (!is_login()) {
            $FromUserName = session('FROM_USER_NAME');
            if ($FromUserName != '' && $FromUserName != null) {
                $user_weixin = D('UserWeixin')->where(array('openid' => $FromUserName))->find();
                if ($user_weixin) {
                    D('Home/Member')->login($user_weixin['uid'], true);
                }
            }
        }
        $data['uid'] = is_login();
        $this->ajaxReturn($data);
    }

    public function bindUser()
    {
        $aUser_name = I('post.user_name', '', 'op_t');
        $aPassword = I('post.password', '', 'op_t');
        $aFromUserName = I('post.FromUserName', '', 'op_t');

        $result['type'] = 0;//0：失败；1：成功;
        if (mb_strlen($aFromUserName, 'utf-8') < 1) {
            $result['info'] = '请通过微信登录！';
            $this->ajaxReturn($result);
        }

        /* 调用UC登录接口登录 */
        $user = UCenterMember();
        $uid = $user->login($aUser_name, $aPassword);
        if (0 < $uid) { //UC登录成功
            $is_bind = D('App/UserWeixin')->alreadyBind($uid);
            if ($is_bind) {
                $result['info'] = '该帐号已绑定其它微信号！';
                $this->ajaxReturn($result);
            }
            /* 登录用户 */
            $Member = D('Home/Member');
            if ($Member->login($uid, 'on')) { //登录用户
                $data['uid'] = $uid;
                $data['openid'] = $aFromUserName;
                $res = D('App/UserWeixin')->bindUser($data);
                if ($res) {
                    $result['type'] = 1;
                    $result['uid'] = is_login();
                    $user_info = query_user(array('uid', 'nickname','avatar128','avatar256'), is_login());
                    $result['user_info'] = $user_info;
                } else {
                    $result['info'] = '绑定失败';
                }
            } else {
                $result['info'] = $Member->getError();
            }

        } else { //登录失败
            switch ($uid) {
                case -1:
                    $result['info'] = '用户不存在或被禁用！';
                    break; //系统级别禁用
                case -2:
                    $result['info'] = '密码错误！';
                    break;
                default:
                    $result['info'] = $uid;
                    break; // 0-接口参数错误（调试阶段使用）
            }
        }

        $this->ajaxReturn($result);
    }

    public function loginOut()
    {
        $openid = session('FROM_USER_NAME');
        if ($openid != '' && $openid != null) {
            $openid = I('post.FromUserName', '', 'op_t');
        }
        $map['uid'] = is_login();
        $map['openid'] = $openid;
        $res = D('App/UserWeixin')->unbindUser($map);
        if ($res) {
            $Member = D('Home/Member');
            $Member->logout();
            $data['type'] = 1;
        } else {
            $data['type'] = 0;
            $data['info'] = '你没权限操作！';
        }
        $this->ajaxReturn($data);
    }

    public function register()
    {
        $aUser_name = I('post.user_name', '', 'op_t');
        $aPassword = I('post.password', '', 'op_t');
        $aNickname = I('post.nickname', '', 'op_t');
        $aEmail = I('post.email', '', 'op_t');
        $aFromUserName = I('post.FromUserName', '', 'op_t');

        $result['type'] = 0;
        /* 调用注册接口注册用户 */
        $User = UCenterMember();
        $uid = $User->register($aUser_name, $aNickname, $aPassword, $aEmail);
        if (0 < $uid) { //注册成功
            $uid = $User->login($aUser_name, $aPassword);//通过账号密码取到uid
            D('Home/Member')->login($uid, false);//登陆
            $reg_weibo = C('USER_REG_WEIBO_CONTENT');//用户注册的微博内容
            if ($reg_weibo != '') {//为空不发微博
                D('Weibo/Weibo')->addWeibo($uid, $reg_weibo);
            }

            //绑定微信
            $data['uid'] = $uid;
            $data['openid'] = $aFromUserName;
            $res = D('App/UserWeixin')->bindUser($data);
            if ($res) {
                $result['type'] = 1;
                $user_info = query_user(array('uid', 'nickname','avatar256','avatar128'), is_login());
                $result['user_info'] = $user_info;
                $result['uid'] = is_login();
            } else {
                $result['info'] = '绑定失败，请进行绑定操作！';
                $result['type'] = -1;
            }
        } else { //注册失败，显示错误信息
            $result['info'] = $this->showRegError($uid);
        }
        $this->ajaxReturn($result);
    }

    public function createSession(){
        $this->ajaxReturn(array('true'));
    }
}