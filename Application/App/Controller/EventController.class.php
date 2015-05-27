<?php
/**
 * Created by PhpStorm.
 * User: 汪汪汪
 * Date: 4/4/14
 * Time: 9:29 AM
 */

namespace App\Controller;

use App\Model;
use Think\Controller;
use Weibo\Api\WeiboApi;

class EventController extends BaseController
{
    /* 获取当前分类信息 */
    public function getEventModules()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $EventModules = D('Event/EventType')->where(array('status' => 1, 'pid' => 0))->page($aPage, $aCount)->order('create_time asc')->select();
        foreach ($EventModules as &$e) {
            $e['EventSecond'] = D('Event/EventType')->where(array('status' => 1, 'pid' => $e['id']))->select();
        }
        unset($e);
        $list = array('list' => $EventModules);
        $this->apiSuccess('返回成功', $list);
    }


    /* 获取某一个分类的活动信息 */
    public function getEventsAll()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aId = I('type_id', 0, 'intval');

        $map['status'] = 1;
        $aId && $map['type_id'] = $aId;

        $event = D('Event/Event')->where($map)->page($aPage, $aCount)->order('create_time desc')->select();
        foreach ($event as &$v) {
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v['content'], $arr); //匹配所有的图片
            $v['create_time'] = friendlyDate($v['create_time']);
            $v['update_time'] = friendlyDate($v['update_time']);
            $v['sTime'] = time_format($v['sTime']);

            $v['cover_url'] = get_cover($v['cover_id']);
            $v['imgList'] = $arr[1];
            $v['explain'] = op_t($v['explain']);
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
            if ($v['deadline'] < time()) {
                $v['is_deadline'] = '1';
            } else {
                $v['is_deadline'] = '0';
            }
            if ($v['eTime'] < time()) {
                $v['is_end'] = '1';
            } else {
                $v['is_end'] = '0';
            }
            $v['eTime'] = time_format($v['eTime']);
            $v['deadline'] = time_format($v['deadline']);
        }
        unset($v);
        $list = array('list' => $event);
        $this->apiSuccess('返回成功', $list);
    }

    public function getRecommend()
    {
        $rand_event = D('Event/Event')->where(array('is_recommend' => 1))->limit(2)->order('rand()')->select();
        foreach ($rand_event as &$v) {
            $v['user'] = query_user(array('id', 'username', 'nickname', 'space_url', 'space_link', 'avatar128', 'rank_html'), $v['uid']);
            $v['type'] = $type = D('Event/EventType')->where('id=' . $v['type_id'])->find();
            $v['check_isSign'] = D('Event/event_attend')->where(array('uid' => is_login(), 'event_id' => $v['id']))->select();
            $v['create_time'] = friendlyDate($v['create_time']);
            $v['update_time'] = friendlyDate($v['update_time']);
            $v['sTime'] = time_format($v['sTime']);
            $v['eTime'] = time_format($v['eTime']);
            $v['deadline'] = time_format($v['deadline']);
        }
        unset($v);
        if (!$rand_event) {
            $this->apiError('没有推荐');
        }
        $list = array('list' => $rand_event);
        $this->apiSuccess('返回成功', $list);
    }

    /* 获取我的活动信息 */
    public function getWeEvents()
    {

        $this->requireLogin();
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $weEvent = D('Event/Event')->where(array('uid' => is_login(), 'status' => 1))->page($aPage, $aCount)->order('create_time desc,signCount desc')->select();
        foreach ($weEvent as &$v) {
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v['content'], $arr); //匹配所有的图片

            $v['imgList'] = $arr[1];
            $v['explain'] = op_t($v['explain']);
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
        }
        unset($v);
        $list = array('list' => $weEvent);
        $this->apiSuccess('返回成功', $list);
    }


    /* 获取参与活动的人信息 */
    public function getPeopleInfoEvents()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aEventId = I('event_id', 0, 'intval');
        $map['status'] = 1;
        $aEventId && $map['event_id'] = $aEventId;
        if (!$aEventId) {
            $this->apiError('活动不存在！');
        }

        $event = D('Event/EventAttend')->where($map)->page($aPage, $aCount)->select();
        if($event){
            foreach ($event as &$v) {
                $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
            }
        }else{
            $this->apiError('此活动暂无人参加');
        }


        $list = array('list' => $event);
        $this->apiSuccess('返回成功', $list);
    }

    /*活动详情*/
    public function eventDetail()
    {
       /* dump(query_user('uid', is_login()));*/
        $aId = I('id',0, 'intval');
        $map['status'] = 1;
        $aId && $map['id'] = $aId;
        if (!$aId) {
            $this->apiError('活动不存在！');
        }
        $Event = D('Event/Event')->where($map)->select();
        $uid = D('Event/EventAttend')->where(array('event_id' => $aId))->field('uid')->select();
/*        dump($uid);*/
        foreach ($Event as &$v) {
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v['content'], $arr); //匹配所有的图片
            $v['create_time'] = friendlyDate($v['create_time']);
            $v['update_time'] = friendlyDate($v['update_time']);
            $v['sTime'] = time_format($v['sTime']);

            $v['cover_url'] = get_cover($v['cover_id']);
            $v['imgList'] = $arr[1];
            $v['explain'] = op_t($v['explain']);
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
            if ($v['deadline'] < time()) {
                $v['is_deadline'] = '1';
            } else {
                $v['is_deadline'] = '0';
            }
            if ($v['eTime'] < time()) {
                $v['is_end'] = '1';
            } else {
                $v['is_end'] = '0';
            }

      /*      if ((query_user('uid', is_login())==$uid)) {
                $v['is_Creator'] = '1';
            } else {
                $v['is_Creator'] = '0';
            }*/

            $v['eTime'] = time_format($v['eTime']);
            $v['deadline'] = time_format($v['deadline']);
        }
        unset($v);
        $list = array('list' => $Event);
        $this->apiSuccess('返回成功', $list);
    }

    /* 参加活动报名*/
    public function joinEvents()
    {
        $aEventId = I('event_id', 0, 'intval');
        $map['status'] = 1;
        $aEventId && $map['event_id'] = $aEventId;
        $aName = I('name', 0, 'op_h');
        $aPhone = I('phone', '', 'op_t');
        $Event = D('Event/Event')->where(array('id' => $aEventId))->find();

        if (!$Event) {
            $this->apiError('活动不存在！');
        }
        if ($Event['deadline'] < time()) {
            $this->apiError('报名已经截止。');
        }
        if (!is_login()) {
            $this->apiError('请登陆后再报名。');
        }
        if (!$aEventId) {
            $this->apiError('参数错误。');
        }
        if (trim(op_t($aName)) == '') {
            $this->apiError('请输入姓名。');
        }
        if (trim($aPhone) == '') {
            $this->apiError('请输入手机号码。');
        }


        $dope = D('event_attend')->where(array('uid' => is_login(), 'event_id' => $aEventId))->select();
        if (!$dope) {
            $data['uid'] = is_login();
            $data['event_id'] = $aEventId;
            $data['name'] = $aName;
            $data['phone'] = $aPhone;
            $data['creat_time'] = time();
            $data['status'] = 0;
            $news = D('Event/Event_attend')->add($data);

            if ($news) {

                D('Common/Message')->sendMessageWithoutCheckSelf($Event['uid'], query_user('nickname', is_login()) . '报名参加了活动]' . $Event['title'] . ']，请速去审核！', '报名通知', U('Event/Index/member', array('id' => $aEventId)), is_login());

                D('Event/Event')->where(array('id' => $aEventId))->setInc('signCount');
                $this->apisuccess('报名成功。');
            } else {
                $this->apiError('报名失败。');
            }
        } else {
            $this->apiError('您已经报过名了。');

        }



    }
    /*D('Common/Message')->sendMessage($toUid, $user['nickname'] . '报名参加了活动'.$event['title'].'请速去审核','审核提醒', is_login(),1);*/


    // 返回某个专辑的评论列表
    public function getEventComments()
    {

        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');


        $aRowId = I('row_id', 0, 'intval');


        if (!D('Event/Event')->where(array('id' => $aRowId))->find()) {
            exit ($this->apiError('活动不存在'));
        }
        $uid = D('Event/Event')->where(array('id' => $aRowId))->field('uid')->select();
        $uid = array_column($uid, 'uid');
        $arr = array();
        $IssueComments = D('Event/LocalComment')->where(array('app' => 'Event', 'mod' => 'event', 'row_id' => $aRowId, 'status' => 1))->page($aPage, $aCount)->order('create_time desc')->select();
        foreach ($IssueComments as &$v) {
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v['content'], $arr); //匹配所有的图片
            $v['imgList'] = $arr[1];
            $v['content'] = op_t($v['content']);
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'), $v['uid']);
            $v['create_time'] = friendlyDate($v['create_time']);

            if (in_array($v['uid'], $uid)) {
                $v['is_landlord'] = '1';
            } else {
                $v['is_landlord'] = '0';
            }

        }
        unset($v);
        $list = array('list' => $IssueComments);
        $this->apiSuccess('返回成功', $list);
    }

     /*发送活动评论*/
    public function sendEventComment()
    {
        $this->requireLogin();
        $aRowId = I('row_id', 0, 'intval');
        $aContent = I('content', '', 'op_h');
        $aApp = 'Event';
        $aMod = 'event';

        if (!D('Event/Event')->where(array('id' => $aRowId))->find()) {
            exit ($this->apiError('专辑不存在'));
        }

        $data = array('uid' => is_login(), 'row_id' => $aRowId, 'parse' => 0, 'mod' => $aMod, 'app' => $aApp, 'content' => $aContent, 'status' => '1', 'create_time' => time());

        $data = D('Event/LocalComment')->create($data);

        if (!$data) return false;

        $result = D('Event/LocalComment')->add($data);

        D('Event/Event')->where(array('status' => 1, 'id' => $aRowId))->setInc('reply_count');

        $reply = D('Event/Event')->where(array('id' => $result))->find();

        preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $reply['content'], $arr); //匹配所有的图片
        $reply['imgList'] = $arr[1];
        $reply['content'] = op_t($reply['content']);
        $reply ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'));


        $this->apiSuccess('返回成功', $reply);
    }

    /*提前关闭*/
    public function endEvents()
    {
        $this->requireLogin();
        $aEvent_id = I('event_id', 0, 'intval');
        $Event = D('Event/Event')->where(array('status' => 1, 'id' => $aEvent_id))->find();

        if (!$Event) {
            $this->apiError('活动不存在！');
        }
        //判断是否有权限提前关闭活动
        if ($Event['uid'] == is_login() || is_administrator(is_login())) {
            $news = D('Event/Event')->where(array('status' => 1, 'id' => $aEvent_id))->setField('eTime', time());
            if ($news) {
                $this->apiSuccess('提前关闭成功！');
            } else {
                $this->apiError('提前关闭失败！');
            }
        } else {
            $this->apiError('非活动发起者操作！');
        }
    }

    /* 删除活动*/
    public function deleteEvents()
    {
        $this->requireLogin();
        $aEventId = I('id', 0, 'intval');
        $Events = D('Event/Event')->where(array('status' => 1, 'id' => $aEventId))->find();

        if (!$Events) {
            $this->apiError('活动不存在！');
        }
        if ($Events['uid'] == is_login() || is_administrator(is_login())) {
            $res = D('Event/Event')->where(array('status' => 1, 'id' => $aEventId))->setField('status', 0);
            if ($res) {
                $this->apisuccess('删除成功！');
            } else {
                $this->apiError('操作失败！');
            }
        } else {
            $this->apiError('非活动发起者操作！');
        }
    }

    /* 发起或者编辑活动*/
    public function addEvents()
    {
        if (!is_login()) {
            $this->apiError('请登陆后再发起活动。');
        }
        $aStime = I('sTime');
        $aDeadline = I('deadline');
        $aAddress = I('address', '', 'op_h');
        $aExplain = I('explain', '', 'op_h');
        $aTypeId = I('type_id', '', 'intval');
        $aTitle = I('title', '', 'op_t');
        $aCover_id = I('cover_id', 0, 'intval');
        $aEtime = I('eTime');
        $aLimitCount = I('limitCount', 0, 'intval');
        $aId = I('id', 0, 'intval');


        if (!$aCover_id) {
            $this->apiError('请上传封面。');
        }
        if (!$aLimitCount) {
            $this->apiError('请输入限制人数。');
        }
        if (trim(op_t($aTitle)) == '') {
            $this->apiError('请输入标题。');
        }
        if ($aTypeId == 0) {
            $this->apiError('请选择分类。');
        }
        if (trim(op_h($aExplain)) == '') {
            $this->apiError('请输入内容。');
        }
        if (trim(op_h($aAddress)) == '') {
            $this->apiError('请输入地点。');
        }
        if (trim(op_h($aAddress)) == '') {
            $this->apiError('请输入地点。');
        }
        if ($aStime < $aDeadline) {
            $this->apiError('报名截止不能大于活动开始时间');
        }
        if ($aDeadline == '') {
            $this->apiError('请输入截止日期');
        }
        if ($aStime > $aEtime) {
            $this->apiError('活动开始时间不能大于活动结束时间');
        }
        $data = D('Event/Event')->create();
        $data['explain'] = $aExplain;
        $data['title'] = $aTitle;
        $data['sTime'] = $aStime;
        $data['eTime'] = $aEtime;
        $data['cover_id'] = $aCover_id;
        $data['deadline'] = $aDeadline;
        $data['type_id'] = $aTypeId;
        $data['address'] = $aAddress;
        $data['limitCount'] = $aLimitCount;
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['uid'] = is_login();
        //根据id查看是否已有活动
        if ($aId) {
            $contentAlready = D('Event/Event')->find($aId);
            if (!is_administrator(is_login())) {
                //不是管理员则进行检测
                if ($contentAlready['uid'] != is_login()) {
                    $this->apierror('无权编辑');
                }
            }

            $result = D('Event/Event')->where(array('id' => $aId))->save($data);

            if(D('Common/Module')->isInstalled('Weibo')){//安装了微博模块
                //同步到微博
                $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Event/Index/detail', array('id' => $aId));

                $weiboModel=D('Weibo/Weibo');
                $weiboModel->addWeibo("我发布了一个新的活动【" . $aTitle . "】：" . $postUrl);
            }


            if ($result) {
                $this->apisuccess('编辑成功。', U('detail', array('id' => $data['id'])));
            } else {
                $this->apisuccess('编辑失败。', '');
            }
        } else {

            if (modC('NEED_VERIFY', 1,'event') && !is_administrator()) //需要审核且不是管理员
            {
                $content['status'] = 0;

                $user = query_user(array('username', 'nickname'), is_login());
                D('Common/Message')->sendMessage(C('USER_ADMINISTRATOR'), "{$user['nickname']}发布了一个活动，请到后台审核。", $title = '活动发布提醒', U('Admin/Event/verify'), is_login(), 2);
            }
            $Event = D('Event/Event')->add($data);
            //同步到微博
            if(D('Common/Module')->isInstalled('Weibo')){//安装了微博模块
                //同步到微博
                $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Event/Index/detail', array('id' => $aId));
                $weiboModel=D('Weibo/Weibo');
                $weiboModel->addWeibo("我发布了一个新的活动【" . $aTitle . "】：" . $postUrl);
            }

            if ($Event) {
                $this->apisuccess('发布成功。但需管理员审核通过后才会显示在列表中，请耐心等待。');
            } else {
                $this->apisuccess('发布失败。', '');
            }
        }
    }


    public function shenhe($tip)
    {
        $this->requireLogin();
        $aEventId = I('id', 0, 'intval');

        $event_content = D('Event')->where(array('status' => 1, 'id' => $aEventId))->find();
        if (!$event_content) {
            $this->apierror('活动不存在！');
        }
        if ($event_content['uid'] == is_login()) {
            $res = D('Event/EventAttend')->where(array('uid' => is_login(), 'event_id' => $aEventId))->setField('status', $tip);
            if ($tip) {
                D('Event/Event')->where(array('id' => $aEventId))->setInc('attentionCount');
                D('Common/Message')->sendMessageWithoutCheckSelf(is_login(), query_user('nickname', is_login()) . '已经通过了您对活动' . $event_content['title'] . '的报名请求', '审核通知', U('Event/Index/detail', array('id' => $aEventId)), is_login());
            } else {
                D('Event/Event')->where(array('id' => $aEventId))->setDec('attentionCount');
                D('Common/Message')->sendMessageWithoutCheckSelf(is_login(), query_user('nickname', is_login()) . '取消了您对活动[' . $event_content['title'] . ']的报名请求', '取消审核通知', U('Event/Index/member', array('id' => $aEventId)), is_login());
            }
            if ($res) {
                $this->apisuccess('操作成功');
            } else {
                $this->apierror('操作失败！');
            }
        } else {
            $this->apierror('操作失败，非活动发起者操作！');
        }
    }
}
