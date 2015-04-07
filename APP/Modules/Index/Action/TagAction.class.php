<?php
	class TagAction extends Action{
		
		public function index(){
			import('Class.Category',APP_PATH);
			import('ORG.Util.Page');


			isset($_GET['tagname']) ? $tagname = $_GET['tagname'] : $this->error('非法操作');
			$this->tagname = $tagname;
			$condition['tagname']= $tagname;

			$count = M('tag')->where($condition)->count();
			$this->count = $count;
			$page = new Page($count,10);
			$limit = $page->firstRow.','.$page->listRows;
			$page -> setConfig('prev','<'); //这个是更改“上一页”的样式
			$page -> setConfig('next','>');//这个是更改“下一页”的样式
			$page -> setConfig('theme','%upPage% %first% %prePage% %linkPage%  %downPage%');//这个是更改显示在页面上的效果，去掉了总的多少条，第几页的显示内容。

			$this->article = D('TagView')->getAll($condition,$limit);
			$this->page = $page->show();
			$this->display('index');
		}

	}
?>