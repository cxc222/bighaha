<?php
/**
 * 图集后台控制器
 * 
 * @author Administrator
 * @version 1.0
 */
namespace Admin\Controller;

use Think\Upload\Driver\Qiniu\QiniuStorage;
use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;
use Atlas\Lib;
use Atlas\Lib\Picture;


class AtlasController extends AdminController
{
	protected $atlasModel;
	protected $atlasCollectionModel;
	protected $qiniu;
	public $error;
	
	function _initialize()
	{
		$this->meta_title = '图集管理';
		$this->atlasModel = D('Atlas/Atlas');
		$this->atlasCollectionModel = D('Atlas/Atlas_collection');
		
		$config = array(
				'accessKey'=>'WPWs-mQSibJXZd7m_kL_cM0hwTIMCyFjzvgTFeRq',
				'secrectKey'=>'TTUZUuWL8jug5LzxtQGwCPuVmN8-9DXMeFSrDzBa',
				'bucket'=>'bighaha',
				'domain'=>'7xih3v.com1.z0.glb.clouddn.com'
		);
		$this->qiniu = new QiniuStorage($config);
		parent::_initialize();
	}
	
	public function index($page = 1, $r = 10)
	{
		//读取列表
		$map = array('status' => 1);
		$model = $this->atlasModel;
		$list = $model->where($map)->order('id desc')->page($page, $r)->select();
		unset($li);
		$totalCount = $model->where($map)->count();
	
		//显示页面
		$builder = new AdminListBuilder();
	
		$attr['class'] = 'btn ajax-post';
		$attr['target-form'] = 'ids';
	
		$builder->title('内容管理')
		->buttonNew(U('Atlas/editAtlas'))
		->setStatusUrl(U('setEventContentStatus'))
		->buttonDisable('', '审核不通过')
		->buttonDelete()
		->button('设为推荐', array_merge($attr, array('url' => U('doRecommend', array('tip' => 1)))))
		->button('取消推荐', array_merge($attr, array('url' => U('doRecommend', array('tip' => 0)))))
	
		->button('采集数据',array('href'=>U('collection')))	//采集数据
	
		->keyId()->keyLink('content', '内容', 'Atlas/Index/detail?id=###')
		->keyUid()->keyCreateTime('addtime')->keyStatus()
		->keyMap('is_recommend', '是否推荐', array(0 => '否', 1 => '是'))
		->data($list)
		->pagination($totalCount, $r)
		->display();
	}
	
	public function config(){
	    $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        
	    $builder->title('采集设置');
	    
	    $builder->display();
	}
	
	/**
	 * 采集列表
	 * 
	 */
	function collectionList($page = 1, $r = 10){
	    //读取列表
	    $model = $this->atlasCollectionModel;
	    $list = $model->order('id desc')->page($page, $r)->select();
	    unset($li);
	    $totalCount = $model->count();
	    
	    //显示页面
	    $builder = new AdminListBuilder();
	    
	    $attr['class'] = 'btn ajax-post';
	    $attr['target-form'] = 'ids';
	    
	    $builder->title('内容管理')
	    ->buttonNew(U('Atlas/editCollection'))
	    ->buttonDelete()
	    ->keyId()->keyText('name', '名称')    
	    ->keyText('start_id','上次采集开始id')
	    ->keyText('end_id','上次采集结束id')
	    ->keyCreateTime('addtime')
	    ->keyDoActionEdit('Atlas/editCollection?id=###')
	    ->keyDoActionEdit('Atlas/collection?id=###','采集')
	    ->data($list)
	    ->pagination($totalCount, $r)
	    ->display();
	}
	
	/**
	 * 增加采集点
	 * 
	 */
	function editCollection(){
	    $aId=I('id',0,'intval');
	    $title=$aId?"编辑":"新增";
	    if(IS_POST){
	        $aId&&$data['id']=$aId;
            $data['name']=I('post.name','','op_t');
            $data['url']=I('post.url','','op_t');
            $data['page']=I('post.page',1,'intval');
            
            $data['start_id']=I('post.start_id',0,'intval');
            $data['end_id']=I('post.end_id',0,'intval');
            $this->_checkOk($data);
            
            if($data['id']){
                $result = $this->atlasCollectionModel->save($data);
            }else{
                $data['addtime']=time();
                $result = $this->atlasCollectionModel->add($data);
                action_log('add_atlas_collection', 'Atlas', $res, is_login());
            }
            
            //$result=$this->atlasCollectionModel->editData($data);
            
            if($result){
                $aId=$aId?$aId:$result;
                $this->success($title.'成功！',U('Atlas/editCollection',array('id'=>$aId)));
            }else{
                $this->error($title.'失败！',$this->atlasCollectionModel->getError());
            }
	    }else{
	        if($aId){
	            $data=$this->atlasCollectionModel->find($aId);
	        }
	        $builder=new AdminConfigBuilder();
	        $builder->title($title.'采集项目')
	        ->data($data)
	        ->keyId()
	        ->keyText('name','名称')
	        ->keyText('url','采集URL')
	        ->keyText('page','采集页数')
	        ->keyText('start_id','上次采集开始id')
	        ->keyText('end_id','上次采集结束id')
	        ->buttonSubmit()->buttonBack()
	        ->display();
	    }
	}
	
	private function _checkOk($data=array()){
	    if(!mb_strlen($data['name'],'utf-8')){
	        $this->error('名称不能为空！');
	    }
	    if(!mb_strlen($data['url'],'utf-8')){
	        $this->error('采集URL不能为空！');
	    }
	    return true;
	}
	
	/**
	 * 采集临时数据库里面的数据
	 *
	 */
	function collection(){
	    $aId=I('id',0,'intval');
	    
	    $atlasCollection = $this->atlasCollectionModel->find($aId);
		set_time_limit(0);
		
		$url = $atlasCollection['url'];
		$page_suffix = '{page}';
		$page_Count = $atlasCollection['page'];	//页码
		 
		$PictureClass = new Picture();
		//保存Model
		$atlas_configModel = D('atlas_config');
		$Picture = D('Picture');
		/* 调用文件上传组件上传文件 */
		$pic_driver = C('PICTURE_UPLOAD_DRIVER');
		 
		//保存路径	Uploads/atlas/005OPWbujw1ergwtgwamig306403dhdt.gif
		$diskPath = 'Uploads/atlas/';
		 
		if (!file_exists($diskPath)){	//判断目录不存在, 自动创建
			mkdir($diskPath, 0777,true);
		}
		 
		Vendor('Curl.Curl');
		require_once('ThinkPHP/Library/Vendor/Snoopy/Snoopy.class.php');
		require_once('ThinkPHP/Library/Vendor/simplehtmldom/simple_html_dom.php');
		//require_once('ThinkPHP/Library/Vendor/Curl/Curl.php');
		 
		//开始下载
		$curl = new \Curl\Curl();
		$snoopy = new \Snoopy;
		$url = $url.$page_suffix;
		 
		$zindex = 1;	//总共采集多少条,

		//循环读取页面
		for ($i = 1; $i<=$page_Count; $i++){
			$siteUrl = str_replace($page_suffix,$i,$url);
			$snoopy->fetch($siteUrl); //获取所有内容
			$results = $snoopy->results;
			$html = str_get_html($results);
			foreach ($html->find('.web_left') as $webLeft){
				foreach ($webLeft->find('.post-body') as $postbody){
					
					$img = $postbody->find('img',0);
					$src = $img->src;
					$alt = $img->alt;
					$id = str_replace('pic-',' ',$img->id);
					

					//判断是否结束
					if($id == $atlasCollection['start_id']){
					    $this->success('采集成功, 成功数: '.$zindex,U('admin/atlas/index'));
					}

					if($i == 1 && $zindex == 1){
					    //首次采集, 记录ID号
					    $this->atlasCollectionModel->where(array('id'=>$aId))->setField('start_id',$id);
					}
					
					//开始下载
					//$file = 'Uploads/atlas/' . basename($instance->url);
					$file = $diskPath . basename($src);
					$pathName = basename($src);
	
					$curl_down = $curl->download($img->src, $diskPath.$pathName);
					/* $curl_down = $curl->download($img->src,  function($instance, $tmpfile) {
					 //本地保存成功
							$file = 'Uploads/atlas/' . basename($instance->url);
							$pathName = basename($instance->url);
								
							//执行保存文件
							$fh = fopen($file, 'wb');
							stream_copy_to_stream($tmpfile, $fh);
							fclose($fh);
							//return array('file'=>$file,'name'=>$pathName);
							}); */
					//下载结束
					if($curl_down){
						$filePath = ROOT_PATH.'/'.$file;
						//模拟一组 $_FILES 格式
						$fileGBK = iconv('UTF-8','GB2312',$filePath);
						$fileInfo['size'] = filesize($fileGBK);
						$fileInfo['name'] = $pathName;
						$fileInfo['error'] = 0;
						$fileInfo['type'] = mime_content_type($fileGBK);
						$fileInfo['tmp_name'] = $fileGBK;
						
						//执行文件移动
						$info = $PictureClass->moveUpload(
								$fileInfo,
								C('PICTURE_UPLOAD'),
								C('PICTURE_UPLOAD_DRIVER'),
								C("UPLOAD_{$pic_driver}_CONFIG")
						); //TODO:上传到远程服务器
						
						if(!$info){
							//$this->error[] = '';
						}else{
							//暂停60秒
							//sleep(60);
							$_data['uid'] = 1;
							$_data['content'] = $alt;
							$_data['image_id'] = $info['id'];
							$_data['addtime'] = time();
							$_data['status'] = 1;
							if($this->atlasModel->create($_data) && ( $this->atlasModel->add())){
								$zindex++;
							}
						}
					}
				}
			}
		}
		$html->clear();	//清理
		$this->atlasCollectionModel->where(array('id'=>$aId))->setField('end_id',$id);
		/* ------------------------------------------------------------------------------------------------------------- */
		/* $CollectionModel = D('Collection');
		 $CollectionConfigModel = D('CollectionConfig');
		$MaxId = $CollectionConfigModel->getField("MaxId");
		$MaxId ? $where['pid'] = array('gt',$MaxId) : '';
		$collections = $CollectionModel->where($where)->select();
		 
		// 调用文件上传组件上传文件
		$Picture = D('Picture');
		$pic_driver = C('PICTURE_UPLOAD_DRIVER');
		 
		$zindex = 1;
		foreach ($collections as $k => $v){
		$html = get_pregImg($v['content']);
		$pathName = $html[3][0];
		$file = $CollectionModel->pathDir.$pathName;
		if($pathName && file_exists(iconv('UTF-8','GB2312',$file))){
		//模拟一组 $_FILES 格式
		$fileGBK = iconv('UTF-8','GB2312',$file);
		$fileInfo['size'] = filesize($fileGBK);
		$fileInfo['name'] = $pathName;
		$fileInfo['error'] = 0;
		$fileInfo['type'] = mime_content_type($fileGBK);
		$fileInfo['tmp_name'] = $fileGBK;
		 
		$info = $Picture->moveUpload(
				$fileInfo,
				C('PICTURE_UPLOAD'),
				C('PICTURE_UPLOAD_DRIVER'),
				C("UPLOAD_{$pic_driver}_CONFIG")
		); //TODO:上传到远程服务器
		 
		if(!$info){
		//$this->error[] = '';
		}else{
		$_data['uid'] = 1;
		$_data['content'] = $v['title'];
		$_data['image_id'] = $info['id'];
		$_data['addtime'] = time();
		$_data['status'] = 1;
		if($this->atlasModel->create($_data) && ($id = $this->atlasModel->add())){
		$zindex++;
		}
		}
		}else{
		//空文件
	
		}
		}
		//清空缓存库
		$CollectionModel->delete(); */
		//模块/控制器/操作
		$this->success('采集成功, 成功数: '.$zindex,U('admin/atlas/index'));
	}
	
	/**
	 * 审核页面
	 * @param int $page
	 * @param int $r
	 * autor:xjw129xjt
	 */
	public function verify($page = 1, $r = 10)
	{
		//读取列表
		$map = array('status' => 0);
		$model = $this->eventModel;
		$list = $model->where($map)->page($page, $r)->select();
		unset($li);
		$totalCount = $model->where($map)->count();
	
		//显示页面
		$builder = new AdminListBuilder();
		$attr['class'] = 'btn ajax-post';
		$attr['target-form'] = 'ids';
		$builder->title('审核内容')
		->setStatusUrl(U('setEventContentStatus'))->buttonEnable('', '审核通过')->buttonDelete()
		->keyId()->keyLink('title', '标题', 'Event/Index/detail?id=###')->keyUid()->keyCreateTime()->keyStatus()
		->data($list)
		->pagination($totalCount, $r)
		->display();
	}
	
	/**
	 * 设置状态
	 * @param $ids
	 * @param $status
	 * autor:xjw129xjt
	 */
	public function setEventContentStatus($ids, $status)
	{
		$builder = new AdminListBuilder();
		if ($status == 1) {
			foreach ($ids as $id) {
				$content = D('Event')->find($id);
				D('Common/Message')->sendMessage($content['uid'], "管理员审核通过了您发布的内容。现在可以在列表看到该内容了。", $title = '专辑内容审核通知', U('Event/Index/detail', array('id' => $id)), is_login(), 2);
				/*同步微博*/
				$user = query_user(array('username', 'space_link'), $content['uid']);
				$weibo_content = '管理员审核通过了@' . $user['username'] . ' 的内容：【' . $content['title'] . '】，快去看看吧：' . "http://$_SERVER[HTTP_HOST]" . U('Event/Index/detail', array('id' => $content['id']));
				$model = D('Weibo/Weibo');
				$model->addWeibo(is_login(), $weibo_content);
				/*同步微博end*/
			}
	
		}
		$builder->doSetStatus('Event', $ids, $status);
	
	}
	
	/**
	 * editAtlas  编辑图集
	 * @param int $id
	 * @param string $title
	 * @param int $create_time
	 * @param int $status
	 * @param int $allow_user_group
	 * @param int $logo
	 * @param int $type_id
	 * @param string $detail
	 * @param int $type
	 * @author:xjw129xjt xjt@ourstu.com
	 */
	public function editAtlas($id = 0,$content=null,$image_id=0,$status=1)
	{
		if (!IS_POST) {
			//判断是否为编辑模式
			$isEdit = $id ? true : false;
			//如果是编辑模式，读取群组的属性
			if ($isEdit) {
				$atlas = $this->atlasModel->where(array('id' => $id))->find();
			} else {
				$atlas = array('addtime' => time(), 'status' => 1);
			}
	
			//显示页面
			$builder = new AdminConfigBuilder();
			$builder
			->title($isEdit ? '编辑图集' : '新增图集')
			->keyId()->keyTextArea('content', '图集介绍')
			->keySingleImage('image_id', '图片', '图片大小不能大于3M,尺寸建议640像素*640像素')->keyCreateTime('addtime')
			->keyStatus()
			->data($atlas)
			->buttonSubmit(U('editAtlas'))->buttonBack()
			->display();
		} else { //判断是否为编辑模式
			 
			$isEdit = $id ? true : false;
			//生成数据
			$data = array('content' => $content, 'addtime' => time(), 'status' => $status, 'image_id' => $image_id);
			//写入数据库
			$model = $this->atlasModel;
			if ($isEdit) {
				$data['id'] = $id;
				// $data = $model->create($data);
				$result = $model->where(array('id' => $id))->save($data);
			} else {
				$data['uid']=1;
				$model->create($data);
				$result = $model->add($data);
				if (!$result) {
					$this->error('创建失败');
				}
			}
			S('atlas_list', null);
			//返回成功信息
			$this->success($isEdit ? '编辑成功' : '保存成功');
		}
	}
	
}