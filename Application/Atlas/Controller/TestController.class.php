<?php
namespace Atlas\Controller;
use Think\Controller;
use Think\Log;
use Think\Hook;
use Atlas\Api\AtlasApi;
use Think\Exception;
use Common\Exception\ApiException;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class TestController extends AtlasController {
	
	function index(){
		$this->display();
	}
}