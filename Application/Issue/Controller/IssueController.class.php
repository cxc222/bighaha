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
use Admin\Builder\AdminTreeListBuilder;


class IssueController extends AdminController
{
    protected $issueModel;

    function _initialize()
    {
        $this->issueModel = D('Issue/Issue');
        parent::_initialize();
    }

    public function config()
    {
        $admin_config = new AdminConfigBuilder();
        $data = $admin_config->handleConfig();
        $data['NEED_VERIFY'] = $data['NEED_VERIFY'] ? $data['NEED_VERIFY'] : 0;
        $data['DISPLAY_TYPE'] = $data['DISPLAY_TYPE'] ? $data['DISPLAY_TYPE'] : 'list';
        $data['ISSUE_SHOW_TITLE'] = $data['ISSUE_SHOW_TITLE'] ? $data['ISSUE_SHOW_TITLE'] : '最热专辑';
        $data['ISSUE_SHOW_COUNT'] = $data['ISSUE_SHOW_COUNT'] ? $data['ISSUE_SHOW_COUNT'] : 4;
        $data['ISSUE_SHOW_ORDER_FIELD'] = $data['ISSUE_SHOW_ORDER_FIELD'] ? $data['ISSUE_SHOW_ORDER_FIELD'] : 'view_count';
        $data['ISSUE_SHOW_ORDER_TYPE'] = $data['ISSUE_SHOW_ORDER_TYPE'] ? $data['ISSUE_SHOW_ORDER_TYPE'] : 'desc';
        $data['ISSUE_SHOW_CACHE_TIME'] = $data['ISSUE_SHOW_CACHE_TIME'] ? $data['ISSUE_SHOW_CACHE_TIME'] : '600';
        $admin_config->title('专辑基本设置')
            ->keyBool('NEED_VERIFY', '投稿是否需要审核', '默认无需审核')
            ->keyRadio('DISPLAY_TYPE', '默认展示形式', '前台列表默认以该形式展示',array('list'=>'列表','masonry'=>'瀑布流'))
            ->buttonSubmit('', '保存')->data($data);
        $admin_config->keyText('ISSUE_SHOW_TITLE', '标题名称', '在首页展示块的标题');
        $admin_config->keyText('ISSUE_SHOW_COUNT', '显示专辑的个数', '只有在网站首页模块中启用了专辑块之后才会显示');
        $admin_config->keyRadio('ISSUE_SHOW_ORDER_FIELD', '排序值', '展示模块的数据排序方式', array('view_count' => '阅读数', 'reply_count' => '回复数', 'create_time' => '发表时间', 'update_time' => '更新时间'));
        $admin_config->keyRadio('ISSUE_SHOW_ORDER_TYPE', '排序方式', '展示模块的数据排序方式', array('desc' => '倒序，从大到小', 'asc' => '正序，从小到大'));
        $admin_config->keyText('ISSUE_SHOW_CACHE_TIME', '缓存时间', '默认600秒，以秒为单位');
        $admin_config->group('基本配置', 'NEED_VERIFY,DISPLAY_TYPE')->group('首页展示配置', 'ISSUE_SHOW_COUNT,ISSUE_SHOW_TITLE,ISSUE_SHOW_ORDER_TYPE,ISSUE_SHOW_ORDER_FIELD,ISSUE_SHOW_CACHE_TIME');

        $admin_config->groupLocalComment('本地评论配置','issueContent');



        $admin_config->display();
    }

    public function issue()
    {
        //显示页面
        $builder = new AdminTreeListBuilder();
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';
        $attr1 = $attr;
        $attr1['url'] = $builder->addUrlParam(U('setWeiboTop'), array('top' => 1));
        $attr0 = $attr;
        $attr0['url'] = $builder->addUrlParam(U('setWeiboTop'), array('top' => 0));
        $tree = D('Issue/Issue')->getTree(0, 'id,title,sort,pid,status');
        $builder->title('专辑管理')
            ->buttonNew(U('Issue/add'))
            ->data($tree)
            ->display();
    }

    public function add($id = 0, $pid = 0)
    {
        if (IS_POST) {
            if ($id != 0) {
                $issue = $this->issueModel->create();
                if ($this->issueModel->save($issue)) {
                    $this->success('编辑成功。');
                } else {
                    $this->error('编辑失败。');
                }
            } else {
                $issue = $this->issueModel->create();
                if ($this->issueModel->add($issue)) {

                    $this->success('新增成功。');
                } else {
                    $this->error('新增失败。');
                }
            }


        } else {
            $builder = new AdminConfigBuilder();
            $issues = $this->issueModel->select();
            $opt = array();
            foreach ($issues as $issue) {
                $opt[$issue['id']] = $issue['title'];
            }
            if ($id != 0) {
                $issue = $this->issueModel->find($id);
            } else {
                $issue = array('pid' => $pid, 'status' => 1);
            }


            $builder->title('新增分类')->keyId()->keyText('title', '标题')->keySelect('pid', '父分类', '选择父级分类', array('0' => '顶级分类') + $opt)
                ->keyStatus()->keyCreateTime()->keyUpdateTime()
                ->data($issue)
                ->buttonSubmit(U('Issue/add'))->buttonBack()->display();
        }

    }

    public function issueTrash($page = 1, $r = 20, $model = '')
    {
        $builder = new AdminListBuilder();
        $builder->clearTrash($model);
        //读取微博列表
        $map = array('status' => -1);
        $model = $this->issueModel;
        $list = $model->where($map)->page($page, $r)->select();
        $totalCount = $model->where($map)->count();

        //显示页面

        $builder->title('专辑回收站')
            ->setStatusUrl(U('setStatus'))->buttonRestore()->buttonClear('Issue/Issue')
            ->keyId()->keyText('title', '标题')->keyStatus()->keyCreateTime()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function operate($type = 'move', $from = 0)
    {
        $builder = new AdminConfigBuilder();
        $from = D('Issue')->find($from);

        $opt = array();
        $issues = $this->issueModel->select();
        foreach ($issues as $issue) {
            $opt[$issue['id']] = $issue['title'];
        }
        if ($type === 'move') {

            $builder->title('移动分类')->keyId()->keySelect('pid', '父分类', '选择父分类', $opt)->buttonSubmit(U('Issue/add'))->buttonBack()->data($from)->display();
        } else {

            $builder->title('合并分类')->keyId()->keySelect('toid', '合并至的分类', '选择合并至的分类', $opt)->buttonSubmit(U('Issue/doMerge'))->buttonBack()->data($from)->display();
        }

    }

    public function doMerge($id, $toid)
    {
        $effect_count = D('IssueContent')->where(array('issue_id' => $id))->setField('issue_id', $toid);
        D('Issue')->where(array('id' => $id))->setField('status', -1);
        $this->success('合并分类成功。共影响了' . $effect_count . '个内容。', U('issue'));
        //TODO 实现合并功能 issue
    }

    public function contents($page = 1, $r = 10)
    {
        //读取列表
        $map = array('status' => 1);
        $model = M('IssueContent');
        $list = $model->where($map)->page($page, $r)->select();
        unset($li);
        $totalCount = $model->where($map)->count();

        //显示页面
        $builder = new AdminListBuilder();
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';


        $builder->title('内容管理')
            ->setStatusUrl(U('setIssueContentStatus'))->buttonDisable('', '审核不通过')->buttonDelete()
            ->keyId()->keyLink('title', '标题', 'Issue/Index/issueContentDetail?id=###')->keyUid()->keyCreateTime()->keyStatus()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function verify($page = 1, $r = 10)
    {
        //读取列表
        $map = array('status' => 0);
        $model = M('IssueContent');
        $list = $model->where($map)->page($page, $r)->select();
        unset($li);
        $totalCount = $model->where($map)->count();

        //显示页面
        $builder = new AdminListBuilder();
        $attr['class'] = 'btn ajax-post';
        $attr['target-form'] = 'ids';


        $builder->title('审核内容')
            ->setStatusUrl(U('setIssueContentStatus'))->buttonEnable('', '审核通过')->buttonDelete()
            ->keyId()->keyLink('title', '标题', 'Issue/Index/issueContentDetail?id=###')->keyUid()->keyCreateTime()->keyStatus()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function setIssueContentStatus()
    {
        $ids = I('ids');
        $status = I('get.status', 0, 'intval');
        $builder = new AdminListBuilder();
        if ($status == 1) {
            foreach ($ids as $id) {
                $content = D('IssueContent')->find($id);
                D('Common/Message')->sendMessage($content['uid'],$title = '专辑内容审核通知', "管理员审核通过了您发布的内容。现在可以在列表看到该内容了。",  'Issue/Index/issueContentDetail', array('id' => $id), is_login(), 2);
                /*同步微博*/
                /*  $user = query_user(array('nickname', 'space_link'), $content['uid']);
                  $weibo_content = '管理员审核通过了@' . $user['nickname'] . ' 的内容：【' . $content['title'] . '】，快去看看吧：' ."http://$_SERVER[HTTP_HOST]" .U('Issue/Index/issueContentDetail',array('id'=>$content['id']));
                  $model = D('Weibo/Weibo');
                  $model->addWeibo(is_login(), $weibo_content);*/
                /*同步微博end*/
            }

        }
        $builder->doSetStatus('IssueContent', $ids, $status);

    }

    public function contentTrash($page = 1, $r = 10, $model = '')
    {
        //读取微博列表
        $builder = new AdminListBuilder();
        $builder->clearTrash($model);
        $map = array('status' => -1);
        $model = D('IssueContent');
        $list = $model->where($map)->page($page, $r)->select();
        $totalCount = $model->where($map)->count();

        //显示页面

        $builder->title('内容回收站')
            ->setStatusUrl(U('setIssueContentStatus'))->buttonRestore()->buttonClear('IssueContent')
            ->keyId()->keyLink('title', '标题', 'Issue/Index/issueContentDetail?id=###')->keyUid()->keyCreateTime()->keyStatus()
            ->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }
}
