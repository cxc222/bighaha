<?php

class TagViewModel extends ViewModel{

	protected $viewFields = array(
		'tag' => array(
			'aid','tagname',
			'_type'=>'LEFT'
			),
		'article' => array(
			'id','title','time','click','summary','thumbnail','content','ding','cai','cid','username','click','_on'=>'tag.aid = article.id'
			),
		'cate' => array(
			'id'=>'ppid','name','sort','filename','_on'=>'article.cid = cate.id'
			)


	);

	public function getAll($where,$limit){
		return $this->where($where)->limit($limit)->order('time DESC')->select();
	}

}