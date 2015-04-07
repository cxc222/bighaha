<?php

class RequireAction extends CommonAction{
	//列表
	public function index(){
		$this->article = M('require')->select();
		$this->display();
	}

	//彻底删除所有回收站的文章
	public function delete(){
		$id = $_GET['id'];
		$re = M('require')->where(array('id'=>$id))->delete();
		$this->redirect(GROUP_NAME.'/Require/index');  
	}

	//修改文章
	public function update(){
		$id = $_GET['id'];
		$data = array(
			'stuts'=>'1',
			'id'=>$id
			);
		$re = M('require')->save($data);
		$this->redirect(GROUP_NAME.'/Require/index');
	}

	


}
