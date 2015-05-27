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

class ForumController extends BaseController
{
    //返回论坛板块信息
    public function getForumModules()
    {

        $types = D('Forum/Forum')->getAllForumsSortByTypes();

        foreach ($types as &$v) {
            $v['forums']= $this->getForumList(array('status' => 1, 'type_id' => $v['id']));
            foreach($v['forums'] as &$t){
                $t['title'] = op_t($t['title']);
            }

        }
        $list = array('list' => $types);
        $this->apiSuccess('返回成功', $list);

    }

//返回帖子列表（可按恢复时间和发布时间排序)
    public function getPosts()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aId = I('forum_id', 0, 'intval');
        $map['status'] = 1;
        $aId && $map['forum_id'] = $aId;

        $order = I('order', 'create', 'op_t') == 'reply' ? 'last_reply desc' : 'create_time desc';
        $order = 'is_top desc ,' . $order;
        $post_list = D('Forum/ForumPost')->where($map)->page($aPage, $aCount)->order($order)->select();

        $post_ids = D('support')->where(array('appname' => 'Forum', 'table' => 'post', 'uid' => is_login()))->field('row')->select();
        $post_ids = array_column($post_ids, 'row');
        foreach ($post_list as &$v) {

            $v['support_count'] = D('support')->where(array('appname' => 'Forum', 'table' => 'post', 'row' => $v['id'],))->count();
            if (empty($v['support_count'])) {
                $v['is_supported'] = '0';

            } else {
                $v['is_supported'] = '1';
            }

            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v['content'], $arr); //匹配所有的图片
            $v['imglist'] = $arr[1];
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
            $v['content'] = op_t($v['content']);
            if (empty($arr[1])) {
                $v['type'] = 'text';
            } else {
                $v['type'] = 'image';
            }
            if (in_array($v['id'], $post_ids)) {
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }


        }
        unset($v);
        $list = array('list' => $post_list);

        $this->apiSuccess('返回成功', $list);

    }


// 返回某个帖子的评论列表
    public function getPostComments()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aId = I('post_id', 0, 'intval');
        $map['status'] = 1;
        $aId && $map['post_id'] = $aId;


        $replyList = D('Forum/ForumPostReply')->where($map)->page($aPage, $aCount)->select();
        $uid = D('Forum/ForumPost')->where(array('id' => $aId))->field('uid')->select();
        $uid = array_column($uid, 'uid');

        $arr = array();

        foreach ($replyList as &$v) {
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v['content'], $arr); //匹配所有的图片
            $v['imgList'] = $arr[1];
            $v['content'] = op_t($v['content']);
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
            if (in_array($v['uid'], $uid)) {
                $v['is_landlord'] = '1';
            } else {
                $v['is_landlord'] = '0';
            }
            $v['toReplyList'] = D('Forum/ForumLzlReply')->where(array('to_f_reply_id' => $v['id']))->page($aPage, $aCount)->select();

            if (empty($v['toReplyList'])) {
                $v['toReplyList'] = array();
            }
            foreach ($v['toReplyList'] as &$val) {
                $val['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $val['uid']);
                if (in_array($val['uid'], $uid)) {
                    $val['is_landlord'] = '1';
                } else {
                    $val['is_landlord'] = '0';
                }
            }
            unset($val);

        }
        unset($v);

        $list = array('list' => $replyList);
        $this->apiSuccess('返回成功', $list);


    }


//返回评论的评论列表
    public function getComments()
    {
        $apage = I('page', 1, 'intval');
        $acount = I('count', 10, 'intval');

        $aPostId = I('post_id', 0, 'intval');

        $aId = I('to_f_reply_id', 0, 'intval');
        $map['status'] = 1;
        $aId && $map['to_f_reply_id'] = $aId;

        $uid = D('Forum/ForumPost')->where(array('id' => $aPostId))->field('uid')->select();
        $uid = array_column($uid, 'uid');

        $LzlPost = D('Forum/ForumLzlReply')->where($map)->page($apage, $acount)->select();

        foreach ($LzlPost as &$v) {

            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
            if (in_array($v['uid'], $uid)) {
                $v['is_landlord'] = '1';
            } else {
                $v['is_landlord'] = '0';
            }

        }
        unset($v);

        $list = array('list' => $LzlPost);

        $this->apiSuccess('返回成功', $list);
    }


//给帖子点赞
    public function supportPost()
    {
        if (!is_login()) {
            exit($this->apiError('请登录后再点赞。'));
        }
        $appname = 'forum';
        $table = 'post';
        $aRow = I('post_id');
        $aMessage_uid = intval(I('uid'));
        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['row'] = $aRow;
        $support['uid'] = is_login();

        if (D('Support')->where($support)->count()) {
            exit ($this->apiError('您已经赞过，不能再赞了。'));

        } else {
            $support['create_time'] = time();
            if (D('Support')->where($support)->add($support)) {

                $this->clearCache($support);

                $user = query_user(array('username'));
                if (I('jump') == 'no') {
                    $jump = $_SERVER['HTTP_REFERER']; //如果设置了jump=no，则默认使用引用页
                } else {
                    $jump = U($appname . '/Index/' . $table . 'Detail', array('id' => $aRow));//否则按照约定规则组合消息跳转页面。
                }
                D('Message')->sendMessage($aMessage_uid, $user['username'] . '给您点了个赞。', $title = $user['username'] . '赞了您。', $jump, is_login());
                exit($this->apiSuccess('感谢你的支持。'));
            } else {
                exit($this->apiError('写入数据库失败。'));
            }
        }
    }

//给帖子回复
    public function sendPostComment()
    {
        $this->requireLogin();

        $aPostId = I('post_id', 0, 'intval');
        $map['status'] = 1;
        $aPostId && $map['post_id'] = $aPostId;
        if (!$aPostId) {
            $this->apiError('帖子不存在');
        }
        $aContent = I('content', '', 'op_t');
        $uid = D('Forum/ForumPost')->where(array('id' => $aPostId))->field('uid')->select();
        $uid = array_column($uid, 'uid');

        $result = D('Forum/ForumPostReply')->addReply($aPostId, $aContent);

        $reply = D('Forum/ForumPostReply')->where(array('id' => $result))->find();
        preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $reply['content'], $arr); //匹配所有的图片
        $reply['imgList'] = $arr[1];
        $reply['content'] = op_t($reply['content']);
        $reply ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));

        $reply['toReplyList'] = array();

        if (in_array($reply['uid'], $uid)) {
            $val['is_landlord'] = '1';
        } else {
            $val['is_landlord'] = '0';
        }


        $this->apiSuccess('返回成功', $reply);
    }

//给评论回复
    public function sendComment()
    {
        $this->requireLogin();

        $aToReplyId = I('to_reply_id', 0, 'intval');
        $aContent = I('content', '', 'op_t');

        if ($aToReplyId) {
            $LzlReply = D('Forum/ForumLzlReply')->where(array('id' => $aToReplyId))->find();
            $data ['post_id'] = $LzlReply['post_id'];
            $data ['to_f_reply_id'] = $LzlReply['to_f_reply_id'];
            $data ['content'] = $aContent;
            $data ['uid'] = is_login();
            $data ['to_uid'] = $LzlReply['uid'];
            $data ['ctime'] = time();
            $data ['to_reply_id'] = $aToReplyId;
            $result = D('Forum/ForumLzlReply')->add($data);

            if (!$result) {

                $this->apiError('发布失败');
            }
            //增加帖子的回复数
            D('Forum/ForumPost')->where(array('id' => $LzlReply['post_id']))->setInc('reply_count');
            //更新最后回复时间
            D('Forum/ForumPost')->where(array('id' => $LzlReply['post_id']))->setField('last_reply_time', time());

            $Reply = D('Forum/ForumLzlReply')->where(array('id' => $result,))->find();

            $Reply ['ctime'] = friendlyDate($Reply ['ctime']);
            $Reply ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));

            $this->apiSuccess('返回成功', $Reply);
        } else {

            $aToFReplyId = I('to_f_reply_id', 0, 'intval');

            $LzlReply = D('Forum/ForumPostReply')->where(array('id' => $aToFReplyId))->find();

            $data ['post_id'] = $LzlReply['post_id'];
            $data ['to_f_reply_id'] = $aToFReplyId;
            $data ['content'] = $aContent;
            $data ['uid'] = is_login();
            $data ['to_uid'] = $LzlReply['uid'];
            $data ['ctime'] = time();
            $data ['to_reply_id'] = 0;
            $result = D('Forum/ForumLzlReply')->add($data);

            if (!$result) {

                $this->apiError('发布失败');
            }
            //增加帖子的回复数
            D('Forum/ForumPost')->where(array('id' => $LzlReply['post_id']))->setInc('reply_count');
            //更新最后回复时间
            D('Forum/ForumPost')->where(array('id' => $LzlReply['post_id']))->setField('last_reply_time', time());

            $Reply = D('Forum/ForumLzlReply')->where(array('id' => $result,))->find();

            $Reply ['ctime'] = friendlyDate($Reply ['ctime']);
            $Reply ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));

            $this->apiSuccess('返回成功', $Reply);
        }


    }


//收藏帖子
    public function collectionPost()
    {
        $this->requireLogin();
        $aPostId = I('post_id', 0, 'intval');

        //查询数据库内是否已收藏
        $result = D('Forum/ForumBookmark')->where(array('post_id' => $aPostId,'uid'=>is_login()))->find();

        if ($result) {
            $this->apiError('帖子已收藏');
        }else{
            //写入数据库
            $data['post_id'] =$aPostId ;
            $data['uid'] = is_login();
            $data['create_time'] = time();
            $result = D('Forum/ForumBookmark')->add($data);
        }
        //返回成功消息
        $collection = D('Forum/ForumBookmark')->where(array('post_id' => $result))->find();

        $this->apiSuccess('收藏成功', $collection);
    }


//发布帖子
    public function sendPost()
    {
        $this->requireLogin();
        $aForumId = I('forum_id', 0, 'intval');
        $aPostId = I('post_id', 0, 'intval');
        $aTitle = I('title', '', 'op_t');
        $aContent = I('content', '', 'op_h');
        $attach_id = I('attach_id', '', 'op_t');
        $attach_ids = explode(',', $attach_id);

        foreach ($attach_ids as $k => $v) {
            $aContent .= "<p><img src='" . get_cover($v, 'path') . "'/></p>";
        }
        unset($v);

        $aContent = str_replace("\\", '', $aContent);


        $isEdit = $aPostId ? true : false;
        $this->requireForumAllowPublish($aForumId);
        $model = D('Forum/ForumPost');
        if ($isEdit) {
            $data = array('post_id' => intval($aPostId), 'title' => $aTitle, 'content' => $aContent, 'parse' => 0, 'forum_id' => intval($aForumId));

            $result = D('Forum/ForumPost')->editPost($data);


            if (!$result) {
                $this->apiError('编辑失败：' . $model->getError());
            }
        } else {
            $data = array('uid' => is_login(), 'title' => $aTitle, 'content' => $aContent, 'parse' => 0, 'forum_id' => intval($aForumId));

            $result = D('Forum/ForumPost')->createPost($data);

            if (!$result) {
                $this->apiError('发表失败：' . $model->getError());
            }
            $aPostId = $result;
        }


        //发布帖子成功，发送一条微博消息

        if(D('Common/Module')->isInstalled('Weibo')){//安装了微博模块
            $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Forum/Index/detail', array('id' => $aPostId));
            $weiboModel=D('Weibo/Weibo');
            $weiboModel->addWeibo("我修改了活动【" . $aTitle . "】：" . $postUrl);
        }



        //实现发布帖子发布图片微博(公共内容)
        $type = 'feed';
        $feed_data = array();
        //解析并成立图片数据
        $arr = array();
        preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $data['content'], $arr); //匹配所有的图片

        if (!empty($arr[0])) {

            $feed_data['attach_ids'] = '';
            $dm = "http://$_SERVER[HTTP_HOST]" . __ROOT__; //前缀图片多余截取
            $max = count($arr['1']) > 9 ? 9 : count($arr['1']);
            for ($i = 0; $i < $max; $i++) {
                $tmparray = strpos($arr['1'][$i], $dm);
                if (!is_bool($tmparray)) {
                    $path = mb_substr($arr['1'][$i], strlen($dm), strlen($arr['1'][$i]) - strlen($dm));
                    $result_id = D('Home/Picture')->where(array('path' => $path))->getField('id');

                } else {
                    $path = $arr['1'][$i];
                    $result_id = D('Home/Picture')->where(array('path' => $path))->getField('id');
                    if (!$result_id) {
                        $result_id = D('Home/Picture')->add(array('path' => $path, 'url' => $path, 'status' => 1, 'create_time' => time()));
                    }
                }
                $feed_data['attach_ids'] = $feed_data['attach_ids'] . ',' . $result_id;
            }
            $feed_data['attach_ids'] = substr($feed_data['attach_ids'], 1);

        }

        $feed_data['attach_ids'] != false && $type = "image";

        //开始发布微博
        if ($isEdit) {
            D('Weibo/Weibo')->addWeibo("我更新了帖子【" . $aTitle . "】：" . $postUrl, $type, $feed_data);
        } else {
            D('Weibo/Weibo')->addWeibo("我发表了一个新的帖子【" . $aTitle . "】：" . $postUrl, $type, $feed_data);
        }


        //显示成功消息
        $message = $isEdit ? '编辑成功。' : '发表成功。' .  cookie('score_tip');


        //返回成功消息
        $post = D('Forum/ForumPost')->where('id=' . $aPostId)->find();
        $post ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));
        $post['content'] = text_part($post['content']);

        $this->apiSuccess($message, $post);


    }



    /*——————————————————————私有函数————————————————————————————*/


    //验证是否允许登陆 板块拥有权限 用户组是否拥有权限
    private function requireForumAllowPublish($forum_id)
    {

        $this->requireForumExists($forum_id);
        $this->requireLogin();
        $this->requireForumAllowCurrentUserGroup($forum_id);
    }

    private function requireForumExists($forum_id)
    {
        if (!$this->isForumExists($forum_id)) {
            $this->apiError('论坛不存在');
        }
    }

    private function isForumExists($forum_id)
    {
        $forum_id = intval($forum_id);
        $forum = D('Forum')->where(array('id' => $forum_id, 'status' => 1));
        return $forum ? true : false;
    }

    private function requireForumAllowCurrentUserGroup($forum_id)
    {
        $forum_id = intval($forum_id);
        if (!$this->isForumAllowCurrentUserGroup($forum_id)) {
            $this->apiError('该板块不允许发帖');
        }
    }

    private function isForumAllowCurrentUserGroup($forum_id)
    {
        $forum_id = intval($forum_id);
        //如果是超级管理员，直接允许
        if (is_login() == 1) {
            return true;
        }

        //如果帖子不属于任何板块，则允许发帖
        if (intval($forum_id) == 0) {
            return true;
        }

        //读取论坛的基本信息
        $forum = D('Forum/Forum')->where(array('id' => $forum_id))->find();
        $userGroups = explode(',', $forum['allow_user_group']);

        //读取用户所在的用户组
        $list = M('AuthGroupAccess')->where(array('uid' => is_login()))->select();
        foreach ($list as &$e) {
            $e = $e['group_id'];
        }



        //判断用户组是否有权限
        $list = array_intersect($list, $userGroups);
        return $list ? true : false;
    }

    private function clearCache($support)
    {
        unset($support['uid']);
        unset($support['create_time']);
        $cache_key = "support_count_" . implode('_', $support);
        S($cache_key, null);
    }
    private function getForumList($map_type = array('status' => 1))
    {
        $tag = 'forum_list_' . serialize($map_type);
        $forum_list = S($tag);
        $cache_time = modC('CACHE_TIME', 300, 'Forum');
        if (empty($forum_list)) {
            //读取板块列表

            $forum_list = D('Forum/Forum')->where($map_type)->order('sort asc')->select();
            $forumPostModel = D('ForumPost');
            $forumPostReplyModel = D('ForumPostReply');
            $forumLzlReplyModel = D('ForumLzlReply');
            foreach ($forum_list as &$f) {
                $map['status'] = 1;
                $map['forum_id'] = $f['id'];
                $f['background'] = $f['background'] ? getThumbImageById($f['background'], 800, 'auto') : C('TMPL_PARSE_STRING.__IMG__') . '/default_bg.jpg';
                $f['logo'] = $f['logo'] ? getThumbImageById($f['logo'], 128, 128) : C('TMPL_PARSE_STRING.__IMG__') . '/default_logo.png';
                $f['topic_count'] = $forumPostModel->where($map)->count();
                $post_id = $forumPostModel->where(array('forum_id' => $f['id']))->field('id')->select();
                $p_id = getSubByKey($post_id, 'id');
                $map['post_id'] = array('in', implode(',', $p_id));
                $f['total_count'] = $f['topic_count'] + $forumPostReplyModel->where($map)->count();// + $forumLzlReplyModel->where($map)->count();
            }
            unset($f);
            S($tag, $forum_list, $cache_time);
        }
        return $forum_list;
    }
}

