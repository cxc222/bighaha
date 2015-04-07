<?php

class CommandPicWidget extends Widget{
	public function render ($data){
		$limit = 4;
		$filed = array('id','title','time','thumbnail');
		$wheregif = array('del'=>0,'cid'=>1);
		$wheregif['thumbnail'] = array('neq','');
		$wherevideo = array('del'=>0,'cid'=>4);
		$wherevideo['thumbnail'] = array('neq','');
		$data['articlegif'] = M('article')->field($filed)->where($wheregif)->order('time DESC')->limit($limit)->select();
		$data['articlevideo'] = M('article')->field($filed)->where($wherevideo)->order('time DESC')->limit($limit)->select();
		return $this->renderFile('',$data);
	}
}
