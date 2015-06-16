<?php
namespace Atlas\Rule;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class budejie_duanzi extends \Atlas\Rule\CollectRule{
	private $start_id;
	private $url;
	private $page_Count;
	private $page_suffix;
	private $id;
	//private $atlasCollection;

    /**
     *
     * @param $atlasCollectionData
     * @return int
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
					$p = $postbody->find('p',0);
					$id = str_replace('pic-',' ',$p->id);
                    $content = $p->innertext;

					//判断是否结束
					if($this->finish($id)){
                        //跳出循环
                        break 3;
                    }

                    /** @noinspection PhpParamsInspection */
                    $this->Fast($this->zindex,$id);	//首次采集, 记录ID号
					//开始下载 存文本, 不需要下载
					/*$info = $this->download($src);
					if($info){
					    //保存数据库
					    $this->save($alt, $info['id']);
					}*/
                    $this->save($content,0,1,2);
				}
			}
		}
        $html->clear();	//清理
        $this->Success($this->id,$id);  //最后一个的时候记录
		return $this->zindex;
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
	

}