<?php

class HotWidget extends Widget{
	public function render ($data){
		$filed = array('id','title','click');
		$data['articlenew'] = M('article')->field($filed)->where(array('del'=>0))->order('time DESC')->limit(8)->select();
		$data['articleclick'] = M('article')->field($filed)->where(array('del'=>0))->order('click DESC')->limit(8)->select();
		$data['articleding'] = M('article')->field($filed)->where(array('del'=>0))->order('ding DESC')->limit(8)->select();
		return $this->renderFile('',$data);
	}


}
