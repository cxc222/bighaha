<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
		//视图
		public function index(){
			$this->focus = M('article')->where(array(array('cid'=>array('IN','1,2,3,4')),'del'=>0,'thumbnail'=>array('NEQ','')))->limit(8)->order('time DESC')->select();
			$this->gif = M('article')->order('time DESC')->where(array('cid'=>1,'del'=>0,'thumbnail'=>array('NEQ','')))->limit(8)->select();
			$this->gifclick = M('article')->order('click DESC')->where(array('cid'=>1,'del'=>0,'thumbnail'=>array('NEQ','')))->limit(13)->select();
			$this->gifding = M('article')->order('ding DESC')->where(array('cid'=>1,'del'=>0,'thumbnail'=>array('NEQ','')))->limit(13)->select();
			$this->gifcai = M('article')->order('cai DESC')->where(array('cid'=>1,'del'=>0,'thumbnail'=>array('NEQ','')))->limit(13)->select();
			
			$this->pic = M('article')->order('time DESC')->where(array('cid'=>2,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(8)->select();
			$this->picclick = M('article')->order('click DESC')->where(array('cid'=>2,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(13)->select();
			$this->picding = M('article')->order('ding DESC')->where(array('cid'=>2,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(13)->select();
			$this->piccai = M('article')->order('cai DESC')->where(array('cid'=>2,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(13)->select();
			
			$this->video = M('article')->order('time DESC')->where(array('cid'=>4,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(8)->select();
			$this->videoclick = M('article')->order('click DESC')->where(array('cid'=>4,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(13)->select();
			$this->videoding = M('article')->order('ding DESC')->where(array('cid'=>4,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(13)->select();
			$this->videocai = M('article')->order('cai DESC')->where(array('cid'=>4,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(13)->select();
			

			$this->app = M('article')->order('time DESC')->where(array('cid'=>2,'del'=>0,array('thumbnail'=>array('NEQ',''))))->limit(8)->select();
			$this->rank = M('article')->order('time DESC')->where(array('del'=>0,array('cid'=>array('IN','1,2,3,4,5,6')),array('thumbnail'=>array('NEQ',''))))->limit(8)->order('click DESC')->select();
			$this->display('index');
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

		public function verify(){
			import('Class.Image',APP_PATH);
			Image::verify();
		}




}




?>