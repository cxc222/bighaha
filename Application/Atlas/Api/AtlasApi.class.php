<?php
namespace Atlas\Api;

use Admin\Model\AddonsModel;
use Common\Api\Api;
use Common\Exception\ApiException;
use Think\Hook;

class AtlasApi extends Api {
	
	private $atlasModel;
	
	public function __construct() {
		// 模型名称请使用完整路径，否则其他应用中无法调用接口。
		$this->atlasModel = D('Atlas/Atlas');
	}
	
	/**
	 * 获取全部 图集列表
	 * @param number $page
	 * @param number $count
	 * @param unknown $map
	 * @param number $loadCount
	 * @param number $lastId
	 * @param string $keywords
	 */
	public function listAllAtlas($page = 1, $count = 30, $map = array()){
		$map['status'] =  1;
		$model = $this->atlasModel;
		$list = $model->field('id')->where($map)->order('addtime desc')->page($page, $count)->select();
		
		//获取每个微博详情
		foreach ($list as &$e) {
			$e = $this->getAtlasStructure($e['id']);
		}
		unset($e);
		//返回微博列表
		return $this->apiSuccess('获取成功', array('list' => arrayval($list), 'lastId' => $list[count($list) - 1]['id']));
	}
	
	/**
	 * 返回图集详情
	 * @param unknown $id
	 */
	public  function getAtlasStructure($id){
		//$atlas = S('atlas_' . $id);
		if (empty($atlas)) {
			//没有缓存重新查询
			$atlas = $this->atlasModel->find($id);
			//获取图片信息
			
			$atlas = array(
					'id' => intval($atlas['id']),
					'content' => strval($atlas['content']),
					'addtime' => intval($atlas['addtime']),
					'can_delete' => 0,
					'user' => $this->getUserStructure($atlas['uid']),
					'uid' => $atlas['uid'],
					'fetchContent' => ''
			);
			S('atlas_' . $id, $atlas);
		}
		$atlas['can_delete']=$this->canDeleteAtlas($atlas);
		
		return $atlas;
	}
	
	private function canDeleteAtlas($atlas)
	{
		//如果是管理员，则可以删除微博
		/* if (is_administrator(get_uid()) || check_auth('deleteWeibo')) {
			return true;
		} */
		
		//如果是自己发送的微博，可以删除微博
		if ($atlas['uid'] == get_uid()) {
			return true;
		}
	
		//返回，不能删除微博
		return false;
	}
}