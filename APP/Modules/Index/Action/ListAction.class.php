<?php

	/**
	* 文章列表页控制器
	*/
	class ListAction extends Action{
		
		//视图
		public function index(){
			import('Class.Category',APP_PATH);
			import('ORG.Util.Page');
			$filename = $_GET['cate'];
			$nowid = M('cate')->where(array('filename'=>$filename))->getField('id');
			$this->nowid = $nowid;
			$cateinfo = M('cate')->where(array('filename'=>$filename))->select();
			$this->cateinfo = $cateinfo[0];
			$cate = M('cate')->order('sort')->select();
			$cids = Category::getChildsId($cate,$nowid);
			$cids[] = $nowid;


			//获取同级的所有栏目
			$sisterdata = M('cate')->where(array('id'=>$nowid))->limit(1)->select(); //获取栏目数据
			$spid = $sisterdata[0]['pid']; //获取栏目的父栏目的id
			$this->sisterdata = $sisterdata[0]; //写入模板 
			if($spid == 0){
				$this->sister = Category::getSister($cate,$nowid); //通过父栏目id查找同id的所有其它栏目
			}else{
				$this->sister = Category::getSister($cate,$spid); //通过父栏目id查找同id的所有其它栏目
			}
			


			//分页显示
			$where = array('cid'=>array('IN',$cids));
			$where['del'] = 0;
			$count = M('article')->where($where)->count();
			$page = new Page($count,20);
			$page -> setConfig('prev','<'); //这个是更改“上一页”的样式
			$page -> setConfig('next','>');//这个是更改“下一页”的样式
			$page -> setConfig('theme','%upPage% %first% %prePage% %linkPage%  %downPage%');
			$limit = $page->firstRow.','.$page->listRows;
			$this->page = $page->show();



			$this->parent = Category::getParents($cate,$nowid);

			$this->article = D('ArticleView')->getAll($where,$limit);//获取关联数据
			$this->display();
		}


		public function clickNum(){
			$id = (int) $_GET['id'];
			$click = M('cate')->where(array('id'=>$id))->getField('click');
			echo 'document.write('.$click.')';
		}
		

		//顶
		public function ding(){
			header("Content-Type: text/html;charset=utf-8");
			header("Cache-Control:no-cache");
			$id=$_POST['id'];//获取id
			$ck = "articleding".$id;//获取本页cookie
			if (isset($_COOKIE[$ck])) {
				$res = '{"count":"0"}';
				echo $res;
			}else{
				M('article')->where(array('id'=>$id))->setInc('ding');
				$count = M('article')->where(array('id'=>$id))->getField('ding');
				$res = '{"count":"'.$count.'"}';
				cookie("articleding".$id,"articleding".$id,3600);
				echo $res;	
			}
		}

		//顶
		public function cai(){
			header("Content-Type: text/html;charset=utf-8");
			header("Cache-Control:no-cache");
			$id=$_POST['id'];//获取id
			$ck = "articlecai".$id;//获取本页cookie
			if(isset($_COOKIE[$ck])) {
				$res = '{"count":"0"}';
				echo $res;
			}else{
				M('article')->where(array('id'=>$id))->setInc('cai');
				$count = M('article')->where(array('id'=>$id))->getField('cai');
				$res = '{"count":"'.$count.'"}';
				cookie("articlecai".$id,"articlecai".$id,3600);
				echo $res;	
			}
		}


		

	}


?>