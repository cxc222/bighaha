<?php


namespace Mob\Controller;

use Think\Controller;


class GroupController extends Controller
{
    /**
     * 群组首页
     */
    public function index()
    {

        $Group= D('Group')->where(array('status' => 1,))->order('create_time desc')->select();
        foreach($Group as &$v){
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['logo']=getThumbImageByCoverId($v['logo'],200,200);
            if(is_login()==$v['uid']||is_administrator(get_uid()) ){
                $v['is_login']=1;
            }else{
                $v['is_login']=0;
            }
        }

          //dump($Group);exit;
        $this->assign('group',$Group);
        $this->display();
    }

    /**
     * 热门群组
     */
    public function hotGroup(){

        $Group= D('Group')->where(array('status' => 1,))->order('member_count desc,post_count desc')->limit(4)->select();
        foreach($Group as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['logo'] = getThumbImageByCoverId($v['logo'], 200, 200);
            if (is_login() == $v['uid'] || is_administrator(get_uid())) {
                $v['is_login'] = 1;
            } else {
                $v['is_login'] = 0;
            }
        }
    //    dump($Group);exit;
        $this->assign('group',$Group);
        $this->display(T('Application://Mob@group/index'));
    }

    /**
     * 我的群组
     */
    public function myGroup(){
        $Group= D('Group')->where(array('status' => 1,'uid'=>is_login()))->order('create_time desc,member_count desc')->select();
        foreach($Group as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['logo'] = getThumbImageByCoverId($v['logo'], 200, 200);
            if (is_login() == $v['uid'] || is_administrator(get_uid())) {
                $v['is_login'] = 1;
            } else {
                $v['is_login'] = 0;
            }
        }
        //    dump($Group);exit;
        $this->assign('group',$Group);
        $this->display(T('Application://Mob@group/index'));
    }

    /**
     * @param $id
     * 群组ID
     *渲染编辑群组页面内容
     */
    public function groupAdmin($id){
        $editGroup= D('Group')->where(array('status' => 1,'id'=>$id))->find();
        $editGroup['logo_id'] = getThumbImageByCoverId($editGroup['logo']);
        $editGroup['background_id'] = getThumbImageByCoverId($editGroup['background']);

        $groupType = $this->assignGroupTypes();//分类信息内容
        foreach ($groupType['parent'] as $k => $v) {
            $child = $groupType['child'][$v['id']];
            //获取数组中第一父级的位置
            $key_name = array_search($v, $groupType['parent']);
            foreach ($child as $key => $val) {
                $val['title'] = '------' . $val['title'];
                //在父级后面添加数组
                array_splice($groupType['parent'], $key_name + 1, 0, array($val));
            }
        }
        $this->assign('groupTypeAll', $groupType['parent']);
      //  dump($editGroup);exit;
        $this->assign('editGroup',$editGroup);
        $this->display();
    }
    protected function assignGroupTypes()
    {
        $groupType = D('Group/GroupType')->getGroupTypes();
        $this->assign($groupType);
        return $groupType;
    }

    public function createGroup(){

            $aGroupId = I('post.group_id', 0, 'intval');
            $aGroupType = I('post.group_type', 0, 'intval');
            $aTitle = I('post.title', '', 'text');
            $aDetail = I('post.detail', '', 'text');
            $aLogo = I('post.logo', 0, 'intval');
            $aType = I('post.type', 0, 'intval');
            $aBackground = I('post.background', 0, 'intval');
            $aMemberAlias = I('post.member_alias', '成员', 'text');


            if (empty($aTitle)) {
                $this->error('请填写群组名称');
            }
            if (utf8_strlen($aTitle) > 20) {
                $this->error('群组名称最多20个字');
            }
            if ($aGroupType == -1) {
                $this->error('请选择群组分类');
            }
            if (empty($aDetail)) {
                $this->error('请填写群组介绍');
            }
            $isEdit = $aGroupId ? true : false;
            if ($isEdit) {
                $this->requireLogin();


                $this->requireGroupExists($aGroupId);

                $this->checkActionLimit('edit_group', 'Group', $aGroupId, is_login(), true);

                $this->checkAuth('Group/Index/editGroup', get_group_admin($aGroupId), '您无编辑群组权限');

            } else {
                $this->checkActionLimit('add_group', 'Group', 0, is_login(), true);
                $this->checkAuth('Group/Index/addGroup', -1, '您无添加群组权限');
            }

            $need_verify = modC('GROUP_NEED_VERIFY', 0, 'GROUP');
            $model = D('Group/Group');
            if ($isEdit) {

                $data = array('id' => $aGroupId, 'type_id' => $aGroupType, 'title' => $aTitle, 'detail' => $aDetail, 'logo' => $aLogo, 'type' => $aType, 'background' => $aBackground, 'member_alias' => $aMemberAlias);
                $data['status'] = $need_verify ? 0 : 1;
                $result = $model->editGroup($data);
                $group_id = $aGroupId;

            } else {

                $data = array('type_id' => $aGroupType, 'title' => $aTitle, 'detail' => $aDetail, 'logo' => $aLogo, 'type' => $aType, 'uid' => is_login(), 'background' => $aBackground, 'member_alias' => $aMemberAlias);
                $data['status'] = $need_verify ? 0 : 1;
                $result = $model->createGroup($data);
                if (!$result) {
                    $this->error('创建群组失败：' . $model->getError());
                }
                $group_id = $result;
                //向GroupMember表添加创建者成员
                D('GroupMember')->addMember(array('uid' => is_login(), 'group_id' => $group_id, 'status' => 1, 'position' => 3));
            }
            if ($need_verify) {

                $message = '创建成功，请耐心等候管理员审核。';
                // 发送消息
                D('Message')->sendMessage(1, get_nickname(is_login()) . "创建了群组【{$aTitle}】，快去审核吧。", '群组创建审核', U('admin/group/unverify'), is_login());
                $this->success($message, U('group/index/index'));
            }

            // 发送微博
            if (D('Module')->checkInstalled('Weibo')) {

                $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Group/Index/group', array('id' => $group_id));
                if ($isEdit && check_is_in_config('edit_group', modC('GROUP_SEND_WEIBO', 'add_group,edit_group', 'GROUP'))) {
                    D('Weibo')->addWeibo(is_login(), "我修改了群组【" . $aTitle . "】：" . $postUrl);
                }
                if (!$isEdit && check_is_in_config('add_group', modC('GROUP_SEND_WEIBO', 'add_group,edit_group', 'GROUP'))) {
                    D('Weibo')->addWeibo(is_login(), "我创建了一个新的群组【" . $aTitle . "】：" . $postUrl);
                }

            }

            //显示成功消息
            $message = $isEdit ? '编辑成功。' : '发表成功。';
            $url = $isEdit ? 'refresh' : U('group/index/group', array('id' => $group_id));
            $this->success($message, $url);
        }

    protected  function requireLogin()
    {
        if (!is_login()) {
            $this->error('需要登录才能操作');
        }
    }

    protected  function requireGroupExists($group_id)
    {
        if (!group_is_exist($group_id)) {
            $this->error('群组不存在');
        }
    }

    /**
     * 帖子分类管理内容渲染
     */




}