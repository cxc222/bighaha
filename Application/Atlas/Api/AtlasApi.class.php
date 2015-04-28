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
     * 
     */
    function dolike($id,$type=1){
        $this->requireLogin();
        
        $_data['uid'] = '';
        $_data['atlas_id'] = $id;
        $atlas_like = $this->atlas_likeModel->where($_data)->find();
        
        print_r($atlas_like);
        die();
        //写入数据库
        $weibo_id = $this->weiboModel->addWeibo(get_uid(), $content, $type, $feed_data, $from);
        if (!$weibo_id) {
            throw new ApiException('发布失败：' . $this->weiboModel->getError());
        }
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

    /**获取到我关注的人的UID列表，包括了自己
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