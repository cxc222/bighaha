<?php

class CateWidget extends Widget{
	public function render ($data){
		$limit = 8;
		$filed = array('id','title','time');
		$data['articlegif'] = M('article')->field($filed)->where(array('del'=>0,'cid'=>1))->order('time DESC')->limit($limit)->select();
		$data['articlepic'] = M('article')->field($filed)->where(array('del'=>0,'cid'=>2))->order('time DESC')->limit($limit)->select();
		$data['articlenews'] = M('article')->field($filed)->where(array('del'=>0,'cid'=>3))->order('time DESC')->limit($limit)->select();
		return $this->renderFile('',$data);
	}
}
