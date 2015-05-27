<?php

namespace Cat\Controller;

use Think\Controller;

class CenterController extends BaseController
{
    public function _initialize()
    {

        $map['uid'] = is_login();
        $map['status']=1;
        $count['posted'] = D('cat_info')->where($map)->count();

        $count['fav'] = D('cat_fav')->where($map)->count();
        $this->assign('count', $count);
        if (!is_login()) {
            $this->error('请登录后使用个人中心。');
        }
        parent::_initialize();
    }

    public function doSendInfo()
    {
        $this->checkAuth('Cat/Center/doSendInfo',-1,'你没有发送消息的权限！');
        $this->checkActionLimit('cat_center_send_info','cat_center');
        $send = D('cat_send')->create();

        $recStr = I('post.receiver', 'op_t');

        $array = explode(' ', str_replace('@', '', $recStr));
        $array = array_unique($array);
        $send['send_uid'] = is_login();
        $send['create_time'] = time();

        $send['content'] = I('post.content', '', 'op_t');
        $rs = 1;
        foreach ($array as $v) {
            if ($array != '') {
                $v = trim($v);
                $v = str_replace("\r", '', $v);
                $user = D('member')->where(array('nickname' => $v))->find();
                if ($user) {
                    $t_send = $send;
                    $t_send['rec_uid'] = $user['uid'];
                    $rs = $rs && D('cat_send')->add($t_send);
                }

            }

        }
        if ($rs) {
            action_log('cat_center_send_info','cat_center');
            $this->success('发送成功。');
        } else {
            $this->error('发送失败。');
        }

    }

    public function my()
    {
        $this->setTitle('个人中心');

        $entitys = D('cat_entity')->where('show_nav = 1 and status=1')->order('sort desc')->select();
        $map['entity_id'] = $entitys[0]['id'];
        //获取该用户发布的全部用户组
        $map['uid'] = is_login();
        if (I('get.id', 0, 'intval') != 0) {
            $map['entity_id'] = I('get.id', 0, 'intval');
        }


        $map['status'] = 1;
        $my_post = D('cat_info')->where($map)->findPage(10);
        //dump($my_post);exit;
        foreach ($entitys as $key => $v) {
            if (!CheckCanReadEntity(is_login(), $v['id'])) {
                unset($entitys[$key]);
            }
        }
        $data['entitys'] = $entitys;

        //$tpl_html =W('DefaultLiTpl',array('infos'=>$infos,'class'=>$this->_class,'type'=>$this->_type),true);

        foreach ($my_post['data'] as $key => $info) {
            $my_post['data'][$key]['tpl'] = D('Render')->renderInfo($info['id']);
        }

        $data['my_post'] = $my_post;
        $this->assign('current_entity', $map['entity_id']);
        $this->assign($data);
        $this->display();

    }

    public function rec()
    {
        $this->setTitle('收到的信息');
        //从缓存中获取
        $map['rec_uid'] = is_login();
        $rec = D('Send')->getList($map);
        $this->assign('rec', $rec);
        $this->display();
    }

    public function send()
    {
        $this->setTitle('发送的信息');
        //从缓存中获取
        if (empty($rec)) {
            $map['send_uid'] = is_login();
            $rec = D('Send')->getList($map);
        }
        $this->assign('rec', $rec);
        $this->display();
    }

    public function post()
    {
        $this->checkAuth('Cat/Center/doSendInfo',-1,'你没有发送消息的权限！');
        $this->setTitle('发送信息');
        $entitys = D('CatEntity')->where('status=1')->order('sort desc')->select();
        $first_infos = D('cat_info')->where('entity_id =' . $entitys[0]['id'] . ' and uid=' . is_login() . ' and status=1')->select();
        $this->assign('first_infos', $first_infos);
        $this->assign('entitys', $entitys);
        $this->display();
    }

    public function get_infos()
    {
        $map['entity_id'] = I('get.entity_id', 0, 'intval');
        $map['uid'] = is_login();
        $map['status'] = 1;
        $infos = D('cat_info')->where($map)->select();
        $this->assign('infos', $infos);
        $this->display();
    }

    public function doGetBack()
    {
        $map['id'] = I('post.send_id', 0, 'intval');
        $map['send_uid'] = is_login();
        $rs = D('cat_send')->where($map)->delete();
        if ($rs) {
            exit(json_encode(array('status' => 1)));
        } else {
            exit(json_encode(array('status' => 0)));
        }
    }

    public function doRead()
    {
        $map['id'] = I('post.send_id', 0, 'intval');
        D('cat_send')->where($map)->setField('readed', 1);
    }

    public function fav()
    {
        $this->setTitle('个人中心');

        $entitys = D('cat_entity')->where('show_nav = 1' . ' and status=1')->order('sort desc')->select();
        // dump($entitys);exit;
        $map['entity_id'] = $entitys[0]['id'];
        //获取该用户发布的全部用户组
        $t_map['uid'] = is_login();
        $fav = D('cat_fav')->where($t_map)->limit(999)->select();
        $fav_ids = getSubByKey($fav, 'info_id');
        $map['id'] = array('in', implode(',', $fav_ids));
        if (I('get.id', 0, 'intval') != 0) {
            $map['entity_id'] = I('get.id', 0, 'intval');
        }
        $map['status'] = 1;
        if ($fav_ids) {

            $my_post = D('cat_info')->where($map)->findPage(10);

            foreach ($entitys as $key => $v) {
                if (!CheckCanReadEntity(is_login(), $v['id'])) {
                    unset($entitys[$key]);
                }
            }

            foreach ($my_post['data'] as $key => $info) {
                $my_post['data'][$key]['tpl'] = D('Render')->renderInfo($info['id']);
            }

            $data['my_post'] = $my_post;
        } else {
            $data['my_post']['count'] = 0;
            $data['my_post']['totalPages'] = 0;
        }


        $data['entitys'] = $entitys;

        if (I('get.id', 0, 'intval') == 0) {
            foreach ($entitys as $v) {
                $current_entity = $v['id'];
                break;
            }

        } else {
            $current_entity = I('get.id', 0, 'intval');
        }
        $this->assign('current_entity', $current_entity);
        $this->assign($data);
        $this->display();

    }
}