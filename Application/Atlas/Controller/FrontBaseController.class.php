<?php
/**
 * 前台基础类
 * 
 */
namespace Atlas\Controller;

use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class FrontBaseController extends Controller{

	/* 空操作，用于输出404页面 */
	public function _empty()
	{
		$this->redirect('Index/index');
	}
	
	
	protected function _initialize()
	{
		/* 读取站点配置 */
		$config = api('Config/lists');
		C($config); //添加配置
	
		if (!C('WEB_SITE_CLOSE')) {
			$this->error('站点已经关闭，请稍后访问~');
		}
		$sub_menu =
		array(
				'left' =>
				array(
						array('tab' => 'new', 'title' => '最新', 'href' => U('Atlas/Index/index')),
				),
				'right' =>
				array(
						array('tab'=>'create','title' => '<i class="icon-edit"></i> 投稿', 'href' =>is_login()?U('Atlas/Index/publish'):"javascript:toast.error('登录后才能操作')")
				)
		);
		$this->assign('sub_menu', $sub_menu);
		$this->setTitle('图集');
	}
	
	
	protected function ensureApiSuccess($result)
	{
		if (!$result['success']) {
			$this->error($result['message'], $result['url']);
		}
	}
	
	/**
	 * 获取结构
	 * @param unknown $ids
	 * @return multitype:NULL
	 */
	public function getAtlasByIds($ids)
	{
		$ids = is_array($ids) ? $ids : implode(',', $ids);
		$list = array();
		foreach ($ids as $v) {
			$list[] = $this->getAtlas($v);
		}
		return $list;
	}
	
	/**
	 * 获取数据详情
	 * @param unknown $id
	 * @return Ambigous <mixed, \Think\mixed, object>
	 */
	public function getAtlas($id)
	{
		$id=intval($id);
		$atlas = S('atlas_' . $id);
		if (empty($atlas)) {
			$atlas = D('Atlas')->where(array('status' => 1, 'id' => $id))->find();
			$atlas['user'] = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url', 'icons_html'), $atlas['uid']);
			S('atlas_' . $id, $atlas, 300);
		}
		return $atlas;
	}
}