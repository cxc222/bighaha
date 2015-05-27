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

class IssueController extends BaseController
{


    //返回专辑分类信息
    public function getIssueModules()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');

        $IssueModules = D('Issue/Issue')->where(array('status' => 1, 'pid' => 0))->page($aPage, $aCount)->order('create_time desc')->select();
        foreach ($IssueModules as &$v) {
            $v['Issues'] = D('Issue/Issue')->where(array('status' => 1, 'pid' => $v['id']))->select();

            $v['create_time'] = friendlyDate($v['create_time']);
            $v['update_time'] = friendlyDate($v['update_time']);
            foreach ($v['Issues'] as &$i) {
                $i['create_time'] = friendlyDate($i['create_time']);
                $i['update_time'] = friendlyDate($i['update_time']);
            }
        }
        unset($v);
        $list = array('list' => $IssueModules);

        $this->apiSuccess('返回成功', $list);
    }


    //返回某个板块的专辑列表
    public function getIssueList()
    {
        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');
        $aIssueId = I('issue_id', 0, 'intval');
        $map['status'] = 1;
        $aIssueId && $map['issue_id'] = $aIssueId;
        $width = I('width', 160, 'intval');
        $height = I('height', 210, 'intval');


        $IssueList = D('Issue/IssueContent')->where($map)->page($aPage, $aCount)->order('create_time desc')->select();
        $arr = array();

        foreach ($IssueList as &$v) {
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v['content'], $arr); //匹配所有的图片
            $v['imgList'] = $arr[1];
            $v['content'] = op_t($v['content']);
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128', 'signature'), $v['uid']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover_id'], $width, $height);
            $v['create_time'] = friendlyDate($v['create_time']);
            $v['update_time'] = friendlyDate($v['update_time']);
            $v['issue_title'] = D('Issue/Issue')->where(array('id' => $v['issue_id'], 'status' => 1))->select();
            $v['support_count'] = D('support')->where(array('appname' => 'Issue', 'table' => 'issue_content', 'row' => $v['id'],))->count();

            if (empty($v['support_count'])) {
                $v['is_supported'] = '0';

            } else {
                $v['is_supported'] = '1';
            }

            foreach ($v['issue_title'] as &$i) {
                $v['Modules_id'] = D('Issue/Issue')->where(array('id' => $i['pid'], 'status' => 1))->field('id')->select();


            }
            unset($i);
            //回复列表
            $v['Comments'] = D('Issue/LocalComment')->where(array('app'=>'issue','mod'=>'issueContent','row_id' =>$v['id'] ,'status'=>1))->page($aPage, $aCount)->select();
            foreach ($v['Comments'] as &$c) {
                $c['create_time'] = friendlyDate($c['create_time']);
            }
        }
        unset($v);

        $list = array('list' => $IssueList);
        $this->apiSuccess('返回成功', $list);
    }


// 返回某个专辑的详情列表
    public function getIssueDetail()
    {

        $aId = I('id', 0, 'intval');
        $map['status'] = 1;
        $width = I('width', 160, 'intval');
        $height = I('height', 210, 'intval');
        $aId && $map['id'] = $aId;
        $IssueDetail = D('Issue/IssueContent')->where($map)->select();


        foreach ($IssueDetail as &$v) {
            $v['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128', 'signature'), $v['uid']);
            $v['content'] = op_h($v['content']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover_id'], $width, $height);
            $v['issue'] = D('Issue/Issue')->where(array('id' => $v['issue_id']))->select();
            foreach ($v['issue'] as &$c) {
                $c['create_time'] = friendlyDate($c['create_time']);
                $c['update_time'] = friendlyDate($c['update_time']);
            }
            $v['support_count'] = D('support')->where(array('appname' => 'Issue', 'table' => 'issue_content', 'row' => $aId,))->count();

            if (empty($v['support_count'])) {
                $v['is_supported'] = '0';

            } else {
                $v['is_supported'] = '1';
            }
            $v['create_time'] = friendlyDate($v['create_time']);
            $v['update_time'] = friendlyDate($v['update_time']);
            if (is_login() == $v['uid']) {
                $v['is_author'] = '1';
            } else {
                $v['is_author'] = '0';
            }


        }
        unset($v);
        $list = array('list' => $IssueDetail);
        $this->apiSuccess('返回成功', $list);
    }


// 返回某个专辑的评论列表
    public function getIssueComments()
    {

        $aPage = I('page', 1, 'intval');
        $aCount = I('count', 10, 'intval');


        $aRowId = I('row_id', 0, 'intval');


        if (!D('Issue/IssueContent')->where(array('id' => $aRowId))->find()) {
            exit ($this->apiError('专辑不存在'));
        }
        $uid = D('Issue/IssueContent')->where(array('id' => $aRowId))->field('uid')->select();
        $uid = array_column($uid, 'uid');
        $arr = array();
        $IssueComments = D('Issue/LocalComment')->where(array('app'=>'issue','mod'=>'issueContent','row_id' => $aRowId,'status'=>1))->page($aPage, $aCount)->order('create_time desc')->select();
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

// 给专辑回复
    public function sendIssueComment()
    {


        $aRowId = I('row_id', 0, 'intval');
        $aContent = I('content', '', 'op_t');
        $aApp = 'Issue';
        $aMod = 'issueContent';

        if (!D('Issue/IssueContent')->where(array('id' => $aRowId))->find()) {
            exit ($this->apiError('专辑不存在'));
        }


        $data = array('uid' => is_login(), 'row_id' => $aRowId, 'parse' => 0, 'mod' => $aMod, 'app' => $aApp, 'content' => $aContent, 'status' => '1', 'create_time' => time());

        $data = D('Issue/LocalComment')->create($data);
        if (!$data) return false;
        $result = D('Issue/LocalComment')->add($data);

        D('Issue/IssueContent')->where(array('status' => 1, 'id' => $aRowId))->setInc('reply_count');

        $reply = D('Issue/LocalComment')->where(array('id' => $result))->find();

        preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $reply['content'], $arr); //匹配所有的图片
        $reply['imgList'] = $arr[1];
        $reply['content'] = op_t($reply['content']);
        $reply ['user'] = query_user(array('uid', 'username', 'nickname', 'avatar128'),$reply ['uid']);


        $this->apiSuccess('返回成功', $reply);
    }


//给专辑点赞
    public function supportIssue()
    {
        $this->requireLogin();
        $appname = 'Issue';
        $table = 'issue_content';
        $aRow = I('id');
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


//专辑投稿

    public function sendIssue()
    {
        $this->requireLogin();
        $aIssue_id = I('issue_id', '', 'intval');
        $aId = I('id', 0, 'intval');
        $aCover_id = I('cover_id', '', 'intval');
        $aTitle = I('title', '', 'op_t');
        $aUrl = I('url', '', 'op_h');
        $aContent = I('content', '', 'op_h');
        $attach_id = I('attach_id', '', 'op_t');
        $attach_ids = explode(',', $attach_id);


        if (!$aCover_id) {
            $this->apiError('请上传封面。');
        }
        if ($aTitle == '') {
            $this->apiError('请输入标题。');
        }
        if ($aIssue_id == 0) {
            $this->apiError('请选择分类。');
        }
        if ($aContent == '') {
            $this->apiError('请输入内容。');
        }
        if ($aUrl == '') {
            $this->apiError('请输入网址。');
        }
        foreach ($attach_ids as $k => $v) {
            $aContent .= "<p><img src='" . get_cover($v, 'path') . "'/></p>";
        }
        unset($v);

        $aContent = str_replace("\\", '', $aContent);
        $isEdit = $aId ? true : false;
        $this->requireIssueAllowPublish($aIssue_id);

        if ($isEdit) {
            $data = array('id' => intval($aId), 'title' => $aTitle, 'content' => $aContent, 'parse' => 0, 'cover_id' => intval($aCover_id), 'issue_id' => intval($aIssue_id), 'url' => $aUrl);
            $result = D('Issue/IssueContent')->where(array('id' => $aId))->save($data);

            if (!$result) {
                $this->apiError('编辑失败：' . $this->getError());
            }
        } else {
            $data = array('uid' => is_login(), 'title' => $aTitle, 'content' => $aContent, 'parse' => 0, 'cover_id' => intval($aCover_id), 'issue_id' => intval($aIssue_id), 'url' => $aUrl);

          /*  $before = getMyScore();
            $tox_money_before = getMyToxMoney();*/

            $data = D('Issue/IssueContent')->create($data);
            if (!$data) return false;
            $result = D('Issue/IssueContent')->add($data);
/*
            $after = getMyScore();
            $tox_money_after = getMyToxMoney();*/
            if (!$result) {
                $this->apiError('发表失败：' . $this->getError());
            }
            $aId = $result;

        }

        //显示成功消息
        /*$message = $isEdit ? '编辑成功。' : '发表成功。' . getScoreTip($before, $after) . getToxMoneyTip($tox_money_before, $tox_money_after);*/


        //返回成功消息
        $row = D('Issue/IssueContent')->where('id=' . $aId)->find();

        $this->apiSuccess($row);
    }


    /*——————————————————————私有函数————————————————————————————*/

    private function clearCache($support)
    {
        unset($support['uid']);
        unset($support['create_time']);
        $cache_key = "support_count_" . implode('_', $support);
        S($cache_key, null);
    }

//验证是否允许登陆 板块拥有权限 用户组是否拥有权限
    private function requireIssueAllowPublish($aIssue_id)
    {
        $this->requireIssueExists($aIssue_id);
        $this->requireLogin();
        $this->requireIssueAllowCurrentUserGroup($aIssue_id);
    }

    private function requireIssueExists($aIssue_id)
    {
        if (!$this->isIssueExists($aIssue_id)) {
            $this->apiError('专辑不存在');
        }
    }

    private function isIssueExists($aIssue_id)
    {

        $issue = D('Issue/Issue')->where(array('id' => $aIssue_id, 'status' => 1));
        return $issue ? true : false;
    }

    private function requireIssueAllowCurrentUserGroup($aIssue_id)
    {

        if (!$this->isIssueAllowCurrentUserGroup($aIssue_id)) {
            $this->apiError('该板块不允许发帖');

        }
    }

    private function isIssueAllowCurrentUserGroup($aIssue_id)
    {

        //如果是超级管理员，直接允许
        if (is_login() == 1) {
            return true;
        }

        //如果专辑不属于任何板块，则允许发帖
        if (intval($aIssue_id) == 0) {
            return true;
        }

        //读取专辑的基本信息
        /* $issue = D('Issue/Issue')->where(array('id' => $aIssue_id))->find();
         $userGroups = explode(',', $issue['allow_post']);
         dump($userGroups);*/

        //读取所在的用户组
        $list = M('AuthGroupAccess')->where(array('uid' => is_login()))->select();
        foreach ($list as &$e) {
            $e = $e['group_id'];
        }

        //判断用户组是否有权限
        /*  $list = array_intersect($list, $userGroups);  */
        return $list ? true : false;

    }

    //返回模型的错误信息
    private function getError()
    {
        return $this->error;
    }
}

