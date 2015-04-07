<?php

class CommandArticleWidget extends Widget{
	public function render ($data){
		$filed = array('id','title','time','thumbnail');
		$where = array('del'=>0,'cid'=>$data['cid']);
		$where['thumbnail'] = array('neq','');
		$data['articlegif'] = M('article')->field($filed)->where($where)->order('click DESC')->limit($data['limit'])->select();
		return $this->renderFile('',$data);
	}
}
