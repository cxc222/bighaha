<?php
/**
 * Created by PhpStorm.
 * User: zff
 * Date: 15-4-20
 * Time: PM5:41
 */

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;
use Think\Model;

class AtlasController extends AdminController
{
    protected $atlasModel;

    function _initialize()
    {
        parent::_initialize();
        $this->meta_title = '图集管理';
        $this->atlasModel = D('Atlas/Atlas');
    }
    
    public function index($page = 1, $r = 10)
    {
        //读取列表
        $map = array('status' => 1);
        $model = $this->atlasModel;
        $list = $model->where($map)->page($page, $r)->select();
        unset($li);
        $totalCount = $model->where($map)->count();

        //显示页面
        $builder = new AdminListBuilder();

        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';

        $builder->title('内容管理')
        	->buttonNew(U('Atlas/editAtlas'))
            ->setStatusUrl(U('setEventContentStatus'))
            ->buttonDisable('', '审核不通过')
            ->buttonDelete()
            ->button('设为推荐', array_merge($attr, array('url' => U('doRecommend', array('tip' => 1)))))
            ->button('取消推荐', array_merge($attr, array('url' => U('doRecommend', array('tip' => 0)))))
            ->keyId()->keyLink('content', '内容', 'Event/Index/detail?id=###')->keyUid()->keyCreateTime('addtime')->keyStatus()->keyMap('is_recommend', '是否推荐', array(0 => '否', 1 => '是'))
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }
    
    /**
     * 审核页面
     * @param int $page
     * @param int $r
     * autor:xjw129xjt
     */
    public function verify($page = 1, $r = 10)
    {
        //读取列表
        $map = array('status' => 0);
        $model = $this->eventModel;
        $list = $model->where($map)->page($page, $r)->select();
        unset($li);
        $totalCount = $model->where($map)->count();

        //显示页面
        $builder = new AdminListBuilder();
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';
        $builder->title('审核内容')
            ->setStatusUrl(U('setEventContentStatus'))->buttonEnable('', '审核通过')->buttonDelete()
            ->keyId()->keyLink('title', '标题', 'Event/Index/detail?id=###')->keyUid()->keyCreateTime()->keyStatus()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    /**
     * 设置状态
     * @param $ids
     * @param $status
     * autor:xjw129xjt
     */
    public function setEventContentStatus($ids, $status)
    {
        $builder = new AdminListBuilder();
        if ($status == 1) {
            foreach ($ids as $id) {
                $content = D('Event')->find($id);
                D('Common/Message')->sendMessage($content['uid'], "管理员审核通过了您发布的内容。现在可以在列表看到该内容了。", $title = '专辑内容审核通知', U('Event/Index/detail', array('id' => $id)), is_login(), 2);
                /*同步微博*/
                $user = query_user(array('username', 'space_link'), $content['uid']);
                $weibo_content = '管理员审核通过了@' . $user['username'] . ' 的内容：【' . $content['title'] . '】，快去看看吧：' . "http://$_SERVER[HTTP_HOST]" . U('Event/Index/detail', array('id' => $content['id']));
                $model = D('Weibo/Weibo');
                $model->addWeibo(is_login(), $weibo_content);
                /*同步微博end*/
            }

        }
        $builder->doSetStatus('Event', $ids, $status);

    }
    
    /**
     * editAtlas  编辑图集
     * @param int $id
     * @param string $title
     * @param int $create_time
     * @param int $status
     * @param int $allow_user_group
     * @param int $logo
     * @param int $type_id
     * @param string $detail
     * @param int $type
     * @author:xjw129xjt xjt@ourstu.com
     */
    public function editAtlas($id = 0,$content=null,$image_id=0,$status=1)
    {
        if (!IS_POST) {
            //判断是否为编辑模式
            $isEdit = $id ? true : false;
            //如果是编辑模式，读取群组的属性
            if ($isEdit) {
                $atlas = $this->atlasModel->where(array('id' => $id))->find();
            } else {
                $atlas = array('addtime' => time(), 'status' => 1);
            }
            
            //显示页面
            $builder = new AdminConfigBuilder();
            $builder
                ->title($isEdit ? '编辑图集' : '新增图集')
                ->keyId()->keyTextArea('content', '图集介绍')
                ->keySingleImage('image_id', '图片', '图片大小不能大于3M,尺寸建议640像素*640像素')->keyCreateTime('addtime')
                ->keyStatus()
                ->data($atlas)
                ->buttonSubmit(U('editAtlas'))->buttonBack()
                ->display();
        } else { //判断是否为编辑模式
            $isEdit = $id ? true : false;
            //生成数据
            $data = array('content' => $content, 'addtime' => time(), 'status' => $status, 'image_id' => $image_id);
            //写入数据库
            $model = $this->atlasModel;
            if ($isEdit) {
                $data['id'] = $id;
                $data = $model->create($data);
                $result = $model->where(array('id' => $id))->save($data);
            } else {
                $data = $model->create($data);
                $data['uid']=1;
                $result = $model->add($data);
                if (!$result) {
                    $this->error('创建失败');
                }
            }
            S('atlas_list', null);
            //返回成功信息
            $this->success($isEdit ? '编辑成功' : '保存成功');
        }
    }
    
}
