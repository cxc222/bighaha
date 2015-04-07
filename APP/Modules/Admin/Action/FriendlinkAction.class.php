<?php
	class FriendlinkAction extends CommonAction {
		
		//友情链接列表
		public function index(){
			$this->friendlink = M("friendlink")->order('sort ASC')->select();
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
			$upload->savePath =  './Uploads/friendlink/';// 设置附件上传目录
			$upload->upload();
			$info =  $upload->getUploadFileInfo();

			$info[0]['savename'] ? $logo=$info[0]['savename'] : $logo='';

			// 保存表单数据 包括附件数据
			$friendlink = M("friendlink"); // 实例化User对象
			$friendlink->create(); // 创建数据对象
			$friendlink->logo = $logo; // 保存上传的照片根据需要自行组装
			// 写入用户数据到数据库
		    if($friendlink->add()){
		    	$this->success("添加成功",U(GROUP_NAME.'/Friendlink/index'));
		    }else{
		    	$this->error('添加失败');
		    }
		}
		
		//友情链接修改
		public function update(){
			isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
			$friendlink = M('friendlink')->select($id);
			$this->friendlink = $friendlink[0];
			$this->display();
		}

		//友情审核或撤消审核
		public function handle(){
			isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
			isset($_GET['type']) ? $data['statu'] = $_GET['type'] : $this->error('非法操作');
			if(M('Friendlink')->where('id = '.$id)->save($data)){
				$this->success("处理成功",U(GROUP_NAME.'/Friendlink/index'));
			}else{
				$this->error("处理失败");
			}
		}

		//友情链接修改表单处理
		public function updateRun(){
			isset($_POST['id']) ? $id = $_POST['id'] : $this->error('非法操作');
			$oldlogo = M('friendlink')->where(array('id'=>$id))->getField('logo');
			
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =  './Uploads/friendlink/';// 设置附件上传目录
			$upload->upload();
			$info =  $upload->getUploadFileInfo();

			$info[0]['savename'] ? $logo=$info[0]['savename'] : $logo=$oldlogo;

			$data = array(
				'id' => $_POST['id'],
				'sitename' => $_POST['sitename'],
				'siteurl' => $_POST['siteurl'],
				'logo' => $logo,
				'sort' => $_POST['sort']
				);
			if(M('Friendlink')->save($data)){
				$this->success("修改成功",U(GROUP_NAME.'/Friendlink/index'));
			}else{
				$this->error("修改失败");
			}
		}

		//友情链接删除
		public function delete(){
			isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
			if(M('Friendlink')->delete($id)){
				$this->success("删除成功",U(GROUP_NAME.'/Friendlink/index'));
			}else{
				$this->error("删除失败");
			}
		}

		//排序处理
		public function sort(){
			$db = M('friendlink');
			foreach ($_POST as $id => $sort) {
				$db->where(array('id'=>$id))->setField('sort',$sort);
			}
			$this->redirect(GROUP_NAME.'/Friendlink/index');
		}


	}


?>