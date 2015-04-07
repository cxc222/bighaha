<?php
	class AttributeAction extends CommonAction{
		public function index(){
			$this->attr = m('attr')->select();
			$this->display();
		}	

		//添加属性视图
		public function add(){
			$this->display();

		}	

		//添加属性处理
		public function addRun(){
			if(M('attr')->add($_POST)){
				$this->success('添加成功',U(GROUP_NAME.'/Attribute/index'));
			}else{
				$this->error("添加失败");
			}
		}

		//删除属性处理
		public function delete(){
			$id = $_GET['id'];
			if(M('attr')->delete($id)){
				$this->success('删除成功',U(GROUP_NAME.'/Attribute/index'));
			}else{
				$this->error("删除失败");
			}
		}


	}
?>