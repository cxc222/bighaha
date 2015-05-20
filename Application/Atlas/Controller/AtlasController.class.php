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


class AtlasController extends AdminController
{
	protected $atlasModel;
	protected $qiniu;
	public $error;
	
	function _initialize()
	{
		$this->meta_title = '图集管理';
		$this->atlasModel = D('Atlas/Atlas');
	
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
	
	/* public function config()
	 {
	$admin_config = new AdminConfigBuilder();
	$data = $admin_config->handleConfig();
	
	$admin_config->title('图集基本设置')
	->keyBool('NEED_VERIFY', '创建活动是否需要审核','默认无需审核')
	->buttonSubmit('', '保存')->data($data);
	$admin_config->display();
	} */
	
	
	
	/**
	 * 采集临时数据库里面的数据
	 *
	 */
	function collection(){
		set_time_limit(0);
		 
		$url = 'http://www.budejie.com/';
		$page_suffix = '{page}';
		$page_Count = 2;	//页码
		 
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
		for ($i = 1; $i<$page_Count; $i++){
			$siteUrl = str_replace('{page}',$i,$url);
			$snoopy->fetch($siteUrl); //获取所有内容
			$results = $snoopy->results;
			$html = str_get_html($results);
			foreach ($html->find('.web_left') as $webLeft){
				foreach ($webLeft->find('.post-body') as $postbody){
					if($i == 1 && $zindex == 1){
						//首次采集, 记录ID号
							
					}
					$img = $postbody->find('img',0);
					$src = $img->src;
					$alt = $img->alt;
					$id = str_replace('pic-',' ',$img->id);
	
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
						$info = $Picture->moveUpload(
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
							if($this->atlasModel->create($_data) && ($id = $this->atlasModel->add())){
								$zindex++;
							}
						}
					}
				}
			}
			$html->clear();	//清理
		}
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