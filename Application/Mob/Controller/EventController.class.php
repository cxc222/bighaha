<?php


namespace Mob\Controller;

use Think\Controller;

class EventController extends Controller{

    public function index()
    {

        $order = 'create_time desc';
        $event = D('Event')->order($order)->select();

        foreach ($event as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover_id']);
        //    $v['eTime']=friendlyDate($v['eTime']);
        }
        $this->assign('event',$event);
       // dump($event);exit;

        $order = 'create_time desc';
        $event = D('Event')->order($order)->select();

        foreach ($event as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover_id']);
        //    $v['eTime']=friendlyDate($v['eTime']);
        }
        $this->assign('event',$event);
       // dump($event);exit;

        $this->display();
    }
} 