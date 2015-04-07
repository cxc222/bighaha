<?php

class SearchViewModel extends ViewModel{

	protected $viewFields = array(
		'article' => array(
			'id','title','time','click','summary','thumbnail',
			'_type'=>'LEFT'
			),
		'articlecate' => array(
			'id'=>'ppid','name','sort','_on'=>'article.cid = articlecate.id'
			)
	);

	public function getAll($where,$limit){
		return $this->where($where)->limit($limit)->order('time DESC')->select();
	}

}