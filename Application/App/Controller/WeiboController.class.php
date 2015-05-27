<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 4/4/14
 * Time: 9:29 AM
 */

namespace App\Controller;

use App\Model;
use Think\Controller;
use Weibo\Api\WeiboApi;

class WeiboController extends BaseController
{
    private $weiboApi;
    protected $api;

    public function _initialize()
    {
        $this->weiboApi = new WeiboApi();
        $this->api = new Model\ApiModel();
        parent::_initialize();
    }

    /**获取微博字数限制
     * @auth 陈一枭
     */
    public function getWeiboWordsLimit(){
        C(api('Config/lists'));
        $this->apiSuccess('微博字数限制获取成功。',array('limit'=>C('WEIBO_WORDS_COUNT')));
    }

    public function listAllWeibo($page = 1, $count = 10, $uid = 0)
    {
        $map = array();
        if ($uid != 0) {
            $map['uid'] = $uid;
        }
        $result = $this->weiboApi->listAllWeibo($page, $count, $map);
        $result['list'] = $this->handleWeiboList($result['list']);
        //echo($result);
        $this->ajaxReturn($result);
    }


    /**列出我关注的全部微博，需要登录
     * @param int $page
     * @param int $count
     * @auth 陈一枭
     */
    public function listMyFollowingWeibo($page = 1, $count = 10)
    {
        $this->api->requireLogin();
        $result = $this->weiboApi->listMyFollowingWeibo($page, $count);
        $result['list'] = $this->handleWeiboList($result['list']);
        $this->ajaxReturn($result);
    }

    public function getWeiboDetail($weibo_id)
    {
        $result = $this->weiboApi->getWeiboDetail($weibo_id);
        $result['weibo']=$this->preHandleWeibo($result['weibo']);
        $this->ajaxReturn($result);
    }

    /**发微博，需要登录
     * @param        $content
     * @param string $type
     * @param string $attach_ids
     * @param string $from
     * @auth 陈一枭
     */
    public function sendWeibo($content, $type = 'feed', $attach_ids = '', $from = '手机客户端')
    {
        $this->api->requireLogin();
        $feed_data['attach_ids'] = $attach_ids;
        $result = $this->weiboApi->sendWeibo($content, $type, $feed_data, $from);
        $result['detail']=$this->weiboApi->getWeiboStructure($result['weibo_id']);
        $result['detail']=$this->preHandleWeibo($result['detail']);
        $this->ajaxReturn($result);
    }
    /**删除微博
     * @param $weibo_id
     * @auth 陈一枭
     */
    public function deleteWeibo($weibo_id)
    {
        $this->api->requireLogin();
        $result = $this->weiboApi->deleteWeibo($weibo_id);
        $this->ajaxReturn($result);
    }

    /**转发微博，需要登录
     * @param        $content
     * @param        $type
     * @param        $sourseId
     * @param        $weiboId
     * @param        $becomment
     * @param string $from
     * @auth 陈一枭
     */
    public function sendRepost($content = '', $type = 'repost', $sourceId = 0, $weiboId = 0, $becomment = 0, $from = '手机客户端')
    {
        $this->api->requireLogin();
        $feed_data = '';
        $sourse = $this->weiboApi->getWeiboDetail($sourceId);
        $sourseweibo = $sourse['weibo'];
        $feed_data['sourse'] = $sourseweibo;
        $feed_data['sourseId'] = $sourceId;
        //发送微博
        $result = $this->weiboApi->sendWeibo($content, $type, $feed_data, $from);
        if ($result) {
            D('weibo')->where('id=' . $sourceId)->setInc('repost_count');
            $weiboId != $sourceId && D('weibo')->where('id=' . $weiboId)->setInc('repost_count');
        }

        $user = query_user(array('nickname'), is_login());
        $toUid = D('weibo')->where(array('id' => $weiboId))->getField('uid');

        D('Common/Message')->sendMessage($toUid, $user['nickname'] . '转发了您的微博！', '转发提醒', U('Weibo/Index/weiboDetail', array('id' => $result['weibo_id'])), is_login(), 1);


        if ($becomment == 'true') {
            $this->weiboApi->sendRepostComment($weiboId, $content);
        }
        $result['detail']['weibo']=$this->preHandleWeibo($result['detail']['weibo']);
        //返回成功结果
        $this->ajaxReturn(apiToAjax($result));
    }

    /**微博点赞，需要登录
     * @param $weibo_id
     * @auth 陈一枭
     */
    public function supportWeibo($weibo_id)
    {




        $this->api->requireLogin();
        $supportModel = D('Support');
        $weibo = array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $weibo_id);
        $weibo['uid'] = get_uid();
        if ($supportModel->where($weibo)->count()) {
            $this->apiError( '点赞失败：' . '重复点赞。',501);
        } else {
            $weibo['create_time'] = time();
            $result = $supportModel->add($weibo);
            if ($result) {
                $this->apiSuccess('点赞成功。', array());
            } else {
                $this->apiError( '点赞失败：' . '数据存储错误。',502);
            }
        }
    }

    /**发送评论，需要登录
     * @param     $weibo_id
     * @param     $content
     * @param int $comment_id
     * @auth 陈一枭
     */
    public function sendComment($weibo_id, $content, $comment_id = 0)
    {
        $this->api->requireLogin();
        $result = $this->weiboApi->sendComment($weibo_id, $content, $comment_id);
        $this->ajaxReturn($result);
    }

    /**列出全部评论
     * @param     $weibo_id
     * @param int $page
     * @param int $count
     * @auth 陈一枭
     */
    public function listComment($weibo_id, $page = 1, $count = 10)
    {
        $result = $this->weiboApi->listComment($weibo_id, $page, $count);
        $this->ajaxReturn($result);
    }



    /**删除评论
     * @param $comment_id
     * @auth 陈一枭
     */
    public function deleteComment($comment_id)
    {
        $this->api->requireLogin();
        $result = $this->weiboApi->deleteComment($comment_id);
        $this->ajaxReturn($result);
    }


    /*——————————————————————私有函数————————————————————————————*/


    private function math_images($weibo)
    {
        if ($weibo['type'] == 'image') { //如果是图片微博则解析图片
            $attach_ids = explode(',', $weibo['data']['attach_ids']);
            foreach ($attach_ids as $data_id) {
                $weibo['images'][] = getThumbImageById($data_id, 100, 100);
            }
        } else if (($weibo['type'] == 'repost')) { //处理转发中的图片微博
            if ($weibo['data']['sourse']['type'] == 'image') {
                $source = $weibo['data']['sourse'];
                $attach_ids = explode(',', $source['data']['attach_ids']);
                foreach ($attach_ids as $data_id) {
                    $source['images'][] = getThumbImageById($data_id, 100, 100);
                }
                $weibo['data']['sourse'] = $source;
            }
        }
        return $weibo;
    }

    /**预处理微博列表
     * @param $list
     * @return mixed
     * @auth 陈一枭
     */
    private function handleWeiboList($list)
    {
        foreach ($list as &$weibo) {
            $weibo = $this->preHandleWeibo($weibo);

        }
        return $list;
    }


    /**
     * @param $weibo
     * @return mixed
     * @auth 陈一枭
     */
    private function math_support($weibo)
    {
        $support_model = D('Support');
        $weibo_map = array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $weibo['id']);
        $weibo['support_count'] = $support_model->where($weibo_map)->count();
        if (is_login()) {
            $weibo_supported_map = $weibo_map;
            $weibo_supported_map['uid'] = is_login();
            $weibo['is_supported'] = $support_model->where($weibo_supported_map)->count();
            return $weibo;
        }
        return $weibo;
    }


    /**
     * @param $weibo
     * @auth 陈一枭
     */
    private function clean_useless_data($weibo)
    {
        unset($weibo['fetchContent']);
        unset($weibo['user']['space_url']);
        unset($weibo['user']['avatar32']);
        unset($weibo['user']['avatar64']);
        unset($weibo['user']['avatar256']);
        unset($weibo['user']['avatar512']);
        unset($weibo['user']['icons_html']);
        return $weibo;
    }

    /**
     * @param $weibo
     * @return mixed
     * @auth 陈一枭
     */
    private function preHandleWeibo($weibo)
    {
        $weibo = $this->math_images($weibo);
        $weibo = $this->math_support($weibo);
        $weibo = $this->clean_useless_data($weibo);
        return $weibo;
    }
}