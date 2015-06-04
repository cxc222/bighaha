<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.thinkphp.cn>
// +----------------------------------------------------------------------

/**
 * 前台配置文件
 * 所有除开系统级别的前台配置
 */
return array(

    'APP_VERSION' => '0.1.1',
    //'APP_FILE' => 'http://bj2.dl.baidupcs.com/file/800ba5da60c116932b7e762e05d22cf8?fid=3343864405-250528-528934040300612&time=1408541680&sign=FDTAXER-DCb740ccc5511e5e8fedcff06b081203-gXvtsWaL1XGTxTwNf%2FPw4cgpBP0%3D&to=abp2&fm=Nin,B,U,nc&newver=1&newfm=1&flow_ver=3&expires=1408542280&rt=sh&r=168710626&mlogid=3878302150&sh=1&vuk=3343864405&vbdid=2346573767&fn=tox-debug.apk',
    'APP_FILE' => 'apk/0.1.1.apk',

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => false, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 3, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'onethink_home', //session前缀
    'COOKIE_PREFIX' => 'onethink_home_', // Cookie前缀 避免冲突

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
        '__IMG__' => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),


   /* 图片上传相关配置 */
    'PICTURE_UPLOAD' => array(
    'mimes'    => '', //允许上传的文件MiMe类型
    'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
    'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
    'autoSub'  => true, //自动子目录保存文件
    'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
    'rootPath' => './Uploads/Picture/', //保存根路径
    'savePath' => '', //保存路径
    'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
    'saveExt'  => '', //文件保存后缀，空则使用原后缀
    'replace'  => false, //存在同名是否覆盖
    'hash'     => true, //是否生成hash编码
    'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
), //图片上传相关配置（文件上传类配置）
    'SHOW_PAGE_TRACE' => true,
);