<?php

class FriendlinkWidget extends Widget{

	public function render ($data){
		//热门博文
		$limit = $data['limit'];
		$filed = array('id','sitename','sort','siteurl');
		$data['friendlink'] = M('friendlink')->field($filed)->where('statu = 1')->order('sort ASC')->limit($limit)->select();
		return $this->renderFile('',$data);
	}


}
