<?php
namespace Atlas\Controller;
use Think\Controller;
class BaseController extends Controller {
	
	protected function _initialize(){
		/* 读取站点配置 */
		$config = api('Config/lists');
		C($config); //添加配置
	
		if(!C('WEB_SITE_CLOSE')){
			$this->error('站点已经关闭，请稍后访问~');
		}
	}
	
}