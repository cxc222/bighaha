<?php

class NewWidget extends Widget{
	public function render ($data){
		$limit = $data['limit'];
		$filed = array('id','title','time');
		$data['article'] = M('article')->field($filed)->where(array('del'=>0))->order('time DESC')->limit($limit)->select();
		return $this->renderFile('',$data);
	}


}
