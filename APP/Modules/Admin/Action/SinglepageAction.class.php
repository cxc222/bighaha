<?php

class SinglepageAction extends CommonAction{

	public function index(){
		$this->singlepage = M('singlepage')->order('sort ASC')->select();
		$this->display();
	}


	public function add(){
		$this->display();
	}	

	public function addRun(){
		$data = array(
			'title' => $_POST['title'],
			'type' => $_POST['type'],
			'filename' => $_POST['filename'],
			'content' => htmlspecialchars($_POST['content']),
			'sort' => $_POST['sort'],
			'templates' => $_POST['templates'],
			'time' => time()
			);
		M('singlepage')->add($data) ? $this->success('添加成功',U(GROUP_NAME.'/Singlepage/index')) : $this->error('添加失败');
	}


	public function update(){
		
		isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
		$this->singlepage = M('singlepage')->where(array('id'=>$id))->select();
		$this->display();
	}

	public function updateRun(){
		$data = array(
			'id' => $_POST['id'],
			'title' => $_POST['title'],
			'type' => $_POST['type'],
			'sort' => $_POST['sort'],
			'filename' => $_POST['filename'],
			'content' => $_POST['content'],
			'templates' => $_POST['templates'],
			'time' => time()
			);
		M('singlepage')->save($data) ? $this->success('修改成功',U(GROUP_NAME.'/Singlepage/index')) : $this->error('修改失败');

	}

	//添加分类的表单处理
	public function sort(){
		$db = M('singlepage');
		foreach ($_POST as $id => $sort) {
			$db->where(array('id'=>$id))->setField('sort',$sort);
		}
		$this->redirect(GROUP_NAME.'/Singlepage/index');
	}


	public function delete(){
		if(M('singlepage')->delete($_GET['id'])){
			$this->success('删除成功',U(GROUP_NAME.'/Singlepage/index'));
		}else{
			$this->error('删除失败');
		}
	}

	//编辑器图片上传处理
	public function upload(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile($config);
		$upload->autoSub = true;
		$upload->subType = 'date';
		$upload->dateFormat = 'Ym';

		if ($upload->upload('./Uploads/')) {
			$info = $upload->getUploadFileInfo();
			if ($info[0]['extension'] != 'gif') { //判断，如果是gif就不加水印
				if ($info[0]['size'] > 40000) { //判断，大于40kb才加水印
					import('Class.Image',APP_PATH);
					Image::water('./Uploads/'.$info[0]['savename']);
				}
			}
			echo json_encode(array(
					'url'=> $info[0]['savename'],
					'title'=> htmlspecialchars($_POST['pictitle'], ENT_QUOTES),
					'original'=> $info[0]['name'],
					'state'=> 'SUCCESS'
				));
		}else{
			echo json_encode(array(
					'state'=> $upload0->getErrorMsg()
				));
			;
		}
	}	


}