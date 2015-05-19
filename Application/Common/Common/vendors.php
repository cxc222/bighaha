<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 茉莉清茶 <57143976@qq.com> <http://www.3spp.cn>
// +----------------------------------------------------------------------


/**
 * 系统公共库文件扩展
 * 主要定义系统公共函数库扩展
 */

/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @Return: array
 */
use Vendor\PHPMailer;

function get_city_by_ip($ip)
{
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
    $ipinfo = json_decode(file_get_contents($url));
    if ($ipinfo->code == '1') {
        return false;
    }
    $city = $ipinfo->data->region . $ipinfo->data->city; //省市县
    $ip = $ipinfo->data->ip; //IP地址
    $ips = $ipinfo->data->isp; //运营商
    $guo = $ipinfo->data->country; //国家
    if ($guo == '中国') {
        $guo = '';
    }
    return $guo . $city . $ips . '[' . $ip . ']';

}

/**
 * 系统邮件发送函数
 * @param string $to 接收邮件者邮箱
 * @param string $name 接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $attachment 附件列表
 * @茉莉清茶 57143976@qq.com
 */
function send_mail($to = '', $subject = '', $body = '', $name = '', $attachment = null)
{
    $host = C('MAIL_SMTP_HOST');
    $user = C('MAIL_SMTP_USER');
    $pass = C('MAIL_SMTP_PASS');
    if (empty($host) || empty($user) || empty($pass)) {
        return '管理员还未配置邮件信息，请联系管理员配置';
    }

    if (is_sae()) {
        return sae_mail($to, $subject, $body, $name);
    } else {
        return send_mail_local($to, $subject, $body, $name, $attachment);
    }
}

/**
 * SAE邮件发送函数
 * @param string $to 接收邮件者邮箱
 * @param string $name 接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param string $attachment 附件列表
 * @茉莉清茶 57143976@qq.com
 */
function sae_mail($to = '', $subject = '', $body = '', $name = '')
{
    $site_name = modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config');
    if ($to == '') {
        $to = C('MAIL_SMTP_CE'); //邮件地址为空时，默认使用后台默认邮件测试地址
    }
    if ($name == '') {
        $name = $site_name; //发送者名称为空时，默认使用网站名称
    }
    if ($subject == '') {
        $subject = $site_name; //邮件主题为空时，默认使用网站标题
    }
    if ($body == '') {
        $body = $site_name; //邮件内容为空时，默认使用网站描述
    }
    $mail = new SaeMail();
    $mail->setOpt(array(
        'from' => C('MAIL_SMTP_USER'),
        'to' => $to,
        'smtp_host' => C('MAIL_SMTP_HOST'),
        'smtp_username' => C('MAIL_SMTP_USER'),
        'smtp_password' => C('MAIL_SMTP_PASS'),
        'subject' => $subject,
        'content' => $body,
        'content_type' => 'HTML'
    ));

    $ret = $mail->send();
    return $ret ? true : $mail->errmsg(); //返回错误信息
}

function is_sae()
{
    return function_exists('sae_debug');
}

function is_local()
{
    return strtolower(C('PICTURE_UPLOAD_DRIVER')) == 'local' ? true : false;
}

/**
 * 用常规方式发送邮件。
 */
function send_mail_local($to = '', $subject = '', $body = '', $name = '', $attachment = null)
{
    $from_email = C('MAIL_SMTP_USER');
    $from_name = modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config');
    $reply_email = '';
    $reply_name = '';

    //require_once('./ThinkPHP/Library/Vendor/PHPMailer/phpmailer.class.php');增加命名空间，可以注释掉此行
    $mail = new PHPMailer(); //实例化PHPMailer
    $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug = 0; // 关闭SMTP调试功能
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth = true; // 启用 SMTP 验证功能

    $mail->SMTPSecure = ''; // 使用安全协议
    $mail->Host = C('MAIL_SMTP_HOST'); // SMTP 服务器
    $mail->Port = C('MAIL_SMTP_PORT'); // SMTP服务器的端口号
    $mail->Username = C('MAIL_SMTP_USER'); // SMTP服务器用户名
    $mail->Password = C('MAIL_SMTP_PASS'); // SMTP服务器密码
    $mail->SetFrom($from_email, $from_name);
    $replyEmail = $reply_email ? $reply_email : $from_email;
    $replyName = $reply_name ? $reply_name : $from_name;
    if ($to == '') {
        $to = C('MAIL_SMTP_CE'); //邮件地址为空时，默认使用后台默认邮件测试地址
    }
    if ($name == '') {
        $name = modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config'); //发送者名称为空时，默认使用网站名称
    }
    if ($subject == '') {
        $subject = modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config'); //邮件主题为空时，默认使用网站标题
    }
    if ($body == '') {
        $body = modC('WEB_SITE_NAME', 'OpenSNS开源社交系统', 'Config'); //邮件内容为空时，默认使用网站描述
    }
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body); //解析
    $mail->AddAddress($to, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }

    return $mail->Send() ? true : $mail->ErrorInfo; //返回错误信息
}

function thinkox_hash($message, $salt = "ThinkOX")
{
    $s01 = $message . $salt;
    $s02 = md5($s01) . $salt;
    $s03 = sha1($s01) . md5($s02) . $salt;
    $s04 = $salt . md5($s03) . $salt . $s02;
    $s05 = $salt . sha1($s04) . md5($s04) . crc32($salt . $s04);
    return md5($s05);
}

/**获取模块的后台设置
 * @param        $key 获取模块的配置
 * @param string $default 默认值
 * @param string $module 模块名，不设置用当前模块名
 * @return string
 * @auth 陈一枭
 */
function modC($key, $default = '', $module = '')
{
    $mod = $module ? $module : MODULE_NAME;

    $result = S('conf_' . strtoupper($mod) . '_' . strtoupper($key));
    if (empty($result)) {
        $config = D('Config')->where(array('name' => '_' . strtoupper($mod) . '_' . strtoupper($key)))->find();
        if (!$config) {
            $result = $default;
        } else {
            $result = $config['value'];
        }
        S('conf_' . strtoupper($mod) . '_' . strtoupper($key), $result);
    }
    return $result;
}

/**发送短消息
 * @param        $mobile 手机号码
 * @param        $content 内容
 * @param string $time 定时发送
 * @param string $mid 子扩展号
 * @return string
 * @auth 肖骏涛
 */
function sendSMS($mobile, $content, $time = '', $mid = '')
{
    $uid = modC('SMS_UID', '', 'USERCONFIG');
    $pwd = modC('SMS_PWD', '', 'USERCONFIG');
    $http = modC('SMS_HTTP', '', 'USERCONFIG');

    if (empty($http) || empty($uid) || empty($pwd)) {
        return '管理员还未配置短信信息，请联系管理员配置';
    }
    $data = array
    (
        'uid' => $uid, //用户账号
        'pwd' => strtolower(md5($pwd)), //MD5位32密码
        'mobile' => $mobile, //号码
        'content' => $content, //内容 如果对方是utf-8编码，则需转码iconv('gbk','utf-8',$content); 如果是gbk则无需转码
        'time' => $time, //定时发送
        'mid' => $mid, //子扩展号
        'encode' => 'utf8',
    );
    $re = postSMS($http, $data); //POST方式提交
    if (trim($re) == '100') {
        return "发送成功!";
    } else {
        return "发送失败! 状态：" . $re;
    }
}

function postSMS($url, $data = '')
{
    $row = parse_url($url);
    $host = $row['host'];
    $port = $row['port'] ? $row['port'] : 80;
    $file = $row['path'];
    $post = '';
    while (list($k, $v) = each($data)) {
        $post .= rawurlencode($k) . "=" . rawurlencode($v) . "&"; //转URL标准码
    }
    $post = substr($post, 0, -1);
    $len = strlen($post);
    $fp = @fsockopen($host, $port, $errno, $errstr, 10);
    if (!$fp) {
        return "$errstr ($errno)\n";
    } else {
        $receive = '';
        $out = "POST $file HTTP/1.1\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Content-type: application/x-www-form-urlencoded\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Content-Length: $len\r\n\r\n";
        $out .= $post;
        fwrite($fp, $out);
        while (!feof($fp)) {
            $receive .= fgets($fp, 128);
        }
        fclose($fp);
        $receive = explode("\r\n\r\n", $receive);
        unset($receive[0]);
        return implode("", $receive);
    }
}

/**
 * get_kanban_config  获取看板配置
 * @param $key
 * @param $kanban
 * @param string $default
 * @param string $module
 * @return array|bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function get_kanban_config($key, $kanban, $default = '', $module = '')
{
    $config = modC($key, $default, $module);
    if (is_array($config)) {
        return $config;
    } else {
        $config = json_decode($config, true);
        foreach ($config as $v) {
            if ($v['data-id'] == $kanban) {
                $res = $v['items'];
                break;
            }
        }
        return getSubByKey($res, 'data-id');
    }


}