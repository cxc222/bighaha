<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------


namespace Addons\SocialComment;
use Common\Controller\Addon;

/**
 * 通用社交化评论插件
 * @author thinkphp
 */

    class SocialCommentAddon extends Addon{

        public $info = array(
            'name'=>'SocialComment',
            'title'=>'通用社交化评论',
            'description'=>'集成了各种社交化评论插件，轻松集成到系统中。',
            'status'=>1,
            'author'=>'zff',
            'version'=>'1.0'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }
        
        //自动登录
        public function loginEnd($uid,$uname,$email,$uface,$ulink,$expire){
            /* $expire = $expire ? $expire :3600 * 24 * 7;
            $uid = "12880";
            $uname = "zhangsan";
            $email = "zhangsan@uyan.cc";
            $uface = "http://www.zhangsang.com/images/1.jpg";
            $ulink = "http://www.zhangsang.com";
            $expire = "3600";
            
            $config = $this->getConfig();
            print_r(2113);
            die();
             */
        }

        //实现的pageFooter钩子方法
        public function documentDetailAfter($param){
            $config = $this->getConfig();
            if(is_login()){
                $uid = is_login();
                $user_info = query_user(array('avatar128', 'nickname', 'uid', 'email','space_url', 'icons_html'), $uid);
                $avatarAddon = new \Addons\Avatar\AvatarAddon();
                $avatarUrl = $avatarAddon->getAvatarPath($uid, 128);
                $expire = $expire ? $expire :3600 * 24 * 7;
                $encode_data = array(
                    'uid' => $uid,
                    'uname' => $user_info['nickname'],
                    'email' => $user_info['email'],
                    'uface' => $this->get_domain().$avatarUrl,
                    'ulink' =>'',
                    'expire' => $expire
                );
                setcookie('syncuyan', $this->des_encrypt(json_encode($encode_data), $config['comment_key_youyan']), time() + 3600, '/', '');
            }
            $this->assign('addons_config', $config);
            $this->display('comment');
        }
        
        /** * 用DES算法加密/解密字符串 * *
        @param string $string 待加密的字符串
        @param string $key 密匙，和管理后台需保持一致
        @return string 返回经过加密/解密的字符串
        */
        // 加密，注意，加密前需要把数组转换为json格式的字符串
        function des_encrypt($string, $key) {
            $size = mcrypt_get_block_size('des', 'ecb');
            $string = mb_convert_encoding($string, 'GBK', 'UTF-8');
            $pad = $size - (strlen($string) % $size);
            $string = $string . str_repeat(chr($pad), $pad);
            $td = mcrypt_module_open('des', '', 'ecb', '');
            $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
            @mcrypt_generic_init($td, $key, $iv);
            $data = mcrypt_generic($td, $string);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            $data = base64_encode($data);
            return $data;
        }
        
        // 解密，解密后返回的是json格式的字符串
        function des_decrypt($string, $key) {
            $string = base64_decode($string);
            $td = mcrypt_module_open('des', '', 'ecb', '');
            $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
            $ks = mcrypt_enc_get_key_size($td);
            @mcrypt_generic_init($td, $key, $iv);
            $decrypted = mdecrypt_generic($td, $string);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            $pad = ord($decrypted{strlen($decrypted) - 1});
            if($pad > strlen($decrypted)) {
                return false;
            }
            if(strspn($decrypted, chr($pad), strlen($decrypted) - $pad) != $pad) {
                return false;
            }
            $result = substr($decrypted, 0, -1 * $pad);
            $result = mb_convert_encoding($result, 'UTF-8', 'GBK');
            return $result;
        }
        
        /**
         * 获得当前的域名
         *
         * @return string
         */
        function get_domain($http = true) {
            /* 协议 */
            $protocol = (isset ( $_SERVER ['HTTPS'] ) && (strtolower ( $_SERVER ['HTTPS'] ) != 'off')) ? 'https://' : 'http://';
        
            /* 域名或IP地址 */
            if (isset ( $_SERVER ['HTTP_X_FORWARDED_HOST'] )) {
                $host = $_SERVER ['HTTP_X_FORWARDED_HOST'];
            } elseif (isset ( $_SERVER ['HTTP_HOST'] )) {
                $host = $_SERVER ['HTTP_HOST'];
            } else {
                /* 端口 */
                if (isset ( $_SERVER ['SERVER_PORT'] )) {
                    $port = ':' . $_SERVER ['SERVER_PORT'];
                    	
                    if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
                        $port = '';
                    }
                } else {
                    $port = '';
                }
        
                if (isset ( $_SERVER ['SERVER_NAME'] )) {
                    $host = $_SERVER ['SERVER_NAME'] . $port;
                } elseif (isset ( $_SERVER ['SERVER_ADDR'] )) {
                    $host = $_SERVER ['SERVER_ADDR'] . $port;
                }
            }
            if ($http) {
                $hosturl = $protocol . $host;
            } else {
                $hosturl = $host;
            }
            return $hosturl;
        }
    }