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

    static $_aid;
    static $_url;
    static $_vest_uids;
	
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
     * @param $atlasCollection  返回 采集的数据列表
     *
     */
    function _parameter($atlasCollection){
        self::$_aid = $atlasCollection['id'];
        self::$_url = $atlasCollection['url'];
        self::$_vest_uids = $atlasCollection['vest_uids'];
    }
	
	/**
	 * 工场模式加载采集类
	 * 
	 */
	function execute($aId){
        set_time_limit(0);
		$atlasCollection = $this->atlasCollectionModel->find($aId);

		if(!$atlasCollection){
            return false;
        }

		/* $url = $atlasCollection['url'];
		$page_suffix = '{page}';
		$page_Count = $atlasCollection['page'];	//页码 */

        $this->_parameter($atlasCollection);    //赋值

		$atlasCollectionData['id'] = $aId;
		$atlasCollectionData['url'] = $atlasCollection['url'];
		$atlasCollectionData['page_count'] = $atlasCollection['page'];
		$atlasCollectionData['page_suffix'] = '{page}';
		$atlasCollectionData['start_id'] = $atlasCollection['start_id'];
		
		$class = 'Atlas\Rule\\'.$atlasCollection['className'];
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
     * @param int $type
     */
	function save($content,$image_id,$uid=0,$type=1){

        //随机马甲
	    $_data['uid'] = $uid?$uid:$this->getRandomVest(self::$_vest_uids);
	    $_data['content'] = $content;
	    $_data['image_id'] = $image_id;
	    $_data['addtime'] = strtotime($this->randomDate(date("Y-m-d",strtotime("-1 month"))));  //随机时间
	    $_data['status'] = 1;
        $_data['type'] = $type;
        if($this->atlasModel->add($_data)){
            $this->zindex++;
        }
	   /* if($this->atlasModel->create($_data) && ( $this->atlasModel->add())){
	        $this->zindex++;
	    }*/
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

    /**
     *   生成某个范围内的随机时间
     * @param string $begintime  起始时间 格式为 Y-m-d H:i:s
     * @param string $endtime   结束时间 格式为 Y-m-d H:i:s
     * @return bool|string
     */
    function randomDate($begintime, $endtime="") {
        $begin = strtotime($begintime);
        $end = $endtime == "" ? mktime() : strtotime($endtime);
        $timestamp = rand($begin, $end);
        return date("Y-m-d H:i:s", $timestamp);
    }


    /**
     * 获取随机马甲
     *
     * @param $uids 用户uid, 多个 , 号隔开
     * @return mixed
     */
    function getRandomVest($uids){
        /** @var TYPE_NAME $uids */
        $ids = trim($uids, ',');
        /** @noinspection PhpParamsInspection */
        $ids = explode(",",$ids);
        if($ids){
            $uid = $this->_randomVest($ids);
        }
        return $uid;
    }

    /**
     * @param $uids
     * @param $rmUids
     * @return mixed
     */
    private function _randomVest(& $uids,$rmUids){
        if ($rmUids) {
            //去掉其中的数组
           // $uids = array_diff($uids,$rmUids);
           unset($uids[array_search($rmUids,$uids)]);
        }
        $randId = array_rand($uids);
        $uid = $uids[$randId];
        $user = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url', 'icons_html'), $uid);
        if(!$user){
            //不存在这个用户, 重新随机
            return $this->_randomVest($uids,$uid);
        }
        return $uid;
    }

}