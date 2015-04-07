<?php

class SinglepagecategoryWidget extends Widget{

	public function render ($data){
		//热门博文
		$limit = $data['limit'];
		$filed = array('id','title','time','filename');
		$data['singlepage'] = M('singlepage')->field($filed)->order('time DESC')->limit($limit)->select();
		return $this->renderFile('',$data);
	}


}
