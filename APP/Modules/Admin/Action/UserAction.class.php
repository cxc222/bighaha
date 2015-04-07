<?php
	class UserAction extends CommonAction {
		
		//友情链接列表
		public function index(){
			$this->user = M("user")->select();
			$this->display();
		}

		//添加友情链接
		public function add(){
			$this->display();
		}

		//添加友情链接表单处理
		public function addRun(){
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =  './Uploads/avatar/';// 设置附件上传目录
			$upload->upload();
			$info =  $upload->getUploadFileInfo();
			$info[0]['savename'] ? $avatar=$info[0]['savename'] : $avatar='';


			if($_POST['password'] != $_POST['notpassword']) $this->error("两次输入密码错误");
			// 保存表单数据 包括附件数据
			$user = M("user"); // 实例化User对象
			$user->create(); // 创建数据对象
			$user->avatar = $avatar; // 保存上传的照片根据需要自行组装
			$user->password = md5($_POST['password']); // 保存上传的照片根据需要自行组装
			// 写入用户数据到数据库
		    if($user->add()){
		    	$this->success("添加成功",U(GROUP_NAME.'/User/index'));
		    }else{
		    	$this->error('添加失败');
		    }
		}
		
		//友情链接修改
		public function update(){
			isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
			$user = M('user')->select($id);
			$this->user = $user[0];
			$this->display();
		}

		//友情链接修改表单处理
		public function updateRun(){
			isset($_POST['id']) ? $id = $_POST['id'] : $this->error('非法操作');
			$oldavatar = M('user')->where(array('id'=>$id))->getField('avatar');
			
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =  './Uploads/avatar/';// 设置附件上传目录
			$upload->upload();
			$info =  $upload->getUploadFileInfo();

			$info[0]['savename'] ? $avatar=$info[0]['savename'] : $avatar=$oldavatar;

			if ($_POST['password']) {
				if($_POST['password'] != $_POST['notpassword']) $this->error("两次输入密码错误");
				$data = array(
					'id' => $_POST['id'],
					'username' => $_POST['username'],
					'password' => md5($_POST['password']),
					'avatar' => $avatar,
					);
				if(M('user')->save($data)){
					$this->success("修改成功",U(GROUP_NAME.'/User/index'));
				}else{
					$this->error("修改失败");
				}
			}else{
				$data = array(
					'id' => $_POST['id'],
					'username' => $_POST['username'],
					'avatar' => $avatar,
					);
				if(M('user')->save($data)){
					$this->success("修改成功",U(GROUP_NAME.'/User/index'));
				}else{
					$this->error("修改失败");
				}
			}

		}

		//友情链接删除
		public function delete(){
			isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
			if(M('user')->where('id='.$id)->delete()){
				$this->success("删除成功",U(GROUP_NAME.'/User/index'));
			}else{
				$this->error("删除失败");
			}
		}



	}


?>