<?php
	class SearchAction extends Action{
		
		//视图
		public function index(){
			import('Class.Category',APP_PATH);
			import('ORG.Util.Page');


			isset($_GET['keyword']) ? $keyword = $_GET['keyword'] : $this->error('非法操作');
			$this->keyword = $keyword;
			$condition['title']=array(array('like','%'.$keyword.'%'));

			$count = M('article')->where($condition)->count();
			$page = new Page($count,10);
			$limit = $page->firstRow.','.$page->listRows;
			$page -> setConfig('prev','<'); //这个是更改“上一页”的样式
			$page -> setConfig('next','>');//这个是更改“下一页”的样式
			$page -> setConfig('theme','%upPage% %first% %prePage% %linkPage%  %downPage%');//这个是更改显示在页面上的效果，去掉了总的多少条，第几页的显示内容。

			$this->article = D('ArticleView')->getAll($condition,$limit);
			$this->page = $page->show();
			$this->display('index');
		}

	}
?>