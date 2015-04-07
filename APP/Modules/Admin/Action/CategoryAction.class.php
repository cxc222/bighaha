<?php

	/**
	* 文章频道栏目控制器
	*/
	class CategoryAction extends CommonAction{
	
		//分类列表视图
		public function index(){
			import('Class.Category',APP_PATH);
			$cate = M('cate')->order('sort ASC')->select();
			$this->cate = Category::unlimitedForLevel($cate,'&nbsp;&nbsp;--');
			$this->display();
		}	

		//添加分类视图
		public function add(){
			$this->pid = I('pid',0,'intval');
			$this->display();
		}


		//添加分类的表单处理
		public function addRun(){
			if(M('cate')->add($_POST)){
				$this->success("添加成功",U(GROUP_NAME.'/Category/index'));
			}else{
				$this->error('添加失败');
			}
		}

		//排序处理
		public function sort(){
			$db = M('cate');
			foreach ($_POST as $id => $sort) {
				$db->where(array('id'=>$id))->setField('sort',$sort);
			}
			$this->redirect(GROUP_NAME.'/Category/index');
		}

		//修改栏目
		public function update(){
			isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
			$cate = M('cate')->select($id);
			$this->cate = $cate[0];

			$this->display();
		}

		//修改栏目表单处理
		public function updateRun(){
			$data = array(
				'id' => $_POST['id'],
				'name' => $_POST['name'],
				'catetitle' => $_POST['catetitle'],
				'filename' => $_POST['filename'],
				'sort' => $_POST['sort'],
				'keywords' => $_POST['keywords'],
				'description' => $_POST['description']
				);
			if(M('cate')->save($data)){
				$this->success("修改成功",U(GROUP_NAME.'/Category/index'));
			}else{
				$this->error("修改失败");
			}
		}

		//删除
		public function delete(){
			isset($_GET['id']) ? $id = $_GET['id'] : $this->error('非法操作');
			$cate = M('cate')->select();
			import('Class.Category',APP_PATH);
			$son = Category::getChildsId($cate,$id);
			if($son){
				$this->error("有子分类，不能被删除，请先删除子分类");
			}else{
				if(M('cate')->delete($id)){
					$this->success("删除成功",U(GROUP_NAME.'/Category/index'));
				}else{
					$this->error("删除失败");
				}
				
			}

		}

	}

?>