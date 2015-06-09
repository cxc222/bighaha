<?php
namespace Common\Controller;
use Think\Controller;
/**
 * 空模块，主要用于显示404页面，请不要删除
 */
class EmptyController extends Controller{
	
	public function _404(){
		send_http_status(404);
		$this->display(T('Common@Base/404'));
	}
	
	function _empty(){
		$this->_404();
	}
	
	function index(){
		$this->_404();
	}
}