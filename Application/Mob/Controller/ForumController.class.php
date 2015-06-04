<?php


namespace Mob\Controller;

use Think\Controller;


class ForumController extends Controller
{
    public function index()
    {
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $order = I('order', 'create', 'op_t') == 'reply' ? 'last_reply desc' : 'create_time desc';
        $order = 'is_top desc ,' . $order;

        $forum = D('ForumPost')->where(array('status' => 1,))->page($aPage, $aCount)->order($order)->select();
        foreach($forum as &$v){
            $v['user']=query_user(array('nickname', 'avatar32'), $v['uid']);

            $v['plate'] = D('Forum')->where(array('status' => 1,'id'=>$v['forum_id']))->find();

        }
//dump($forum);exit;
        $this->assign('forum',$forum);
        $this->display();
    }


    public function addMoreForum(){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $order = I('order', 'create', 'op_t') == 'reply' ? 'last_reply desc' : 'create_time desc';
        $order = 'is_top desc ,' . $order;

        $forum = D('ForumPost')->where(array('status' => 1,))->page($aPage, $aCount)->order($order)->select();
        foreach($forum as &$v){
            $v['user']=query_user(array('nickname', 'avatar32'), $v['uid']);

            $v['plate'] = D('Forum')->where(array('status' => 1,'id'=>$v['forum_id']))->find();

        }
        if ($forum) {
            $data['html'] = "";
            foreach ($forum as $val) {
                $this->assign("vo", $val);
                $data['html'] .= $this->fetch("_forumlist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);

    }
    /**
     * 版块内容渲染
     */
    public function forumtype(){
        $forum_top = D('ForumType')->where(array('status' => 1))->select();        //查找顶级分类pid=0的

        //  dump($issue_top);exit;
        foreach ($forum_top as &$v) {
            $v['lever_two'] = D('Forum')->where(array('status' => 1, 'type_id' => $v['id']))->select();        //查找二级分类pid=$issue_top的id
            $v['count'] = count($v['lever_two']);                //二级分类数量
            foreach ($v['lever_two'] as &$k) {
                $k['count_content'] = D('ForumPost')->where(array('status' => 1, 'forum_id' => $k['id']))->count();
            }
        }

       //  dump($forum_top);exit;
        $this->assign("forum_top", $forum_top);         //顶级分类
        $this->display();
    }


    /**
     * @param $id
     * 版块点击进入该分类
     */
    public function postSectionDetail($id){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $forum= D('ForumPost')->where(array('status' => 1, 'forum_id' => $id))->page($aPage, $aCount)->select();
     //   dump($forum);exit;

        foreach ($forum as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['plate'] = D('Forum')->where(array('status' => 1,'id'=>$v['forum_id']))->find();
        }
        $title= D('Forum')->where(array('status' => 1, 'id' => $id))->find();
        $title['pid']=1;                    //设置一个标记，执行过这个function的增加这个标记，页面进行判断。
     //     dump($title);exit;
        $this->assign('forum',$forum);
        $this->assign("title", $title);
        $this->display(T('Application://Mob@Forum/index'));
    }


    /**
     * @param $id
     * 版块点击进入该分类--查看更多
     */
    public function addMorePostSectionDetail(){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $aId = I('post.id', '', 'op_t');
        $forum= D('ForumPost')->where(array('status' => 1, 'forum_id' => $aId))->page($aPage, $aCount)->select();

        foreach ($forum as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['plate'] = D('Forum')->where(array('status' => 1,'id'=>$v['forum_id']))->find();
        }
        if ($forum) {
            $data['html'] = "";
            foreach ($forum as $val) {
                $this->assign("vo", $val);
                $data['html'] .= $this->fetch("_forumlist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }
    /**
     * @param $id
     * 帖子详情
     */
    public function postDetail($id){
        $map['id'] = array('eq', $id);

        $forum_detail = D('ForumPost')->where($map)->find();
        $is_add=D('ForumBookmark')->where(array('post_id' => $id,'uid'=>is_login()))->find();
        if(is_null($is_add)){
            $is_add=0;
        }else{
            $is_add=1;
        }

        $forum_detail['user']=query_user(array('nickname', 'avatar128'), $forum_detail['uid']);


        $post_detail= D('ForumPostReply')->where(array('post_id' => $id))->select();
        foreach($post_detail as &$v){
            $v['user']=query_user(array('nickname', 'avatar32'), $v['uid']);
        }
     //   dump($forum_detail);exit;
        $this->assign("forum", $forum_detail);
        $this->assign("postcomment", $post_detail);
        $this->assign("isadd", $is_add);
        $this->display();
    }

    /**
     * 发的帖子内容渲染
     * （标题内容渲染）
     */
    public function addPost(){
        $forum_list = D('Forum/Forum')->getForumList();
        //判断板块能否发帖
        foreach ($forum_list as &$e) {
            $e['allow_publish'] = $this->isForumAllowPublish($e['id']);
        }
        unset($e);
        $myInfo = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url', 'icons_html'), is_login());
        $this->assign('myInfo', $myInfo);
        //赋予论坛列表
        $this->assign('forum_list', $forum_list);
        $types = D('Forum/Forum')->getAllForumsSortByTypes();
        $this->assign('types', $types);


        $this->display();
    }

    private function isForumAllowPublish($forum_id)
    {
        if (!$this->isLogin()) {
            return false;
        }
        if (!$this->isForumExists($forum_id)) {
            return false;
        }
        if (!$this->isForumAllowCurrentUserGroup($forum_id)) {
            return false;
        }
        return true;
    }
    private function isLogin()
    {
        return is_login() ? true : false;
    }
    private function isForumExists($forum_id)
    {
        $forum_id = intval($forum_id);
        $forum = D('Forum')->where(array('id' => $forum_id, 'status' => 1));
        return $forum ? true : false;
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
        $forum = D('Forum')->where(array('id' => $forum_id))->find();
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


    /**
     * 发帖子功能实现
     */
    public function doAddPost($post_id = null, $forum_id = 0, $title, $content){
        $post_id = intval($post_id);
        $forum_id = intval($forum_id);
        $title = text($title);
        $aSendWeibo = I('sendWeibo', 0, 'intval');

        $content = $content;//op_h($content);


        //判断是不是编辑模式
        $isEdit = $post_id ? true : false;
        $forum_id = intval($forum_id);

        //如果是编辑模式，确认当前用户能编辑帖子
        if ($isEdit) {
            $this->requireAllowEditPost($post_id);
        }

        //确认当前论坛能发帖
        $this->requireForumAllowPublish($forum_id);


        if ($title == '') {
            $this->error('请输入标题。');
        }
        if ($forum_id == 0) {
            $this->error('请选择发布的版块。');
        }
        if (strlen($content) < 10) {
            $this->error('发表失败：内容长度不能小于10');
        }


        //   $content = filterBase64($content);
        //检测图片src是否为图片并进行过滤
        //  $content = filterImage($content);

        //写入帖子的内容
        $model = D('Forum/ForumPost');
        if ($isEdit) {
            $data = array('id' => intval($post_id), 'title' => $title, 'content' => $content, 'parse' => 0, 'forum_id' => intval($forum_id));
            $result = $model->editPost($data);
            if (!$result) {
                $this->error('编辑失败：' . $model->getError());
            }
        } else {
            $data = array('uid' => is_login(), 'title' => $title, 'content' => $content, 'parse' => 0, 'forum_id' => $forum_id);

            $before = getMyScore();
            $result = $model->createPost($data);
            $after = getMyScore();
            if (!$result) {
                $this->error('发表失败：' . $model->getError());
            }
            $post_id = $result;
        }

        /*   //发布帖子成功，发送一条微博消息
           $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Forum/Index/detail', array('id' => $post_id));
           $weiboApi = new WeiboApi();
           $weiboApi->resetLastSendTime();*/


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

        if ($aSendWeibo) {
            //开始发布微博
            if ($isEdit) {
                D('Weibo')->addWeibo(is_login(), "我更新了帖子【" . $title . "】：" . U('detail', array('id' => $post_id), null, true), $type, $feed_data);
            } else {
                D('Weibo')->addWeibo(is_login(), "我发表了一个新的帖子【" . $title . "】：" . U('detail', array('id' => $post_id), null, true), $type, $feed_data);
            }
        }


        //显示成功消息
        if ($aSendWeibo) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['info'] = '发贴失败';
        }
        $this->ajaxReturn($return);

    }

    private function requireAllowEditPost($post_id)
    {
        $this->requirePostExists($post_id);
        $this->requireLogin();

        if (is_administrator()) {
            return true;
        }
        //确认帖子时自己的
        $post = D('ForumPost')->where(array('id' => $post_id, 'status' => 1))->find();
        if ($post['uid'] != is_login()) {
            $this->error('没有权限编辑帖子');
        }
    }
    private function requireForumAllowPublish($forum_id)
    {
        $this->requireForumExists($forum_id);
        $this->requireLogin();
        $this->requireForumAllowCurrentUserGroup($forum_id);
    }
    private function requireForumExists($forum_id)
    {
        if (!$this->isForumExists($forum_id)) {
            $this->error('论坛不存在');
        }
    }
    private function requireLogin()
    {
        if (!$this->isLogin()) {
            $this->error('需要登录才能操作');
        }
    }
    private function requireForumAllowCurrentUserGroup($forum_id)
    {
        $forum_id = intval($forum_id);
        if (!$this->isForumAllowCurrentUserGroup($forum_id)) {
            $this->error('该板块不允许发帖');
        }
    }


    /**
     * 帖子回复
     */
    public function AddForumComment(){
        $aPostId = I('post.forumId', 0, 'intval');
        $aContent = I('post.forumcontent', 0, 'op_t');
        $post_id= $aPostId;
        $content=$aContent;
        $content = $this->filterPostContent($content);

        //确认有权限回复
        $this->requireAllowReply($post_id);


        //检测回复时间限制
        $uid = is_login();
        $near = D('ForumPostReply')->where(array('uid' => $uid))->order('create_time desc')->find();

        $cha = time() - $near['create_time'];
        if ($cha > 10) {

            //添加到数据库
            $model = D('Forum/ForumPostReply');
            $before = getMyScore();
         //   $tox_money_before = getMyToxMoney();
            $result = $model->addReply($post_id, $content);
            $after = getMyScore();
        //    $tox_money_after = getMyToxMoney();
            if (!$result) {
                $this->error('回复失败：' . $model->getError());
            }
            //显示成功消息
            $this->success('回复成功。' , 'refresh');
        } else {
            $this->error('请10秒之后再回复');

        }
    }
    /**过滤输出，临时解决方案
     * @param $content
     * @return mixed|string
     * @auth 陈一枭
     */
    private function filterPostContent($content)
    {
        $content = op_h($content);
        $content = $this->limitPictureCount($content);
        $content = op_h($content);
        return $content;
    }
    private function requireAllowReply($post_id)
    {
        $post_id = intval($post_id);
        $this->requirePostExists($post_id);
        $this->requireLogin();
    }
    private function limitPictureCount($content)
    {
        //默认最多显示10张图片
        $maxImageCount = modC('LIMIT_IMAGE', 10);
        //正则表达式配置
        $beginMark = 'BEGIN0000hfuidafoidsjfiadosj';
        $endMark = 'END0000fjidoajfdsiofjdiofjasid';
        $imageRegex = '/<img(.*?)\\>/i';
        $reverseRegex = "/{$beginMark}(.*?){$endMark}/i";

        //如果图片数量不够多，那就不用额外处理了。
        $imageCount = preg_match_all($imageRegex, $content);
        if ($imageCount <= $maxImageCount) {
            return $content;
        }

        //清除伪造图片
        $content = preg_replace($reverseRegex, "<img$1>", $content);

        //临时替换图片来保留前$maxImageCount张图片
        $content = preg_replace($imageRegex, "{$beginMark}$1{$endMark}", $content, $maxImageCount);

        //替换多余的图片
        $content = preg_replace($imageRegex, "[图片]", $content);

        //将替换的东西替换回来
        $content = preg_replace($reverseRegex, "<img$1>", $content);

        //返回结果
        return $content;
    }
    private function requirePostExists($post_id)
    {
        $post_id = intval($post_id);
        $post = D('ForumPost')->where(array('id' => $post_id))->find();
        if (!$post) {
            $this->error('帖子不存在');
        }
    }

    /**
     * 收藏帖子实现
     */
    public function collection(){
        $aPostId = I('post.post_id', 0, 'intval');
        $aAdd=I('post.add', '', 'op_t');

        $add=$aAdd;

        $post_id=$aPostId;
        $add = intval($add);


        //确认用户已经登录
        $this->requireLogin();

        //写入数据库
        if ($add) {
            $result = D('Forum/ForumBookmark')->addBookmark(is_login(), $post_id);
            if (!$result) {
                $this->error('收藏失败');
            }
        } else {
            $result = D('Forum/ForumBookmark')->removeBookmark(is_login(), $post_id);
            if (!$result) {
                $this->error('取消失败');
            }
        }

        //返回成功消息
        if ($add) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['info'] = '已取消收藏！';
        }

        $this->ajaxReturn($return);
    }
}