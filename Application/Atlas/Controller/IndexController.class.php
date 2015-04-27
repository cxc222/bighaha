<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Atlas\Controller;
use Think\Controller;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends Controller {
	
	public function index($page = 1) {
		print_r(213);
		die();
		$this->display ();
	}
}