<?php


namespace Mob\Controller;

use Think\Controller;


class WeiboController extends Controller
{

    /**
     * 主页面显示
     */
    public function index()
    {

        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $weibo = D('Weibo')->where(array('status' => 1,))->page($aPage, $aCount)->order('create_time desc')->select();


        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            $v['content']=parse_weibo_mobile_content($v['content']);

            if(empty( $v['from'])){
                $v['from']="网站端";
            }
            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourseId']) {                        //判断是否是源微博
                $v['sourseId'] = $v['data']['sourseId'];
                $v['is_sourseId'] = '1';
            } else {
                $v['sourseId'] = $v['id'];
                $v['is_sourseId'] = '0';

            }
            $v['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->find();           //源微博用户名
            $v['sourseId_user'] = $v['sourseId_user']['uid'];
            $v['sourseId_user'] = query_user(array('nickname'), $v['sourseId_user']);

            $v['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('content')->find();          //源微博内容
            $v['sourseId_content']=parse_weibo_mobile_content($v['sourseId_content']['content']);                                          //把表情显示出来。

            $v['sourseId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('repost_count')->find();    //源微博转发数

            $v['sourseId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('data')->find();    //为了获取源微图片
            $v['sourseId_img'] = unserialize($v['sourseId_img']['data']);
            $v['sourseId_img'] = explode(',', $v['sourseId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourseId_img'] as &$b) {
                $v['sourseId_img_path'][] = getThumbImageById($b);                      //获得缩略图
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourseId_img']['0'])) {
                $v['sourseId_is_img'] = '0';
            } else {
                $v['sourseId_is_img'] = '1';
            }

        }
        $pid['is_myfocus']=0;
//dump($weibo);exit;
        $this->assign("weibo", $weibo);
        $this->assign("pid", $pid);
        $this->display();

    }

    /**
     * 查看更多功能实现
     */

    public function addMoreWeibo()
    {

        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $weibo = D('Weibo')->where(array('status' => 1,))->page($aPage, $aCount)->order('create_time desc')->select();


        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            $v['content']=parse_weibo_mobile_content($v['content']);

            if(empty( $v['from'])){
                $v['from']="网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourseId']) {                        //判断是否是源微博
                $v['sourseId'] = $v['data']['sourseId'];
                $v['is_sourseId'] = '1';
            } else {
                $v['sourseId'] = $v['id'];
                $v['is_sourseId'] = '0';

            }
            $v['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->find();           //源微博用户名
            $v['sourseId_user'] = $v['sourseId_user']['uid'];
            $v['sourseId_user'] = query_user(array('nickname'), $v['sourseId_user']);


            $v['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('content')->find();          //源微博内容
            $v['sourseId_content']=parse_weibo_mobile_content($v['sourseId_content']['content']);

            $v['sourseId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('repost_count')->find();    //源微博转发数

            $v['sourseId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('data')->find();    //为了获取源微图片
            $v['sourseId_img'] = unserialize($v['sourseId_img']['data']);
            $v['sourseId_img'] = explode(',', $v['sourseId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourseId_img'] as &$b) {
                $v['sourseId_img_path'][] = getThumbImageById($b);
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourseId_img']['0'])) {
                $v['sourseId_is_img'] = '0';
            } else {
                $v['sourseId_is_img'] = '1';
            }

        }


        if ($weibo) {
            $data['html'] = "";
            foreach ($weibo as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibolist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);

    }

    /**
     * @param $id
     * 微博细节
     */

    public function weiboDetail($id)
    {
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 5, 'op_t');
        $map['id'] = array('eq', $id);
        $weibodetail = D('Weibo')->where($map)->find();
        $width = 100;
        $height = 100;

        $support['appname'] = 'Weibo';
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        if(empty($weibodetail['from'])){
            $weibodetail['from']="网站端";
        }

        $weibodetail['user'] = query_user(array('nickname', 'avatar64'), $weibodetail['uid']);
        $weibodetail['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $weibodetail['id']))->count();


        $weibodetail['data'] = unserialize($weibodetail['data']);              //字符串转换成数组,获取微博源ID
            if ($weibodetail['data']['sourseId']) {                        //
                $weibodetail['sourseId'] = $weibodetail['data']['sourseId'];
                $weibodetail['is_sourseId'] = '1';
            } else {
                $weibodetail['sourseId'] = $weibodetail['id'];
                $weibodetail['is_sourseId'] = '0';
            }

        $weibodetail['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourseId']))->find();           //源微博用户名

        $weibodetail['sourseId_user'] = $weibodetail['sourseId_user']['uid'];
        $weibodetail['sourseId_user'] = query_user(array('nickname'), $weibodetail['sourseId_user']);
        $weibodetail['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourseId']))->field('content')->find();          //源微博内容
        $weibodetail['sourseId_content']=parse_weibo_mobile_content($weibodetail['sourseId_content']['content']);
        $weibodetail['sourseId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourseId']))->field('repost_count')->find();    //源微博转发数

        $weibodetail['sourseId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourseId']))->field('data')->find();    //为了获取源微图片
        $weibodetail['sourseId_img'] = unserialize($weibodetail['sourseId_img']['data']);
        $weibodetail['sourseId_img'] = explode(',', $weibodetail['sourseId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($weibodetail['sourseId_img'] as &$b) {                                     //取得转发后源微博图片
                $weibodetail['sourseId_img_path'][] = getThumbImageById($b, $width, $height);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $weibodetail['sourseId_img_big'][] = $bi['path'];
                }else{
                    $weibodetail['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }

            }

        $weibodetail['cover_url'] = explode(',', $weibodetail['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($weibodetail['cover_url'] as &$a) {                                   //取得转发的微博的图片
                $weibodetail['img_path'][] = getThumbImageById($a, $width, $height);

            }

            if (empty($weibodetail['data']['attach_ids'])) {            //判断是否是图片
                $weibodetail['is_img'] = '0';
            } else {
                $weibodetail['is_img'] = '1';
            }

            if (in_array($weibodetail['id'], $is_zan)) {                         //判断是否已经点赞
                $weibodetail['is_support'] = '1';
            } else {
                $weibodetail['is_support'] = '0';
            }

            if (empty($weibodetail['sourseId_img']['0'])) {                     //判断源微博是否有图片
                $weibodetail['sourseId_is_img'] = '0';
            } else {
                $weibodetail['sourseId_is_img'] = '1';
            }



        $mapl['weibo_id'] = array('eq', $id);
        $weibocomment = D('Weibo_comment')->where(array('status' => 1, $mapl))->page($aPage, $aCount)->order('create_time desc')->select();
        foreach ($weibocomment as &$k) {
            $k['user'] = query_user(array('nickname', 'avatar32'), $k['uid']);
            $k['content']=parse_weibo_mobile_content($k['content']);
        }
       // dump($weibodetail);exit;
        $this->assign("weibodetail", $weibodetail);

        $this->assign('weibocomment', $weibocomment);                //微博评论

        $this->display();
    }

    /**
     * @param $id
     * 微博细节
     */

    public function addMoreComment()
    {

        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count',5, 'op_t');

        $aId = I('post.id', '', 'op_t');

        $map['weibo_id'] = array('eq', $aId);
        $weibocomment = D('WeiboComment')->where(array('status' => 1, $map))->page($aPage, $aCount)->order('create_time desc')->select();

        foreach ($weibocomment as &$k) {
            $k['user'] = query_user(array('nickname', 'avatar32'), $k['uid']);
            $k['content']=parse_weibo_mobile_content($k['content']);
        }

        if ($weibocomment) {
            $data['html'] = "";
            foreach ($weibocomment as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibocomment");

                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }

    /**
     * 发微博
     */
    public function doSend()
    {
        // dump(is_login());exit;
        $aContent = I('post.content', '', 'op_t');
        $aType = I('post.type', 'image', 'op_t');
        $aAttachIds = I('post.attach_ids', '', 'op_t');
      //  dump($aContent);exit;

        //权限判断
        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }

       
        if (!check_auth('Weibo/Index/doSend')) {
            $this->error('您无微博发布权限。');
        }
        if (empty($aContent)) {
            $this->error('发布内容不能为空。');
        }

        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }
        $feed_data = array();
        $feed_data['attach_ids'] = $aAttachIds;
        if (empty($aAttachIds)) {
            $aType = 'feed';
        }


        // 执行发布，写入数据库
        $weibo_id = send_weibo($aContent, $aType, $feed_data);

        if ($weibo_id) {
            $return['status'] = '1';
        } else {
            $return['status'] = ' 0';
            $return['info'] = '发布失败！';
        }
        $this->ajaxReturn($return);
    }

    /**
     * 我的关注
     */
    public function myFocus()
    {
        {

            $aPage = I('post.page', 0, 'op_t');
            $aCount = I('post.count', 10, 'op_t');
            $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
            $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
            $follow_who_ids=array_merge($follow_who_ids,array(is_login()));//加上自己的微博
            $map['uid'] = array('in', $follow_who_ids);
            $weibo = D('Weibo')->where(array('status' => 1, $map))->page($aPage, $aCount)->order('create_time desc')->select();//我关注的人的微博


            $support['appname'] = 'Weibo';                              //查找是否点赞
            $support['table'] = 'weibo';
            $support['uid'] = is_login();
            $is_zan = D('Support')->where($support)->select();
            $is_zan = array_column($is_zan, 'row');

            foreach ($weibo as &$v) {
                $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
                $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
                $v['content']=parse_weibo_mobile_content($v['content']);
                if(empty($v['from'])){
                    $v['from']="网站端";
                }

                $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
                if ($v['data']['sourseId']) {                        //判断是否是源微博
                    $v['sourseId'] = $v['data']['sourseId'];
                    $v['is_sourseId'] = '1';
                } else {
                    $v['sourseId'] = $v['id'];
                    $v['is_sourseId'] = '0';

                }
                $v['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->find();           //源微博用户名
                $v['sourseId_user'] = $v['sourseId_user']['uid'];
                $v['sourseId_user'] = query_user(array('nickname'), $v['sourseId_user']);

                $v['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('content')->find();          //源微博内容
                $v['sourseId_content']=parse_weibo_mobile_content($v['sourseId_content']['content']);                                          //把表情显示出来。

                $v['sourseId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('repost_count')->find();    //源微博转发数

                $v['sourseId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('data')->find();    //为了获取源微图片
                $v['sourseId_img'] = unserialize($v['sourseId_img']['data']);
                $v['sourseId_img'] = explode(',', $v['sourseId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
                foreach ($v['sourseId_img'] as &$b) {
                    $v['sourseId_img_path'][] = getThumbImageById($b);                      //获得缩略图
                    //获得原图
                    $bi = M('Picture')->where(array('status' => 1))->getById($b);
                    if(!is_bool(strpos( $bi['path'],'http://'))){
                        $v['sourseId_img_big'][] = $bi['path'];
                    }else{
                        $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                    }
                }


                $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
                foreach ($v['cover_url'] as &$a) {
                    $v['img_path'][] = getThumbImageById($a);
                    //获得原图
                    $bi = M('Picture')->where(array('status' => 1))->getById($b);
                    if(!is_bool(strpos( $bi['path'],'http://'))){
                        $v['sourseId_img_big'][] = $bi['path'];
                    }else{
                        $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                    }
                }

                if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                    $v['is_support'] = '1';
                } else {
                    $v['is_support'] = '0';
                }

                if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                    $v['is_img'] = '0';
                } else {
                    $v['is_img'] = '1';
                }
                if (empty($v['sourseId_img']['0'])) {
                    $v['sourseId_is_img'] = '0';
                } else {
                    $v['sourseId_is_img'] = '1';
                }

            }

            $pid['is_myfocus']=1;

            $this->assign("weibo", $weibo);
            $this->assign("pid", $pid);
            $this->display(T('Application://Mob@Weibo/index'));
        }
    }

    /**
     * 加载更多我的关注
     */

    public function addMoreMyFocus(){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count',10, 'op_t');
        $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
        $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
        $follow_who_ids=array_merge($follow_who_ids,array(is_login()));//加上自己的微博
        $map['uid'] = array('in', $follow_who_ids);
        $weibo = D('Weibo')->where(array('status' => 1, $map))->page($aPage, $aCount)->order('create_time desc')->select();


        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            $v['content']=parse_weibo_mobile_content($v['content']);
            if(empty( $v['from'])){
                $v['from']="网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourseId']) {                        //判断是否是源微博
                $v['sourseId'] = $v['data']['sourseId'];
                $v['is_sourseId'] = '1';
            } else {
                $v['sourseId'] = $v['id'];
                $v['is_sourseId'] = '0';

            }
            $v['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->find();           //源微博用户名
            $v['sourseId_user'] = $v['sourseId_user']['uid'];
            $v['sourseId_user'] = query_user(array('nickname'), $v['sourseId_user']);

            $v['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('content')->find();          //源微博内容
            $v['sourseId_content']=parse_weibo_mobile_content($v['sourseId_content']['content']);                                          //把表情显示出来。

            $v['sourseId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('repost_count')->find();    //源微博转发数

            $v['sourseId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('data')->find();    //为了获取源微图片
            $v['sourseId_img'] = unserialize($v['sourseId_img']['data']);
            $v['sourseId_img'] = explode(',', $v['sourseId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourseId_img'] as &$b) {
                $v['sourseId_img_path'][] = getThumbImageById($b);                      //获得缩略图
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourseId_img']['0'])) {
                $v['sourseId_is_img'] = '0';
            } else {
                $v['sourseId_is_img'] = '1';
            }

        }


        if ($weibo) {
            $data['html'] = "";
            foreach ($weibo as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibolist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);


    }

    /**
     * @param $id
     * @param $uid
     * 点赞
     */
    public function support($id, $uid)
    {
        //$id是发帖人的微博ID
        //$uid是发帖人的ID
        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }
        $row = $id;
        $message_uid = $uid;
        $support['appname'] = 'Weibo';
        $support['table'] = 'weibo';
        $support['row'] = $row;
        $support['uid'] = is_login();

        if (D('Support')->where($support)->count()) {
            $return['status'] = '0';
            $return['info'] = '亲，您已经支持过我了！';
        } else {
            $support['create_time'] = time();
            if (D('Support')->where($support)->add($support)) {

                $this->clearCache($support);

                $user = query_user(array('username'));

                D('Message')->sendMessage($message_uid, $user['username'] . '给您点了个赞。', $title = $user['username'] . '赞了您。', is_login());

                $return['status'] = '1';
            } else {
                $return['status'] = ' 0';
                $return['info'] = '亲，您已经支持过我了！';
            }


        }
        $this->ajaxReturn($return);
    }


    private function clearCache($support)
    {
        unset($support['uid']);
        unset($support['create_time']);
        $cache_key = "support_count_" . implode('_', $support);
        S($cache_key, null);
    }

    /**
     * @param $id
     * @param $uid
     * 转发内容获取展示
     */

    public function forward($id, $uid)
    {

        //$id是发帖人的微博ID
        //$uid是发帖人的ID

        $map['id'] = array('eq', $id);
        $weibo = D('Weibo')->where(array('status' => 1, $map))->order('create_time desc')->select();
        // dump($weibo);


        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            $v['data'] = unserialize($v['data']);              //字符串转换成数组
            if ($v['data']['sourseId']) {
                $v['sourseId'] = $v['data']['sourseId'];
                $v['is_sourseId'] = '1';
            } else {
                $v['sourseId'] = $v['id'];
                $v['is_sourseId'] = '0';
            }
            $v['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->find();           //源微博用户名
            $v['sourseId_user'] = $v['sourseId_user']['uid'];
            $v['sourseId_user'] = query_user(array('nickname'), $v['sourseId_user']);
            $v['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('content')->find();
        }


        $this->assign('weibo', $weibo[0]);
        $this->display(T('Application://Mob@Weibo/forward'));

    }

    /**
     * 转发功能实现
     */
    public function  doForward()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Mob/index/index'), 1);
        }

        $aContent = I('post.content', '', 'op_t');              //说点什么的内容
        $aType = I('post.type', '', 'op_t');                    //类型
        $aSoueseId = I('post.sourseId', 0, 'intval');           //获取该微博源ID
        $aWeiboId = I('post.weiboId', 0, 'intval');             //要转发的微博的ID
        $aBeComment = I('post.release', 'false', 'op_t');       //是否作为评论发布

        if (empty($aContent)) {
            $this->error('转发内容不能为空');
        }

        $this->checkAuth('Weibo/Index/doSendRepost', -1, '您无微博转发权限。');

        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }

        $weiboModel = D('Weibo');
        $feed_data = '';
        $sourse = $weiboModel->getWeiboDetail($aSoueseId);
        $sourseweibo = $sourse['weibo'];

        $feed_data['sourse'] = $sourseweibo;
        $feed_data['sourseId'] = $aSoueseId;

        $new_id = send_weibo($aContent, $aType, $feed_data);        //发布微博


        if ($new_id) {
            D('weibo')->where('id=' . $aSoueseId)->setInc('repost_count');
            $aWeiboId != $aSoueseId && D('weibo')->where('id=' . $aWeiboId)->setInc('repost_count');
            S('weibo_' . $aWeiboId, null);
            S('weibo_' . $aSoueseId, null);
        }
// 发送消息
        $user = query_user(array('nickname'), is_login());
        $toUid = D('weibo')->where(array('id' => $aWeiboId))->getField('uid');
        D('Common/Message')->sendMessage($toUid, $user['nickname'] . '转发了您的微博！', '转发提醒', U('Weibo/Index/weiboDetail', array('id' => $new_id)), is_login(), 1);

        // 发布评论

        if ($aBeComment == 'on') {
            send_comment($aWeiboId, $aContent);
        }


        //转发后的微博内容获取
        $weibo = D('Weibo')->where(array('status' => 1, 'id'=>$new_id))->order('create_time desc')->select();
        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            $v['content']=parse_weibo_mobile_content($v['content']);

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourseId']) {                        //判断是否是源微博
                $v['sourseId'] = $v['data']['sourseId'];
                $v['is_sourseId'] = '1';
            } else {
                $v['sourseId'] = $v['id'];
                $v['is_sourseId'] = '0';
            }
            $v['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->find();           //源微博用户名
            $v['sourseId_user'] = $v['sourseId_user']['uid'];
            $v['sourseId_user'] = query_user(array('nickname'), $v['sourseId_user']);

            $v['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('content')->find();          //源微博内容
            $v['sourseId_content']=parse_weibo_mobile_content($v['sourseId_content']['content']);                                          //把表情显示出来。

            $v['sourseId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('repost_count')->find();    //源微博转发数

            $v['sourseId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('data')->find();    //为了获取源微图片
            $v['sourseId_img'] = unserialize($v['sourseId_img']['data']);
            $v['sourseId_img'] = explode(',', $v['sourseId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourseId_img'] as &$b) {
                $v['sourseId_img_path'][] = getThumbImageById($b);                      //获得缩略图
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }

            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if(!is_bool(strpos( $bi['path'],'http://'))){
                    $v['sourseId_img_big'][] = $bi['path'];
                }else{
                    $v['sourseId_img_big'][] =getRootUrl(). substr( $bi['path'],1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourseId_img']['0'])) {
                $v['sourseId_is_img'] = '0';
            } else {
                $v['sourseId_is_img'] = '1';
            }

        }

        if ($weibo) {
            $data['html'] = "";
            foreach ($weibo as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibolist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
            $data['info'] = '转发失败！';
        }
        $this->ajaxReturn($data);

    }

    /**
     * @param $id
     * @param $user
     * 增加评论时显示的信息
     */
    public function addComment($id, $user)
    {
        //$id是发帖人的微博ID
        //$uid是发帖人的ID

        $map['id'] = array('eq', $id);
        $weibo = D('Weibo')->where(array('status' => 1, $map))->order('create_time desc')->select();


        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            $v['data'] = unserialize($v['data']);              //字符串转换成数组
            if ($v['data']['sourseId']) {
                $v['sourseId'] = $v['data']['sourseId'];
                $v['is_sourseId'] = '1';
            } else {
                $v['sourseId'] = $v['id'];
                $v['is_sourseId'] = '0';
            }
            $v['sourseId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->find();           //源微博用户名
            $v['sourseId_user'] = $v['sourseId_user']['uid'];
            $v['sourseId_user'] = query_user(array('nickname'), $v['sourseId_user']);
            $v['sourseId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourseId']))->field('content')->find();


            $v['at_user_id'] = $user;

        }
//dump($weibo);exit;
        $this->assign('weibo', $weibo[0]);
        $this->display(T('Application://Mob@Weibo/addcomment'));

    }

    /**
     * 增加评论实现
     */
    public function doAddComment()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Mob/index/index'), 1);
        }

        $aContent = I('post.weibocontent', '', 'op_t');              //说点什么的内容
        $aWeiboId = I('post.weiboId', 0, 'intval');             //要评论的微博的ID

        if (empty($aContent)) {
            $this->error('评论内容不能为空。');
        }

        $this->checkAuth('Weibo/Index/doComment', -1, '您无微博评论权限。');
        $return = check_action_limit('add_weibo_comment', 'weibo_comment', 0, is_login(), true);//行为限制
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }
        $new_id = send_comment($aWeiboId, $aContent);        //发布评论



        $weibocomment = D('WeiboComment')->where(array('status' => 1, 'id'=>$new_id))->order('create_time desc')->select();

        foreach ($weibocomment as &$k) {
            $k['user'] = query_user(array('nickname', 'avatar32'), $k['uid']);
            $k['content']=parse_weibo_mobile_content($k['content']);
        }

        if ($weibocomment) {
            $data['html'] = "";
            foreach ($weibocomment as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibocomment");

                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }


    public function delComment()
    {
        $comment_id = I('post.commentId', 0, 'intval');              //接收评论ID
        $weibo_id = I('post.weiboId', 0, 'intval');                   //接收微博ID

        $weibo_uid = D('Weibo')->where(array('status' => 1, 'id' => $weibo_id))->find();//根据微博ID查找微博发送人的UID
        $comment_uid = D('WeiboComment')->where(array('status' => 1, 'id' => $comment_id))->find();//根据评论ID查找评论发送人的UID

        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }


        if (is_administrator(get_uid()) || $weibo_uid['uid'] == get_uid() || $comment_uid['uid'] == get_uid()) {                                     //如果是管理员，则可以删除评论
            $result = D('WeiboComment')->deleteComment($comment_id);
        }
        if ($result) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['info'] = '删除失败';
        }
        $this->ajaxReturn($return);
    }


}