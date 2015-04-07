<?php

class IndexTwoWidget extends Widget{
	public function render ($data){
		$filed = array('id','title','click','thumbnail','ding','cai');
		$where['del'] = 0;
		$where['thumbnail']=array('NEQ','');    
		$where['cid']=array('IN','2,4,5');    
		$data['article'] = M('article')->field($filed)->where($where)->order('id DESC')->limit(2)->select();
		return $this->renderFile('',$data);
	}
}
