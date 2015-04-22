<?php
namespace Home\Model;
use Think\Model\ViewModel;
class SlideViewModel extends ViewModel{

	protected $viewFields = array(
		'article' => array(
			'id','title','thumbnail','username','click','ding','cai',
			'_type'=>'LEFT'
			),
		'user' => array(
			'username','avatar','_on'=>'article.username = user.username'
			),

	);

	public function getAll($where){
		return $this->where($where)->limit(4)->order('time DESC')->select();
	}

}