<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 4/2/14
 * Time: 9:14 AM
 */

namespace Atlas\Api;

use Admin\Model\AddonsModel;
use Common\Api\Api;
use Common\Exception\ApiException;
use Think\Hook;


class AtlasApi extends Api
{
    /**各个常用模型*/
    private $atlasModel;
    private $atlas_likeModel;

    public function __construct()
    {
        // 模型名称请使用完整路径，否则其他应用中无法调用接口。
        $this->atlasModel = D('Atlas/Atlas');
        $this->atlas_likeModel = D('Atlas/Atlas_like');
    }
    
    /**
     * 顶 - 操作
     * @param unknown $id
     * @param number $type
     * @throws ApiException
     * @return multitype:
     */
    function dolike($id,$type=1){
        $this->requireLogin();
        
        $_data['uid'] = is_login();
        $_data['atlas_id'] = $id;
        $atlas_like = $this->atlas_likeModel->where($_data)->find();
        if($atlas_like){
        	throw new ApiException('您已经支持过了');
        }
        $_data['type'] = $type;
        $_data['create_time'] = time();
        if(false===$this->atlas_likeModel->create($_data)){
        	throw new ApiException('出现错误: '.$this->atlas_likeModel->getError());
        }
        $atlas_like_id = $this->atlas_likeModel->add();
        if(!$atlas_like_id){
        	throw new ApiException('顶失败: '.$this->atlas_likeModel->getError());
        }
        //添加统计数
        $Count = 0;
        if($type == 1){
        	$this->atlasModel->where('id='.$id)->setInc('like_count');
        	$Count = $this->atlasModel->where('id='.$id)->getField('like_count');
        }elseif($type == 2){
        	$this->atlasModel->where('id='.$id)->setInc('unlike_count');
        	$Count = $this->atlasModel->where('id='.$id)->getField('unlike_count');
        }
        //更新缓存
        $this->getAtlas($id,true);
        return $this->apiSuccess('获取成功', array('count' => $Count));
    }
    
    /**
     * 获取数据详情
     * @param unknown $id
     * @return Ambigous <mixed, \Think\mixed, object>
     */
    public function getAtlas($id,$refresh=FALSE)
    {
    	$id=intval($id);
    	$atlas = S('atlas_' . $id);
    	if (empty($atlas) || $refresh) {
    		$atlas = D('Atlas')->where(array('status' => 1, 'id' => $id))->find();
            if(!$atlas){
                return false;
            }
    		$atlas['user'] = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url', 'icons_html'), $atlas['uid']);
    		S('atlas_' . $id, $atlas, 300);
    	}
    	return $atlas;
    }

    /**
     * 如果发生了错误，跳转到登录页面
     * @throws \Common\Exception\ApiException
     */
    protected function requireLogin()
    {
        try {
            parent::requireLogin();
        } catch (ApiException $ex) {
            $message = $ex->getMessage();
            $errorCode = $ex->getCode();
            $extra = array('url' => U('Home/User/login'));
            throw new ApiException($message, $errorCode, $extra);
        }
    }

    /**
     * 获取到我关注的人的UID列表，包括了自己
     * @return array
     * @auth 陈一枭
     */
    private function getFollowList()
    {
        //获取我关注的人
        $result = $this->followModel->where(array('who_follow' => get_uid()))->select();
        foreach ($result as &$e) {
            $e = $e['follow_who'];
        }
        unset($e);
        $followList = $result;
        $followList[] = is_login();
        return $followList;
    }
}