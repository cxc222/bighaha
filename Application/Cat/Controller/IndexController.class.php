<?php
namespace Cat\Controller;

use Think\Controller;
use Weibo\Api\WeiboApi;


class IndexController extends BaseController
{
    public function  _initialize()
    {
        parent::_initialize();

    }

    public function index()
    {
        $this->setTitle($this->APP_NAME);
        $map['show_index'] = '1';
        $map['status'] = 1;
        $entitys = D('cat_entity')->where($map)->order('sort desc')->select();

        $this->assign('entitys', $entitys);

        $this->display();
    }

    /**
     * 发布页面
     */
    public function post()
    {
        $info_id = I('get.info_id', 0, 'intval');
        $this->setTitle('发布信息');
        if (!(is_login())) {
            $this->error('请登陆后发布。');
        }
        /*得到实体信息*/
        if (I('get.entity_id', 0, 'intval') != 0) {
            $entity = D('cat_entity')->find(I('get.entity_id', 0, 'intval'));
        }

        if (I('get.name', '', 'op_t') != '') {
            $map['name'] = I('get.name', '', 'op_t');
            $entity = D('cat_entity')->where($map)->find();
        }
        $data['entity'] = $entity;
        /*得到实体信息end*/
        /*检查是否在可发布组内*/
        $can_post = CheckCanPostEntity(is_login(), $entity['id']);
        if (!$can_post) {
            $this->error('对不起，您无权发布。');
        }
        if ($info_id != 0) {
            $info = D('cat_info')->find($info_id);
            if (!$info) {
                $this->error('404不存在。');
            }
            $this->checkAuth('Cat/Index/editInfo',$info['uid'],'你没有编辑该条信息的权限！');
            $this->assign('info', $info);
        }else{
            $this->checkAuth('Cat/Index/addInfo',-1,'你没有发布信息的权限！');
        }
        /*检查是否在可发布组内end*/

        /*构建发布模板*/
        $tpl = '';

        $map_field['entity_id'] = $entity['id'];
        $map_field['status'] = 1;
        $fields = D('cat_field')->where($map_field)->order('sort desc')->select();
        if ($data['entity']['can_over']) {
            $over_time = array('input_type' => IT_DATE, 'can_empty' => 0, 'name' => 'over_time', 'tip' => '请输入截止日期', 'alias' => '截止日期', 'args' => 'min=1&error=请选择日期');
            $tpl .= R('InputRender/render', array(array('field' => $over_time, 'info_id' => $info_id, $tpl)), 'Widget');
        }


        foreach ($fields as $v) {
            $tpl .= R('InputRender/render', array(array('field' => $v, 'info_id' => $info_id)), 'Widget');
        }
        $data['tpl'] = $tpl;
        /*构建发布模板end*/

        $this->assign($data);

        $this->assign('entity_id', $entity['id']);
        $this->display();
    }

    /**
     * 执行添加信息
     */
    public function doAddInfo()
    {
        unset($_POST['__hash__']);
        $entity_id = I('post.entity_id', 0, 'intval');
        $info_id = I('post.info_id', 0, 'intval');
        $aOverTime = I('post.over_time', '', 'op_t');
        $entity = D('cat_entity')->find($entity_id);
        /**权限认证**/
        $can_post = CheckCanPostEntity(is_login(), $entity_id);
        if (!$can_post) {
            $this->error('对不起，您无权发布。');
        }
        /**权限认证end*/


        $info['title'] = I('post.title', '', 'op_t');
        if ($info['title'] == '') {
            $this->error('必须输入标题');

        }
        if (mb_strlen($info['title'], 'utf-8') > 40) {
            $this->error('标题过长。');
        }
        $info['create_time'] = time();

        if ($info_id != 0) {
            //保存逻辑
            $info = D('cat_info')->find($info_id);
            $this->checkAuth('Cat/Index/editInfo',$info['uid'],'你没有编辑该条信息的权限！');
            $this->checkActionLimit('cat_edit_info','cat_info',$info['id']);
            if ($aOverTime != '') {
                $info['over_time'] = strtotime($aOverTime);
            }

            $info['id'] = $info_id;
            $res=D('cat_info')->save($info);
            $rs_info = $info['id'];
            if($res){
                action_log('cat_edit_info','cat_info',$info['id']);
            }
        } else {
            $this->checkAuth('Cat/Index/addInfo',-1,'你没有发布信息的权限！');
            $this->checkActionLimit('cat_add_info','cat_info');
            //新增逻辑
            $info['entity_id'] = $entity_id;
            $info['uid'] = is_login();
            if ($entity['need_active'] && !is_administrator()) {
                $info['status'] = 2;
            } else {
                $info['status'] = 1;
            }
            if (isset($_POST['over_time'])) {
                $info['over_time'] = strtotime($_POST['over_time']);
            }

            $rs_info = D('cat_info')->add($info);
            if($rs_info){
                action_log('cat_add_info','cat_info');
            }
        }

        $rs_data = 1;

        if ($rs_info != 0) //如果info保存成功
        {

            if ($info_id != 0) {
                $map_data['info_id'] = $info_id;
                D('Data')->where($map_data)->delete();
            }

            $dataModel = D('Data');
            foreach ($_POST as $key => $v) {

                if ($key != 'entity_id' && $key != 'over_time' && $key != 'ignore' && $key != 'info_id' && $key != 'title' && $key != '__hash__' && $key != 'file') {
                    if (is_array($v)) {
                        $rs_data = $rs_data && $dataModel->addData($key, implode(',', $v), $rs_info, $entity_id);
                    } else {
                        $v = op_h($v);
                        $rs_data = $rs_data && $dataModel->addData($key, $v, $rs_info, $entity_id);
                    }
                }
                if ($rs_data == 0) {
                    $this->error($dataModel->getError());
                }
            }
            if ($rs_info && $rs_data) {
                $this->assign('jumpUrl', U('Cat/Index/info', array('info_id' => $rs_info)));

                if ($entity['need_active']) {
                    $this->success('发布成功。'.cookie('score_tip').' 请耐心等待管理员审核。通过审核后该信息将出现在前台页面中。');
                } else {
                    if ($entity['show_nav']) {
                        if(D('Common/Module')->isInstalled('Weibo')){//安装了微博模块
                            $postUrl = "http://$_SERVER[HTTP_HOST]" . U('cat/index/info', array('info_id' => $rs_info), null, true);
                            $weiboModel=D('Weibo/Weibo');
                            $weiboModel->addWeibo("我发布了一个新的 " . $entity['alias'] . "信息 【" . $info['title'] . "】：" . $postUrl);
                        }
                    }
                    $this->success('发布成功。'.cookie('score_tip'));
                }

            }
        } else {
            $this->error('发布失败。');
        }

    }

    /**
     * 详情页面
     */
    public function info()
    {

        /*检查是否在可阅读组内*/
        $can_post = CheckCanRead(is_login(), I('get.info_id', 0, 'intval'));
        if (!$can_post) {
            $this->assign('jumpUrl', U('cat/Index/index'));
            $this->error('对不起，您无权阅读。');
        }
        /*检查是否在可阅读组内end*/
        if (is_login()) {
            $map_read['uid'] = is_login();
            $map_read['info_id'] = I('get.info_id', 0, 'intval');

            $has_read = D('cat_read')->where($map_read)->count();
            if ($has_read) {
                D('cat_read')->where($map_read)->setField('cTime', time());
            } else {
                $map_read['cTime'] = time();
                D('cat_read')->add($map_read);
            }
        }

        /*得到实体信息*/
        $map['info_id'] = I('get.info_id', 0, 'intval');

        $read = D('cat_read')->where($map)->order('cTime desc')->limit(10)->select();
        foreach ($read as $key => $v) {
            $read[$key]['user'] = query_user(array('nickname', 'space_url', 'avatar64'), $v['uid']);
        }

        $info = D('cat_info')->find(I('get.info_id', 0, 'intval'));


        $this->setTitle('{$info.title|op_t}');
        $info['read']++;
        D('cat_info')->save($info);
        $entity = D('cat_entity')->find($info['entity_id']);
        $assign['info'] = $info;
        $assign['entity'] = $entity;
        //取出全部的字段数据
        $map_field['entity_id'] = $entity['id'];
        $map_field['status'] = 1;
        $fields = D('cat_field')->where($map_field)->order('sort desc')->select();
        //确定是否过期
        $now = time();
        if ($now > $info['over_time']) {
            $overed = '1';
            $assign['overed'] = 1;
        }
        //获取到信息的数据
        $info['data'] = D('Data')->getByInfoId($info['id']);
        /*得到实体信息end*/
        $tpl = '';
        /*构建自动生成模板*/
        $assign['fields'] = $fields;


        //$tpl = R('SysTagRender', array(array('tpl' => $tpl, 'info' => $info)), 'Widget');
        $assign['tpl'] = $tpl;
        $assign['info'] = $info;
        $info['reads'] = $read;
        if ($entity['use_detail'] == 0) {

            $detail = R('DefaultInfoTpl/render', array(array('fields' => $fields, 'info' => $info)), 'Widget');
        } else {
            /**默认模板添加**/
            $assign['entity'] = D('cat_entity')->find($info['entity_id']);
            $assign['data'] = D('Data')->getByInfoId($info['id']);
            $assign['user'] = query_user(array('nickname', 'spcae_url'), $info['uid']);
            $assign['info_id'] = $info['info_id'];
            //$assign['info']['com'] = D('Com')->getList($map, 5);
            $assign['mid'] = is_login();
            /**默认模板添加end**/
            $view = new \Think\View();
            $view->assign($assign);

            $detail = $view->fetch(T('Application://Cat@Tpls/' . $entity['use_detail']), '');
        }
        $assign['detail'] = $detail;

        $this->assign($assign);
        $this->display();
    }

    /**
     * 列表页面
     */
    public function li($entity_id = 0, $name = '')
    {
        $entity_id = I('get.entity_id', 0, 'intval');
        $name = I('get.name', '', 'op_t');
        if ($entity_id != 0) {
            $map['entity_id'] = $entity_id;
        }
        if ($name != '') {
            $map['name'] = $name;
        }


        $entity = D('cat_entity')->where($map)->find();
        $this->assign('current', 'category_' . $entity['id']);
        $this->setTitle('{$entity.alias}');
        $map_s_field['entity_id'] = $entity['id'];
        $map_s_field['can_search'] = '1';
        $map_s_field['status'] = 1;
        $search_fields = D('cat_field')->where($map_s_field)->order('sort desc')->select();
        foreach ($search_fields as $key => $v) {
            $search_fields[$key]['values'] = parseOption($v['option']);
        }
        $data['search_fields'] = $search_fields;
        $this->assign($data);
        $this->assign('entity', $entity);
        $this->display();
    }

    public function doFav()
    {
        $this->checkAuth('Cat/Index/doFav',-1,'你没有收藏分类信息的权限！');
        if (!D('Fav')->checkFav(is_login(), intval(I('post.id', 0, 'intval')))) {
            //未收藏，就收藏
            if (D('Fav')->doFav(is_login(), I('post.id', 0, 'intval'))) {
                $this->ajaxReturn((array('status' => 1)));
            };
        } else {
            //已收藏，就取消收藏
            if (D('Fav')->doDisFav(is_login(), I('post.id', 0, 'intval'))) {
                $this->ajaxReturn((array('status' => 2)));
            };

        }

        $this->ajaxReturn((array('status' => 0)));
    }

    /**
     * 支持ajax删除信息
     */
    public function delInfo()
    {

        $map['info_id'] = I('post.info_id', 0, 'intval'); // $_POST['info_id'];
        $info=D('cat_info')->find($map['info_id']);
        $this->checkAuth('Cat/Index/delInfo',$info['uid'],'你没有删除分类信息的权限！');

        $rs = D('cat_info')->where(array('id' => I('post.info_id', 0, 'intval')))->delete();
        if ($rs) {
            D('cat_data')->where($map)->delete();
            D('cat_com')->where($map)->delete();
        }

        if ($rs) {
            exit(json_encode(array('status' => 1)));
        } else {
            exit(json_encode(array('status' => 0)));
        }
    }


    public function doScore()
    {
        $this->checkAuth('Cat/Index/doScore',-1,'你没有打分的权限！');
        $info_id = I('post.info_id', 0, 'intval');
        $rate['info_id'] = $info_id;
        $info = D('cat_info')->find($info_id);
        if ($info['uid'] == is_login()) {
            $this->error('不能给自己打分。');
        }
        $rate['uid'] = is_login();
        $map = $rate;
        if (D('cat_rate')->where($map)->count()) {
            $this->error('已经打过分。');
        }

        if (I('post.score', 'floatval') > 5 || I('post.score', 'floatval') < 0) {
            $this->error('分数有误。');
        }
        $rate['score'] = I('post.score', 'floatval');
        $rate['create_time'] = time();
        $rs = D('cat_rate')->add($rate);


        if ($rs) {
            $map_rate['info_id'] = $info_id;
            $count = D('cat_rate')->where($map_rate)->Avg('score');
            $map_info['id'] = I('post.info_id', 0, 'intval');
            D('cat_info')->where($map_info)->setField('rate', $count);

            $this->success('打分成功。');
        } else {
            $this->error('打分失败。');
        }

    }

    /**
     * 获取自己用于发送的信息列表
     */
    public function get_s_infos()
    {
        $infos = D('Info')->getList('entity_id=' . I('get.entity_id') . ' and uid=' . is_login() . ' and status=1');

        $this->assign('info_id', I('get.info_id', 0, 'intval'));
        $this->assign('uid', I('get.uid', 0, 'intval'));
        $this->assign('send_infos', $infos);
        $this->display();
    }

    /**发送信息
     *
     */
    public function send_info()
    {
        $this->checkAuth('Cat/Center/doSendInfo',-1,'你没有发送信息的权限！');
        $send = D('cat_send')->create();
        $send['send_uid'] = is_login();
        $send['rec_uid'] = I('post.uid', 0, 'intval');

        $send['info_id'] = I('post.info_id', 0, 'intval');
        $send['s_info_id'] = I('post.s_info_id', 0, 'intval');
        $send['create_time'] = time();
        $rs = D('cat_send')->add($send);

        if ($rs) {
            exit(json_encode(array('status' => 1)));
        }
        exit(json_encode(array('status' => 0)));

    }

    /**
     * 动态css输出
     */
    public function css()
    {
        header('Content-type: text/css');
        echo modC('CSS');
    }

    /**
     * 动态JS输出
     */
    public function js()
    {
        header('Content-type: text/javascript');
        echo modC('JS');
    }

}