<?php
/**
 *
 */
namespace Weibo\Controller;


use Think\Controller;

class ShareController extends  Controller{

    public function shareBox(){
        $query = I('get.query','','text');
        parse_str($query,$array);
        $this->assign('query',$query);
        $this->assign('parse_array',$array);
        $this->display(T('Weibo@default/Widget/share/sharebox'));
    }

    public function doSendShare(){
        $aContent = I('post.content','','text');
        $aQuery = I('post.query','','text');
        parse_str($aQuery,$feed_data);

        if(empty($aContent)){
            $this->error('内容不能为空');
        }

        $new_id = send_weibo($aContent, 'share', $feed_data,$feed_data['from']);

        $user = query_user(array('nickname'), is_login());
        $info =  D('Weibo/Share')->getInfo($feed_data);
        $toUid = $info['uid'];
        D('Common/Message')->sendMessage($toUid, $user['nickname'] . '分享了您的内容！', '分享提醒', U('Weibo/Index/weiboDetail', array('id' => $new_id)), is_login(), 1);


        $result['url'] ='';
        //返回成功结果
        $result['status'] = 1;
        $result['info'] = '分享成功！' . cookie('score_tip');;
        $this->ajaxReturn($result);

    }
}