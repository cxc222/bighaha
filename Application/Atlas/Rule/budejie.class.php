<?php
namespace Atlas\Rule;

class budejie extends \Atlas\Rule\CollectRule{
	private $zindex = 1;
	private $start_id;
	private $url;
	private $page_Count;
	private $page_suffix;
	private $id;
	//private $atlasCollection;
	
	/**
	 * 
	 * 
	 */
	function executeRule($atlasCollectionData){
		//$this->atlasCollection = $this->atlasCollectionModel->find($atlasCollectionData['id']);
		$this->id = $atlasCollectionData['id'];
		$this->url = $atlasCollectionData['url'];
		$this->page_Count = $atlasCollectionData['page_count'];
		$this->page_suffix = $atlasCollectionData['page_suffix'];
		$this->start_id = $atlasCollectionData['start_id'];
		
		//循环读取页面
		for ($i = 1; $i<=$this->page_Count; $i++){
			$siteUrl = str_replace($this->page_suffix,$i,$this->url);
			$this->snoopy->fetch($siteUrl); //获取所有内容
			$results = $this->snoopy->results;
			$html = str_get_html($results);
			foreach ($html->find('.web_left') as $webLeft){
				foreach ($webLeft->find('.post-body') as $postbody){
					
					$img = $postbody->find('img',0);
					$src = $img->src;
					$alt = $img->alt;
					$id = str_replace('pic-',' ',$img->id);
					
					//判断是否结束
					$this->finish($id);
					
					$this->Fast($this->zindex,$id);	//首次采集, 记录ID号
					
					//开始下载
					$info = $this->download($src);
					if($info){
					    //保存数据库
					    $this->save($alt, $info['id']);
					}
					
					//$file = 'Uploads/atlas/' . basename($instance->url);
					/* $file = $diskPath . basename($src);
					$pathName = basename($src);
		
					$curl_down = $curl->download($img->src, $diskPath.$pathName);
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
								//C('PICTURE_UPLOAD_DRIVER'),
								'qiniu',
								C("UPLOAD_QINIU_CONFIG")
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
								$this->zindex++;
							}
						}
						
					} */
				}
			}
		}
		
		
	}
	
	/**
	 * 第一次 采集, 记录ID号
	 * 
	 * @param unknown $index
	 * @param int		$id	需要记录的id
	 */
	function Fast($index,$id){
		if($index == 1 && $this->zindex == 1){
			//首次采集, 记录ID号
			$this->atlasCollectionModel->where(array('id'=>$this->id))->setField('start_id',$id);
		}
	}
	
	
	function finish($id){
	    if($id == $this->start_id){
	        //$this->success('采集成功, 成功数: '.$this->zindex,U('admin/atlas/index'));
	        return true;
	    }
	}
	
	function Success(){
	    
	}
}