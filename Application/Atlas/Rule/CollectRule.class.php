<?php
/**
 * 基础采集配置
 * 
 */
namespace Atlas\Rule;
use Atlas\Model;

class CollectRule {
	public $curl;
	public $snoopy;
	public $atlasModel;
	public $atlasCollectionModel;
	public $RuleClass;
	public $PictureClass;
	public $diskPath = 'Uploads/atlas/';
    public $zindex = 1;
	
	function __construct() {
		Vendor ( 'Curl.Curl' );
		require_once ('ThinkPHP/Library/Vendor/Snoopy/Snoopy.class.php');
        /** @noinspection SpellCheckingInspection */
        require_once ('ThinkPHP/Library/Vendor/simplehtmldom/simple_html_dom.php');

        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $this->curl = new \Curl\Curl ();
		$this->snoopy = new \Snoopy ();
		$this->atlasModel = D('Atlas/Atlas');
		$this->atlasCollectionModel = D('Atlas/Atlas_collection');
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $this->PictureClass = new \Atlas\Lib\Picture();
		
		if (!file_exists($this->diskPath)){	//判断目录不存在, 自动创建
		    mkdir($this->diskPath, 0777,true);
		}
	}
	
	/**
	 * 工场模式加载采集类
	 * 
	 */
	function execute($aId){
		$atlasCollection = $this->atlasCollectionModel->find($aId);
		
		/* $url = $atlasCollection['url'];
		$page_suffix = '{page}';
		$page_Count = $atlasCollection['page'];	//页码 */
		
		$atlasCollectionData['id'] = $aId;
		$atlasCollectionData['url'] = $atlasCollection['url'];
		$atlasCollectionData['page_count'] = $atlasCollection['page'];
		$atlasCollectionData['page_suffix'] = '{page}';
		$atlasCollectionData['start_id'] = $atlasCollection['start_id'];
		
		$class = '\Atlas\Rule\\'.$atlasCollection['className'];
		$this->RuleClass = new $class();
		return $this->RuleClass->executeRule($atlasCollectionData);
	}

    /**
     * 开启下载图片
     * @param unknown $imgUrl
     * @return bool
     */
	function download($imgUrl){
	    $file = $this->diskPath . basename($imgUrl);
	    $pathName = basename($imgUrl);
	    
	    $curl_down = $this->curl->download($imgUrl, $this->diskPath.$pathName);
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
	        $info = $this->PictureClass->moveUpload(
	            $fileInfo,
	            C('PICTURE_UPLOAD'),
	            //C('PICTURE_UPLOAD_DRIVER'),
	            'qiniu',
	            C("UPLOAD_QINIU_CONFIG")
	        ); //TODO:上传到远程服务器

	        if(!$info){
	            //$this->error[] = '';
	            return false;
	        }else{
	            //暂停60秒
	            //sleep(60);
	            /* $_data['uid'] = 1;
	            $_data['content'] = $alt;
	            $_data['image_id'] = $info['id'];
	            $_data['addtime'] = time();
	            $_data['status'] = 1;
	            if($this->atlasModel->create($_data) && ( $this->atlasModel->add())){
	                $this->zindex++;
	            } */
	            return $info;
	        }
	        
	    }
	    //下载end
	}

    /**
     * 保存到数据库
     * @param $content
     * @param $image_id
     * @param int $uid
     */
	function save($content,$image_id,$uid=1){
	    $_data['uid'] = $uid;
	    $_data['content'] = $content;
	    $_data['image_id'] = $image_id;
	    $_data['addtime'] = time();
	    $_data['status'] = 1;
	    if($this->atlasModel->create($_data) && ( $this->atlasModel->add())){
	        $this->zindex++;
	    }
	}

    /**
     * 成功后的操作
     *
     * @param $aId  采集id
     * @param $id   需要更新上去的id
     */
    function Success($aId,$id){
        $this->atlasCollectionModel->where(array('id'=>$aId))->setField('end_id',$id);
    }
}