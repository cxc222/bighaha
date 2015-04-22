<?php
namespace Home\Model;
use Think\Model\ViewModel;
class ArticleViewModel extends ViewModel {
	protected $viewFields = array(
		'article' => array(
			'id','title','time','click','summary','thumbnail','content','ding','cai','username','click',
			'_type'=>'LEFT'
			),
		'cate' => array(
			'id'=>'ppid','name','sort','filename','_on'=>'article.cid = cate.id'
			),
		'user' => array(
			'avatar','_on'=>'article.username = user.username'
			)

	);

	public function getAll($where,$limit){
		return $this->where($where)->limit($limit)->order('time DESC')->select();
	}

}