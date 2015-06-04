<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM5:41
 */

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;

class AppstoreController extends AdminController
{
    protected $mAdminListBuilder;

    protected $mGoodsModel;

    public function _initialize()
    {
        $this->mAdminListBuilder;
        $this->mGoodsModel = D('AppstoreGoods');
    }

    public function index($page = 1, $r = 20, $content = '')
    {
        redirect(U('verify'));
    }

    public function goods($page = 1, $r = 20)
    {
        //TODO 支持搜索
        $this->mAdminListBuilder = new AdminListBuilder();
        $goods = $this->mGoodsModel->where('status > -1')->page($page, $r)->select();


        $this->mAdminListBuilder->title('商品审核')
            ->buttonSetStatus(U('Appstore/setStatus'), 2, '设为待审核', array())
            ->search('搜索', 'title', null, '支持标题')
            ->keyLink('title', '名称', 'appstore/edit?id=###')->keyUid()->keyCreateTime()->keyUpdateTime()
            ->data($goods)->display();
    }

    /**审核商品
     * @param int $page
     * @param int $r
     * @auth 陈一枭
     */
    public function verify($page = 1, $r = 20)
    {
        //TODO 支持搜索
        $this->mAdminListBuilder = new AdminListBuilder();
        $goods = $this->mGoodsModel->where(array('status' => 2))->page($page, $r)->select();


        $this->mAdminListBuilder->title('商品审核')
            ->buttonSetStatus(U('Appstore/setStatus'), 1, '审核通过', array())
            ->search('搜索', 'title', null, '支持标题')
            ->keyLink('title', '名称', 'appstore/edit?id=###')->keyUid()->keyCreateTime()->keyUpdateTime()
            ->data($goods)->display();
    }

    public function type($page = 1, $r = 20)
    {
        $entity = I('entity', 0, 'intval');
        if ($entity != 0) {
            $map['entity'] = $entity;
        }

        $builder = new AdminListBuilder();
        $builder->title('商品分类管理');
        $list = D('Appstore/AppstoreType')->getList($map, $r, 'id desc');

        foreach ($list['data'] as &$v) {
            switch ($v['entity']) {
                case 1:
                    $v['entity_alias'] = '插件';
                    break;
                case 2:
                    $v['entity_alias'] = '模块';
                    break;
                case 3:
                    $v['entity_alias'] = '模板';
                    break;
                case 4:
                    $v['entity_alias'] = '服务';
                    break;
            }
        }
        unset($v);
        $builder->keyId()->keyLink('title', '分类名称', 'addType?id=###')->keyText('entity_alias', '分类所属');
        $builder->setSelectPostUrl(U('type'))->select('', 'entity', 'select', '', '', '', array(array('id' => 0, 'value' => '全部'), array('id' => 1, 'value' => '插件'), array('id' => 2, 'value' => '模块'), array('id' => 3, 'value' => '主题'), array('id' => 4, 'value' => '服务')));;
        $builder->buttonNew(U('addType', array('entity' => $entity)), '新增');
        $builder->data($list[data]);

        $builder->display();
    }

    public function addType()
    {
        if (IS_POST) {
            $aId = I('post.id', 0, 'intval');
            $aTitle = I('title', '', 'text');
            $aEntity = I('entity', 1, 'intval');
            $aSort = I('sort', 0, 'intval');
            $aTitle = $aTitle == '' ? $this->error('分类名称必填') : $aTitle;
            $data['title'] = $aTitle;
            $data['sort'] = $aSort;
            $data['entity'] = $aEntity;
            $data['status'] = 1;
            if ($aId != 0) {
                //存储
                $data['id'] = $aId;
                $rs = M('AppstoreType')->save($data);
            } else {
                $rs = M('AppstoreType')->add($data);
            }
            if ($rs === false) {

                $this->error('保存失败。');
            } else {
                $this->success('保存成功。');
            }

        } else {
            $id = I('id', 0, 'intval');
            if ($id != 0) {
                $data = M('AppstoreType')->find($id);
            }else{
                $entity = I('entity', 1, 'intval');

                if ($entity == 0) {
                    $entity = 1;
                }
                $data['entity'] = $entity;
                $data['status'] = 1;
                $data['sort'] = 0;
            }

            $builder = new AdminConfigBuilder();

            $builder->title('新增分类');
            $builder->keyId();
            $builder->keyTitle();
            $builder->keyRadio('entity', '分类所属', '', array('1' => '插件', 2 => '模块', '3' => '主题', 4 => '服务'))
                ->keyInteger('sort', '排序')
                ->keyStatus();
            $builder->data($data)->buttonSubmit();

            $builder->display();
        }


    }

    /**
     * @auth 陈一枭
     */
    public function trash($page = 1, $r = 20)
    {
        $this->mAdminListBuilder = new AdminListBuilder();
        $goods = $this->mGoodsModel->where(array('status' => -1))->page($page, $r)->select();


        $this->mAdminListBuilder->title('商品审核')
            ->buttonSetStatus(U('Appstore/setStatus'), 1, '审核通过', array())
            ->search('搜索', 'title', null, '支持标题')
            ->keyLink('title', '名称', 'appstore/edit?id=###')->keyUid()->keyCreateTime()->keyUpdateTime()
            ->data($goods)->display();
    }


    /**设置商品状态，用于审核通过
     * @param int $ids id，表单自动获取
     * @param int $status 默认为正常状态1
     * @auth 陈一枭
     */
    public function setStatus($ids = 0, $status = 1)
    {
        if ($ids == 0) {
            $this->error('请选择商品');
        }
        $appstoreModel = D('AppstoreGoods');
        $weiboModel = D('Weibo/weibo');
        $map['id'] = array('in', implode(',', $ids));
        $rs = $this->mGoodsModel->where($map)->setField('status', $status);
        if ($status == 1) {
            foreach ($ids as $id) {
                $goods = $appstoreModel->find($id);
                $user = query_user(array('nickname'), $goods['uid']);
                $weibo_content = '管理员审核通过了@' . $user['nickname'] . ' 的商品：【' . op_t($goods['title']) . '】，快去看看吧：' . "http://$_SERVER[HTTP_HOST]" . U('appstore/index/goodsDetail', array('id' => $goods['id']));
                $weiboModel->addWeibo(is_login(), $weibo_content, 'feed', null, '云平台');
            }
        }

        $this->success('成功设置' . $rs . '件商品的状态。');
    }


}
