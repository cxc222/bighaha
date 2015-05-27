<?php
/**
 * Created by PhpStorm.
 * User: 汪汪汪
 * Date: 4/4/14
 * Time: 9:29 AM
 */

namespace App\Controller;

use Think\Controller;
use Weibo\Api\WeiboApi;

class GroupController extends BaseController
{
    //返回群组分类信息
    public function getGroupType()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aId = I('id', '', 'intval');
        if ($aId) {
            $Group = D('Group/GroupType')->where(array('status' => 1, 'pid' => $aId))->page($aPage, $aCount)->order('create_time asc')->select();

            $list = array('list' => $Group);

            $this->apiSuccess('返回成功', $list);
        } else {
            $GroupModules = D('Group/GroupType')->where(array('status' => 1, 'pid' => 0))->page($aPage, $aCount)->order('create_time asc')->select();
            foreach ($GroupModules as &$g) {
                $g['GroupSecond'] = D('Group/GroupType')->where(array('status' => 1, 'pid' => $g['id']))->select();

                $g['create_time'] = friendlyDate($g['create_time']);
            }
            unset($g);
            $list = array('list' => $GroupModules);

            $this->apiSuccess('返回成功', $list);
        }
    }


    public function getGroupAll()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $width = I('width', 160, 'intval');
        $height = I('height', 200, 'intval');
        $aTypeId = I('type_id', 0, 'intval');
        $map['status'] = 1;
        $aTypeId && $map['type_id'] = $aTypeId;


        if (empty($aTypeId)) {


            $Groups = D('Group/Group')->where(array('status' => 1))->page($aPage, $aCount)->order('create_time desc')->select();


        } else {
            $first = D('Group/GroupType')->where(array('id' => $aTypeId, 'status' => 1))->page($aPage, $aCount)->order('create_time desc')->find();
            if ($first['pid'] == 0) {
                $second = D('Group/GroupType')->where(array('pid' => $first['id'], 'status' => 1))->page($aPage, $aCount)->order('create_time desc')->field('id')->select();

                $ids = array();
                foreach ($second as &$s) {
                    $ids = array_merge($ids, array_column($s, 'id'));

                }

                $map = array_merge($ids, array($first['id']));

                $map['type_id'] = array('in', $map);

                $Groups = D('Group/Group')->where(array($map, 'status' => 1))->page($aPage, $aCount)->order('create_time desc')->select();


            } else {
                $Groups = D('Group/Group')->where(array('type_id' => $aTypeId,'status' => 1,))->page($aPage, $aCount)->order('create_time desc')->select();
            }
        }
        foreach ($Groups as &$c) {
            if(!$width){
                $c['background'] = get_cover($c['cover_id']);;
            }else{
                $c['background'] = getThumbImageByCoverId($c['cover_id'], $width, $height);
            }
            if(!$width){
                $c['logo'] = get_cover($c['cover_id']);;
            }else{
                $c['logo'] = getThumbImageByCoverId($c['cover_id'], $width, $height);
            }

            $c['create_time'] = friendlyDate($c['create_time']);
            $c['title'] = op_t($c['title']);
            $c['detail'] = op_t($c['detail']);
            $c['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $c['uid']);
            $c['menmberCount'] = D('Group/GroupMember')->where(array('group_id' => $c['id']))->order('create_time asc')->count();
        }
        unset($c);
        $list = array('list' => $Groups);

        $this->apiSuccess('返回成功', $list);
    }
    public function getGroupMenmber(){
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aId=I('id', 0, 'intval');
        $Group=D('Group/Group')->where(array('id' => $aId,'status'=>1))->find();
        if(!$Group){
            $this->apiError('没有此群组');
        }
        $GroupMenmber= D('Group/GroupMember')->where(array('group_id' => $aId))->page($aPage, $aCount)->order('create_time asc')->select();

        $GroupCreator=D('Group/Group')->where(array('id' => $aId))->field('uid')->find();

        foreach ($GroupMenmber as &$user) {
            $user['user'] = query_user(array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title'), $user['uid']);

            if (in_array($user['uid'], $GroupCreator)) {
                $user['isCreator'] ='1';
            } else {
                $user['isCreator'] = '0';
            }
        }

        unset($user);

        $list = array('list' => $GroupMenmber);

        $this->apiSuccess('返回成功', $list);
    }

    //返回我的群组信息
    public function getWeGroupAll()
    {
        $this->requireLogin();
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $width = I('width', 160, 'intval');
        $height = I('height', 210, 'intval');
        $member = D('GroupMember')->where(array('uid' => is_login(), 'status' => 1))->field('group_id')->select();
        $group_ids = getSubByKey($member, 'group_id');
        $myattend = D('Group/Group')->where(array('id' => array('in', $group_ids), 'status' => 1))->page($aPage, $aCount)->order('uid = ' . is_login() . ' desc ,uid asc')->select();

        foreach ($myattend as &$g) {
            $g['create_time'] = friendlyDate($g['create_time']);
            if(!$width){
                $g['background'] = get_cover($g['cover_id']);;
            }else{
                $g['background'] = getThumbImageByCoverId($g['cover_id'], $width, $height);
            }
            if(!$width){
                $g['logo'] = get_cover($g['cover_id']);;
            }else{
                $g['logo'] = getThumbImageByCoverId($g['cover_id'], $width, $height);
            }
            $g['title'] = op_t($g['title']);
            $g['detail'] = op_t($g['detail']);
            $g['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $g['uid']);
            $g['menmber'] = D('GroupMember')->where(array('group_id' => $g['id']))->order('create_time asc')->count();
            foreach ($g['menmber'] as &$user) {
                $user['user'] = query_user(array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title'), $user['uid']);
                /*$user['isCreator'] = checkIsCreator($user['uid'], 'Group', $user['group_id']);*/
            }
        }
        unset($g);
        $list = array('list' => $myattend);

        $this->apiSuccess('返回成功', $list);


    }

    //返回群组下的帖子信息
    public function getPostAll()
    {

        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aGroupId = I('group_id', 0, 'intval');
        $map['status'] = 1;
        $aGroupId && $map['group_id'] = $aGroupId;


        $Posts = D('Group/GroupPost')->where($map)->page($aPage, $aCount)->order('create_time desc')->select();

        foreach ($Posts as &$p) {
            $p['create_time'] = friendlyDate($p['create_time']);
            $p['title'] = op_t($p['title']);
            $p['content'] = op_t($p['content']);
            $p['last_reply_time'] = friendlyDate($p['last_reply_time']);
            $p['update_time'] = friendlyDate($p['update_time']);
            $p['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $p['uid']);

        }
        unset($p);
        $list = array('list' => $Posts);

        $this->apiSuccess('返回成功', $list);
    }

    //创建与编辑群组
    public function addGroup()
    {
        if (!is_login()) {
            $this->apiError('请登陆后再发起活动。');
        }

        //基本信息
        $aTitle = I('title', '', 'op_t');
        $aDetail = I('detail', '', 'op_h');
        $aTypeId = I('type_id', 0, 'intval');
       /* $aBackground = I('background', 0, 'intval');*/
        $aType = I('type', 0, 'intval');
        $aLogo = I('logo', 0, 'intval');
        $aId = I('id', 0, 'intval');

     /*   if (!$aBackground) {
            $this->apiError('请上传封面。');
        }*/
        if (trim(op_t($aTitle)) == '') {
            $this->apiError('请输入标题。');
        }
        if ($aTypeId == 0) {
            $this->apiError('请选择分类。');
        }
        if (trim(op_h($aDetail)) == '') {
            $this->apiError('请填写群组介绍。');
        }
   /*     if (!$aLogo) {
            $this->apiError('请上传背景');
        }*/


        $data = D('Group/Group')->create();
        $data['detail'] = $aDetail;
        $data['title'] = $aTitle;
        $data['logo'] = $aLogo;
       /* $data['background'] = $aBackground;*/
        $data['type'] = $aType;
        $data['type_id'] = $aTypeId;
        $data['create_time'] = time();
        $data['uid'] = is_login();

        //根据id查看是否已有活动
        if ($aId) {
            $Group = D('Group/Group')->find($aId);
            if (!is_administrator(is_login())) {
                //不是管理员则进行检测
                if ($Group['uid'] != is_login()) {
                    $this->apiError('无权编辑');
                }
            }

            //编辑基本信息
            $result = D('Group/Group')->where(array('id' => $aId))->save($data);

            $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Event/Index/group', array('id' => $aId));
            $weiboApi = new WeiboApi();
            $weiboApi->resetLastSendTime();
            $weiboApi->sendWeibo("我修改了群组【" . $aTitle . "】：" . $postUrl);
            if ($result) {
                $this->apiSuccess('编辑成功。', U('detail', array('id' => $data['id'])));
            } else {
                $this->apiError('编辑失败。');
            }
        } else {
            if (modC('NEED_VERIFY',1,'group') && !is_administrator()) //需要审核且不是管理员
            {
                $data['status'] = 0;

                $user = query_user(array('username', 'nickname'), is_login());
                D('Common/Message')->sendMessage(C('USER_ADMINISTRATOR'), "{$user['nickname']}发布了一个活动，请到后台审核。", $title = '活动发布提醒', U('Admin/Group/verify'), is_login(), 2);
            }
            $Group = D('Group/Group')->add($data);
            //同步到微博
            $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Group/Index/group', array('id' => $Group));

            $weiboModel=D('Weibo/Weibo');
            $weiboModel->addWeibo("我发布了一个新的活动【" . $aTitle . "】：" . $postUrl);

            if ($Group) {
                $this->apiSuccess('发布成功。但需管理员审核通过后才会显示在列表中，请耐心等待。');
            } else {
                $this->apiError('发布失败。');
            }
        }
    }

    //解散群组

    public function endGroup()
    {
        $this->requireLogin();
        $aGroupId = I('id', 0, 'intval');
        $Group = D('Group/Group')->where(array('status' => 1, 'id' => $aGroupId))->find();
        if (!$Group) {
            $this->apiError('群组不存在！');
        }
        if ($Group['uid'] == is_login() || is_administrator(is_login())) {
            $res = D('Group/Group')->where(array('status' => 1, 'id' => $aGroupId))->setField('status', -1);
            if ($res) {
                $this->apisuccess('解散成功！');
            } else {
                $this->apiError('解散操作失败！');
            }
        } else {
            $this->apiError('非群组发起者操作！');
        }

    }

    //公告信息
    public function getNotice()
    {
        $aGroupId = I('group_id', 0, 'intval');
        /*$aContent= I('content','', 'op_t');*/
        $Notice = D('Group/GroupNotice')->where($aGroupId)->find();
        $Notice['create_time'] = friendlyDate($Notice['create_time']);
        $list = array('list' => $Notice);

        $this->apiSuccess('返回成功', $list);
    }

    //加入群组
    public function joinGroup()
    {

        $aGroupId = I('group_id', 0, 'intval');
        //查询权限
        $group = D('Group/Group')->where(array('id' => $aGroupId, 'status' => 1))->find();
        if (!$group) {
            $this->apiError('该群组不存在');
        }
        $this->requireLogin();
        //判断是否已经加入
        $is_join = D('Group/GroupMember')->where(array('group_id' => $aGroupId, 'uid' => is_login(), 'status' => 1))->find();
        if ($is_join) {
            $this->apiError('已经加入了该群组');
        }

        // 已经加入但还未审核
        if (D('Group/GroupMember')->where(array('uid' => is_login(), 'group_id' => $aGroupId, 'status' => 0))->select()) {
            $this->apiError('请耐心等待管理员审核');
        }

        // 获取群组的类型 0为公共的 1为私有的

        $group = D('Group/Group')->where(array('id' => $aGroupId, 'status' => 1))->find();
        $type = $group['type'];
        //要存入数据库的数据
        $data['group_id'] = $aGroupId;
        $data['uid'] = is_login();
        $data['create_time'] = time();

        if ($type == 1) {
            // 群组为私有的。
            $data['status'] = 0;
            $res = D('Group/GroupMember')->add($data);

            $group = D('Group/Group')->where(array('status' => 1, 'id' => $aGroupId))->find();

            // 发送消息
            D('Message')->sendMessage($group['uid'], get_nickname(is_login()) . "请求加入群组【{$group['title']}】", '加入群组审核', U('group/Manage/member', array('group_id' => $aGroupId, 'status' => 0)), is_login());
            $this->clearcache($aGroupId);
            if ($res) {
                $this->apiSuccess('加入成功，等待群组管理员审核！');
            } else {
                $this->apiError('加入失败');
            }
        } else {
            // 群组为公共的
            $data['status'] = 1;
            $data['update_time'] = $data['create_time'];
            $res = D('Group/GroupMember')->add($data);
            //添加到最新动态
            $dynamic['group_id'] = $aGroupId;
            $dynamic['uid'] = is_login();
            $dynamic['type'] = 'attend';
            $dynamic['create_time'] = $data['create_time'];
            D('Group/GroupDynamic')->add($dynamic);
            if ($res) {
                $this->apiSuccess('加入成功');
            } else {
                $this->apiError('加入失败');
            }
        }


    }


    //退出群组
    public function quitGroup()
    {
        $this->requireLogin();
        $aGroupId = I('group_id', 0, 'intval');
        $Reg = D('Group/Group')->where(array('status' => 1, 'id' => $aGroupId))->find();

        if (!$Reg) {
            $this->apiError('群组不存在！');
        }
        if ($Reg['uid'] == is_login() || is_administrator(is_login())) {
            $res = D('Group/GroupMember')->where(array('status' => 1, 'group_id' => $aGroupId, 'uid' => is_login()))->delete();
            if ($res) {
                $this->apiSuccess('退出成功！');
            } else {
                $this->apiError('你还未加入此群组，无法退出！');
            }
        } else {
            $this->apiError('非群组发起者操作！');
        }
    }


    //邀请好友加入
    public function GroupInvite()
    {
        $this->requireLogin();
        $aId = I('id');
        $toUid = I('uid');
        $group = D('Group/Group')->find($aId);
        $friend = D('Follow')->where(array('who_follow' => is_login(), 'follow_who' => $toUid))->find();
        if ($friend['follow_who']) {
            D('Message')->sendMessage($toUid, get_nickname(is_login()) . "邀请您加入群组【{$group['title']}】  <a class='ajax-post' href='" . U('group/index/attend', array('group_id' => $aId)) . "'>接受邀请</a>", '邀请加入群组', U('group/index/group', array('id' => $aId)), is_login());
            $this->apisuccess('邀请成功！');
        } else {
            $this->apisuccess('邀请失败！');
        }
    }

    //剔除组员
    public function rejectGroupPeople()
    {
        $aGroupId = I('group_id', 0, 'intval');
        $aUid = I('uid', 0, 'intval');
        $map['status'] = 1;
        $aGroupId && $map['group_id'] = $aGroupId;

        $Group = D('Group/Group')->where(array('id' => $aGroupId))->find();
        if (!is_administrator(is_login())) {
            //不是管理员则进行检测
            if ($Group['uid'] != is_login()) {
                $this->apiError('无权管理成员');
            }
        }
        //剔除组员
        $rejectGroupPeople = D('Group/GroupMember')->where(array('group_id' => $aGroupId, 'status' => 1, 'uid' => $aUid))->delete();
        if ($rejectGroupPeople) {
            $this->apiSuccess('移出成功！');
        } else {
            $this->apiError('移出操作失败！');
        }
    }

    //添加组员（审核组员）接口
    public function addGroupPeople()
    {
        $aGroupId = I('group_id', 0, 'intval');
        $aUid = I('uid', 0, 'intval');
        $map['status'] = 1;
        $aGroupId && $map['group_id'] = $aGroupId;

        $Group = D('Group/Group')->where(array('id' => $aGroupId))->find();
        if (!is_administrator(is_login())) {
            //不是管理员则进行检测
            if ($Group['uid'] != is_login()) {
                $this->apiError('无权管理成员');
            }
        }
        //审核
        $addGroupPeople = D('Group/GroupMember')->where(array('group_id' => $aGroupId, 'status' => 0, 'uid' => $aUid))->setField('status', 1);
        if ($addGroupPeople) {
            $this->apiSuccess('审核成功！');
        } else {
            $this->apiError('审核操作失败！');
        }
    }

    /*
        //接受邀请
        public
        function receiveInvite()
        {

        }*/

//新建帖子分类操作
    public function addPostCategory()
    {
        $this->requireLogin();
        $aGroupId = I('group_id', 0, 'intval');
        $aTitle = I('title', '', 'op_h');
        $aId = I('id', 0, 'intval');

        //数据配置
        $data = D('Group/GroupPostCategory')->create();
        $data['title'] = $aTitle;
        $data['create_time'] = time();
        $Group = D('Group/Group')->where(array('id' => $aGroupId))->find();
        if (!$Group) {
            $this->apiError('帖子不存在。');
        }
        if (!$aTitle) {
            $this->apiError('请填写分类标题。');
        }
        if ($aId) {
            if (!is_administrator(is_login())) {
                //不是管理员则进行检测
                if ($Group['uid'] != is_login()) {
                    $this->apiError('无权编辑');
                }
            }
            //编辑基本信息
            $Cate = D('Group/GroupPostCategory')->where(array('id' => $aId))->save($data);


            if ($Cate) {
                $this->apiSuccess('编辑分类成功');
            } else {
                $this->apiError('编辑分类成功');
            }

        } else {
            $data['status'] = 1;
            $data['group_id'] = $aGroupId;
            D('Group/GroupPostCategory')->add($data);
            if ($data) {
                $this->apiSuccess('增加分类成功');
            } else {
                $this->apiError('增加分类失败');
            }

        }
    }

//展示帖子分类
    public function PostCategory()
    {
        $aGroupId = I('group_id', 0, 'intval');
        $map['status'] = 1;
        $aGroupId && $map['group_id'] = $aGroupId;
        /*$aContent= I('content','', 'op_t');*/
        $PostCategory = D('Group/GroupPostCategory')->where($map)->order('sort asc')->select();
        $list = array('list' => $PostCategory);
        $this->apiSuccess('返回成功', $list);
    }


//发布或编辑帖子
    public function sendPost()
    {
        $this->requireLogin();
        $aGroupId = I('group_id', 0, 'intval');
        $aPostId = I('id', 0, 'intval');
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
        $this->requireGroupAllowPublish($aGroupId);

        if ($isEdit) {
            $data = array('id' => intval($aPostId), 'title' => $aTitle, 'content' => $aContent, 'parse' => 0, 'group_id' => intval($aGroupId));
            $result = D('Group/GroupPost')->editPost($data);
            if (!$result) {
                $this->apiError('编辑失败：');
            }
        } else {
            $data = array('uid' => is_login(), 'title' => $aTitle, 'content' => $aContent, 'parse' => 0, 'group_id' => intval($aGroupId));

            $result = D('Group/GroupPost')->createPost($data);

            if (!$result) {
                $this->apiError('发表失败');
            }
            $aPostId = $result;
        }


        //发布帖子成功，发送一条微博消息
        $postUrl = "http://$_SERVER[HTTP_HOST]" . U('group/Index/detail', array('id' => $aPostId));
        $weiboModel=D('Weibo/Weibo');
        $weiboModel->addWeibo("我发布了一个新的活动【" . $aTitle . "】：" . $postUrl);


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
            $weiboModel=D('Weibo/Weibo');
            $weiboModel->addWeibo("我发布了一个新的活动【" . $aTitle . "】：" . $postUrl);
        } else {
            $weiboModel=D('Weibo/Weibo');
            $weiboModel->addWeibo("我发布了一个新的活动【" . $aTitle . "】：" . $postUrl);
        }


        //显示成功消息
        $message = $isEdit ? '编辑成功。' : '发表成功。' .  cookie('score_tip');


        //返回成功消息
        $post = D('group/groupPost')->where('id=' . $aPostId)->find();
        $post ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));
        $post['content'] = text_part($post['content']);

        $this->apiSuccess($message, $post);

    }

//展示帖子回复信息
    public function getPostReply()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aId = I('post_id', 0, 'intval');
        $map['status'] = 1;
        $aId && $map['post_id'] = $aId;


        $replyList = D('Group/GroupPostReply')->where($map)->page($aPage, $aCount)->select();
        $uid = D('Group/GroupPost')->where(array('id' => $aId))->field('uid')->select();
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
            $v['toReplyList'] = D('group/groupLzlReply')->where(array('to_f_reply_id' => $v['id']))->page($aPage, $aCount)->select();

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

//回复帖子
    public function doReplyPost()
    {
        $this->requireLogin();

        $aPostId = I('post_id', 0, 'intval');
        $map['status'] = 1;
        $aPostId && $map['post_id'] = $aPostId;
        if (!$aPostId) {
            $this->apiError('帖子不存在');
        }
        $aContent = I('content', '', 'op_t');
        $uid = D('Group/GroupPost')->where(array('id' => $aPostId))->field('uid')->select();
        $uid = array_column($uid, 'uid');

        $result = D('Group/GroupPostReply')->addReply($aPostId, $aContent);

        $reply = D('Group/GroupPostReply')->where(array('id' => $result))->find();
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

//展示楼中楼的回复
    public function PostLzl()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');

        $aPostId = I('post_id', 0, 'intval');

        $aId = I('to_f_reply_id', 0, 'intval');
        $map['status'] = 1;
        $aId && $map['to_f_reply_id'] = $aId;

        $uid = D('Group/GroupPost')->where(array('id' => $aPostId))->field('uid')->select();
        $uid = array_column($uid, 'uid');

        $LzlPost = D('Group/GroupLzlReply')->where($map)->page($aPage, $aCount)->select();

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

//回复帖子的回复
    public function doPostLzl()
    {
        $this->requireLogin();

        $aToReplyId = I('to_reply_id', 0, 'intval');
        $aContent = I('content', '', 'op_t');


        if ($aToReplyId) {
            $result = D('Group/GroupLzlReply')->where(array('id' => $aToReplyId))->find();
            $data['post_id'] = $result['post_id'];
            $data['to_f_reply_id'] = $result['to_f_reply_id'];
            $data['to_uid'] = $result['uid'];
            $data['uid'] = is_login();
            $data['ctime'] = time();
            $data['content'] = $aContent;
            $data['to_reply_id'] = $aToReplyId;

            $result = D('Group/GroupLzlReply')->add($data);

            $LzlReply = D('Group/GroupLzlReply')->where(array('id' => $result))->find();

            $LzlReply ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));

        } else {
            $aFToReplyId = I('to_f_reply_id', 0, 'intval');
            $result = D('Group/GroupLzlReply')->where(array('id' => $aFToReplyId))->find();
            $data['post_id'] = $result['post_id'];
            $data['to_f_reply_id'] = $aFToReplyId;
            $data['to_uid'] = $result['uid'];
            $data['uid'] = is_login();
            $data['ctime'] = time();
            $data['content'] = $aContent;
            $result = D('GroupLzlReply')->add($data);

            $LzlReply = D('Group/GroupLzlReply')->where(array('id' => $result))->find();

            $LzlReply ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));

        }


        $this->apiSuccess('返回成功', $LzlReply);

    }

//帖子收藏
    public function postBookmark()
    {
        $this->requireLogin();
        $aPostId = I('post_id', 0, 'intval');
        $Post = D('Group/GroupPost')->where(array('id' => $aPostId, 'status' => 1))->find();
        if (!$Post) {
            $this->apiError('收藏失败，帖子不存在');
        }
        $collection = D('Group/GroupBookmark')->where(array('post_id' => $aPostId, 'uid' => is_login()))->find();
        if (!$collection) {
            $data['uid'] = is_login();
            $data['post_id'] = $aPostId;
            $data['create_time'] = time();
            //写入数据库
            D('Group/GroupBookmark')->add($data);
            $this->apiSuccess('收藏成功');
        } else {
            $this->apiError('已收藏，请勿重复收藏');
        }
        $this->apiSuccess('返回成功');
    }

//取消收藏
    public function RejectBookmark()
    {
        $this->requireLogin();
        $aPostId = I('post_id', 0, 'intval');
        $collection = D('Group/GroupBookmark')->where(array('post_id' => $aPostId, 'uid' => is_login()))->find();
        if (!$collection) {
            $this->apiError('取消失败,无收藏记录');
        } else {
            D('Group/GroupBookmark')->where(array('post_id' => $aPostId, 'uid' => is_login()))->delete();
            $this->apiSuccess('取消收藏成功');
        }
    }

//帖子点赞信息
    public function postSupport()
    {

        if (!is_login()) {
            exit($this->apiError('请登录后再点赞。'));
        }
        $appname = 'Group';
        $table = 'post';
        $aRow = I('post_id');
        $aMessage_uid = intval(I('uid'));
        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['row'] = $aRow;
        $support['uid'] = is_login();

        if (D('Support')->where($support)->count()) {
            $this->apiError('您已经赞过，不能再赞了。');

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
                $this->apiSuccess('感谢你的支持。');
            } else {
                $this->apiError('写入数据库失败。');
            }
        }

    }

    /*——————————————————————私有函数————————————————————————————*/
//验证是否允许登陆 板块拥有权限 用户组是否拥有权限
    private
    function requireGroupAllowPublish($aGroupId)
    {

        $this->requireGroupExists($aGroupId);
        $this->requireLogin();
        $this->requireGroupAllowCurrentUserGroup($aGroupId);
    }


//确认群组是否存在
    private
    function requireGroupExists($aId)
    {
        if (!$this->isGroupExists($aId)) {
            $this->apiError('群组不存在');
        }
    }

    private
    function isGroupExists($aId)
    {
        $aId = intval($aId);
        $group = D('Group/Group')->where(array('id' => $aId, 'status' => 1));
        return $group ? true : false;
    }

    private
    function requireGroupAllowCurrentUserGroup($aGroupId)
    {
        $aGroupId = intval($aGroupId);
        if (!$this->isgroupAllowCurrentUserGroup($aGroupId)) {
            $this->apiError('该板块不允许发帖');
        }
    }

    private
    function isGroupAllowCurrentUserGroup($aId)
    {
        $aId = intval($aId);
        //如果是超级管理员，直接允许
        if (is_login() == 1) {
            return true;
        }

        //如果帖子不属于任何板块，则允许发帖
        if (intval($aId) == 0) {
            return true;
        }

        //读取群组的基本信息
        $group = D('Group/Group')->where(array('id' => $aId))->find();
        $userGroups = explode(',', $group['allow_user_group']);

        //读取用户所在的用户组
        $list = M('AuthGroupAccess')->where(array('uid' => is_login()))->select();
        foreach ($list as &$e) {
            $e = $e['group_id'];
        }

        //每个用户都有一个默认用户组
        $list[] = '1';

        //判断用户组是否有权限
        $list = array_intersect($list, $userGroups);
        return $list ? true : false;
    }

    private
    function clearCache($support)
    {
        unset($support['uid']);
        unset($support['create_time']);
        $cache_key = "support_count_" . implode('_', $support);
        S($cache_key, null);
    }
}