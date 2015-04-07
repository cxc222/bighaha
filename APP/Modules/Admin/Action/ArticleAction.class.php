<?php

class ArticleAction extends CommonAction{
	//列表
	public function index(){
		import('ORG.Util.Page');
		//分页显示
		$count = M('article')->where('del=0')->count();
		$page = new Page($count,20);
		$page -> setConfig('prev','<'); //这个是更改“上一页”的样式
		$page -> setConfig('next','>');//这个是更改“下一页”的样式
		$page -> setConfig('theme','<b>%totalRow%%header%</b> <b>%nowPage%/%totalPage%</b>  %upPage% %first% %prePage% %linkPage% %nextPage% %downPage% %end%');//这个是更改显示在页面上的效果，去掉了总的多少条，第几页的显示内容。
		$limit = $page->firstRow.','.$page->listRows;
		$this->article = D('ArticleRelation')->getArticles($type=0,$limit);
		$this->page = $page->show();
		$this->display();
	}

	//某id列表
	public function lists(){
		import('ORG.Util.Page');
		isset($_GET['id']) ? $cid = $_GET['id'] : $this->error('非法操作');
		$where = array('cid'=>$cid);
		$count = M('article')->where($where)->count();
		$page = new Page($count,20);
		$page -> setConfig('prev','<'); //这个是更改“上一页”的样式
		$page -> setConfig('next','>');//这个是更改“下一页”的样式
		$page -> setConfig('theme','<b>%totalRow%%header%</b> <b>%nowPage%/%totalPage%</b>  %upPage% %first% %prePage% %linkPage% %nextPage% %downPage% %end%');//这个是更改显示在页面上的效果，去掉了总的多少条，第几页的显示内容。
		$limit = $page->firstRow.','.$page->listRows;
		
		$this->article = D('ArticleRelation')->getArticlesId('0',$cid,$limit);
		$this->page = $page->show();
		$this->display();
	}


	//回收站
	public function trach(){
		import('ORG.Util.Page');
		//分页显示
		$count = M('article')->where('del=1')->count();
		$page = new Page($count,20);
		$page -> setConfig('prev','<'); //这个是更改“上一页”的样式
		$page -> setConfig('next','>');//这个是更改“下一页”的样式
		$page -> setConfig('theme','<b>%totalRow%%header%</b> <b>%nowPage%/%totalPage%</b>  %upPage% %first% %prePage% %linkPage% %nextPage% %downPage% %end%');//这个是更改显示在页面上的效果，去掉了总的多少条，第几页的显示内容。
		$limit = $page->firstRow.','.$page->listRows;
		$this->article = D('ArticleRelation')->getArticles($type=1,$limit);
		$this->page = $page->show();
		$this->display();
	}



	//删除或者还原
	public function toTrach(){
		$type = (int) $_GET['type'];
		$msg = $type ? '删除' : '还原';
		$update = array(
			'id'=>(int) $_GET['id'], //找到对应的id
			'del'=> $type	//设置值 
		);
		if(M('article')->save($update)){
			$this->success($msg.'成功！',U(GROUP_NAME.'/Article/index'));
		}else{
			$this->error($msg."失败");
		}

	}

	//彻底删除
	public function delete(){
		$id = (int) $_GET['id'];
		if(M('article')->delete($id)){
			M('article_attr')->where(array('bid'=>$id))->delete();
			$this->success('彻底删除成功！',U(GROUP_NAME.'/Article/index'));
		}else{
			$this->error("彻底删除失败");
		}
	}

	//彻底删除所有回收站的文章
	public function deleteAll(){
		$id = $_POST['id'];
		foreach ($id as $v) {
			$sql = 'DELETE FROM '.C('DB_PREFIX').'article WHERE id='.$v;
			M('article')->query($sql);
		}
		$this->redirect(GROUP_NAME.'/Article/index');  
		
	}

	//添加博文
	public function add(){
		//所属分类
		import('Class.Category',APP_PATH);
		$cate = M('cate')->order('sort')->select();
		$this->cate = Category::unlimitedForLevel($cate);
		//属性
		$this->attr = M('attr')->select();
		$this->user = M('user')->select();//循环出所有会员
		$this->click = mt_rand(118,1288);
		$this->display();
	}

	//添加博文
	public function addgif(){
		//所属分类
		import('Class.Category',APP_PATH);
		$cate = M('cate')->order('sort')->select();
		$this->cate = Category::unlimitedForLevel($cate);
		//属性
		$this->attr = M('attr')->select();
		$this->user = M('user')->select();//循环出所有会员
		$this->click = mt_rand(118,1288);
		$this->display();
	}

	//添加博文表单处理
	public function addRun(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->autoSub = true;
		$upload->subType = 'date';
		$upload->dateFormat = 'Ym';

		$upload->maxSize  = 8145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Uploads/';// 设置附件上传目录

		$upload->upload();

		$info =  $upload->getUploadFileInfo();

		$info[0]['savename'] ? $thumbnail=$info[0]['savename'] : $thumbnail='';
		$del = 0;
		isset($_POST['del']) ? $del = 1 : $del = 0;
		$data = array(
			'title' => $_POST['title'],
			'content' => $_POST['content'],
			'summary' => $_POST['summary'],
			'keyword' => $_POST['keyword'],
			'tag' => $_POST['tag'],
			'del' => $del,
			'username' => $_POST['username'],
			'ding' => mt_rand(27,254),
			'cai' => mt_rand(2,22),
			'thumbnail' => $thumbnail,
			'time' => time(),
			'click' => (int) $_POST['click'],
			'cid' => (int) $_POST['cid']
			);

		$tag = $data['tag'];
		$tag = rtrim($tag);
		$taglist = explode(',',$tag);

		if($bid = M('article')->add($data)){
			if (isset($_POST['tag'])) {
				foreach ($taglist as $v) {
					$dataa['aid'] = $bid;
					$dataa['tagname'] = $v;
					M('tag')->add($dataa);
				}
			}
			$this->success('添加成功',U(GROUP_NAME.'/Article/add'));
		}else{
			$this->error("添加失败");
		}
	}	



	//添加gif表单处理
	public function addgifRun(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->autoSub = true;
		$upload->subType = 'date';
		$upload->dateFormat = 'Ym';

		$upload->maxSize  = 8145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Uploads/';// 设置附件上传目录

		$upload->upload();

		$info =  $upload->getUploadFileInfo();

		$info[0]['savename'] ? $thumbnail=$info[0]['savename'] : $thumbnail='';
		$del = 0;
		isset($_POST['del']) ? $del = 1 : $del = 0;
		$data = array(
			'title' => $_POST['title'],
			'content' => $_POST['content'],
			'summary' => $_POST['summary'],
			'keyword' => $_POST['keyword'],
			'tag' => $_POST['tag'],
			'del' => $del,
			'username' => $_POST['username'],
			'ding' => mt_rand(27,254),
			'cai' => mt_rand(2,22),
			'thumbnail' => $thumbnail,
			'time' => time(),
			'click' => (int) $_POST['click'],
			'cid' => (int) $_POST['cid']
			);

		$tag = $data['tag'];
		$tag = rtrim($tag);
		$taglist = explode(',',$tag);

		if($bid = M('article')->add($data)){
			if (isset($_POST['tag'])) {
				foreach ($taglist as $v) {
					$dataa['aid'] = $bid;
					$dataa['tagname'] = $v;
					M('tag')->add($dataa);
				}
			}
			$this->success('添加成功',U(GROUP_NAME.'/Article/addgif'));
		}else{
			$this->error("添加失败");
		}
	}	



	//修改文章
	public function update(){
		//所属分类
		import('Class.Category',APP_PATH);
		$cate = M('cate')->order('sort')->select();
		$this->cate = Category::unlimitedForLevel($cate);
		$this->user = M('user')->select();//循环出所有会员
		//属性
		$this->attr = M('attr')->select();
		isset($_GET['id']) ? $id = $_GET['id'] : $this->error("非常操作");
		$article = M('article')->find($id);
		$this->cid = M('cate')->find($article['cid']);

		//标签
		$tag = M('tag')->where(array('aid'=>$id))->select();
		foreach ($tag as $v) {
			$taglist .= $v['tagname'].',';
		}
		$this->taglist = substr($taglist,0,-1);

		$this->article = $article;
		$this->display();
	}

	//修改GIF
	public function updategif(){
		//所属分类
		import('Class.Category',APP_PATH);
		$cate = M('cate')->order('sort')->select();
		$this->cate = Category::unlimitedForLevel($cate);
		$this->user = M('user')->select();//循环出所有会员
		//属性
		$this->attr = M('attr')->select();

		isset($_GET['id']) ? $id = $_GET['id'] : $this->error("非常操作");
		$article = M('article')->find($id);
		$this->cid = M('cate')->find($article['cid']);
		
		//标签
		$tag = M('tag')->where(array('aid'=>$id))->select();
		foreach ($tag as $v) {
			$taglist .= $v['tagname'].',';
		}
		$this->taglist = substr($taglist,0,-1);

		$this->article = $article;
		$this->display();
	}

	//添加博文表单处理
	public function updateRun(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->autoSub = true;
		$upload->subType = 'date';
		$upload->dateFormat = 'Ym';

		$upload->maxSize  = 8145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Uploads/';// 设置附件上传目录

		$upload->upload();

		$info =  $upload->getUploadFileInfo();

		$info[0]['savename'] ? $thumbnail=$info[0]['savename'] : $thumbnail='';
		isset($_POST['del']) ? $del = 1 : $del = 0;

		if ($_FILES['thumbnail']['name']) {
			$data = array(
				'id' => $_POST['id'],
				'title' => $_POST['title'],
				'title' => $_POST['title'],
				'keyword' => $_POST['keyword'],
				'tag' => $_POST['tag'],
				'thumbnail' => $thumbnail,
				'del' => $del,
				'content' => $_POST['content'],
				'summary' => $_POST['summary'],
				'username' => $_POST['username'],
				'time' => time(),
				'click' => (int) $_POST['click'],
				'cid' => (int) $_POST['cid']
			);
		}else{
			$data = array(
				'id' => $_POST['id'],
				'title' => $_POST['title'],
				'title' => $_POST['title'],
				'keyword' => $_POST['keyword'],
				'tag' => $_POST['tag'],
				'del' => $del,
				'content' => $_POST['content'],
				'summary' => $_POST['summary'],
				'username' => $_POST['username'],
				'time' => time(),
				'click' => (int) $_POST['click'],
				'cid' => (int) $_POST['cid']
			);
		}

		//tag处理
		$id = $data['id'];
		M('tag')->where('aid='.$id)->delete();//删除原tag
		$tag = $data['tag'];
		$tag = rtrim($tag);
		$taglist = explode(',',$tag);

		if($bid = M('article')->save($data)){
			if (isset($_POST['tag'])) {
				foreach ($taglist as $v) {
					$dataa['aid'] = $id;
					$dataa['tagname'] = $v;
					M('tag')->add($dataa);
				}
			}
			$this->success('修改成功',U(GROUP_NAME.'/Article/index'));

		}else{
			$this->error("添加失败");
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
			// import('Class.Image',APP_PATH);
			// Image::water('./Uploads/'.$info[0]['savename']);
			//if ($info[0]['extension'] != 'gif') { //判断，如果是gif就不加水印
				if ($info[0]['size'] > 40000) { //判断，大于40kb才加水印
					import('Class.Image',APP_PATH);
					Image::water('./Uploads/'.$info[0]['savename']);
				}
			//}
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


	//编辑器图片gif上传处理
	public function uploadgif(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile($config);
		$upload->autoSub = true;
		$upload->subType = 'date';
		$upload->dateFormat = 'Ym';


		if ($upload->upload('./Uploads/')) {
			$info = $upload->getUploadFileInfo();
			// import('Class.Image',APP_PATH);
			// Image::water('./Uploads/'.$info[0]['savename']);
			// if ($info[0]['extension'] != 'gif') { //判断，如果是gif就不加水印
			// 	if ($info[0]['size'] > 40000) { //判断，大于40kb才加水印
			// 		import('Class.Image',APP_PATH);
			// 		Image::water('./Uploads/'.$info[0]['savename']);
			// 	}
			// }
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
