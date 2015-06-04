<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-5
 * Time: 上午11:15
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;

class QuestionController extends AdminController
{
    private $questionModel;
    private $questionAnswerModel;
    private $questionCategoryModel;
    private $questionSupportModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->questionModel = D('Question/Question');
        $this->questionAnswerModel = D('Question/QuestionAnswer');
        $this->questionCategoryModel = D('Question/QuestionCategory');
        $this->questionSupportModel = D('Question/QuestionSupport');
    }

    //分类管理
    public function category()
    {
        //显示页面
        $builder = new AdminTreeListBuilder();

        $tree = $this->questionCategoryModel->getTree(0, 'id,title,sort,pid,status');

        $builder->title('问题分类管理')
            ->suggest('禁用、删除分类时会将分类下的问题转移到默认分类下')
            ->buttonNew(U('Question/add'))
            ->data($tree)
            ->display();
    }

    /**分类添加
     * @param int $id
     * @param int $pid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function add($id = 0, $pid = 0)
    {
        $title = $id ? "编辑" : "新增";
        if (IS_POST) {
            if ($this->questionCategoryModel->editData()) {
                //S('SHOW_EDIT_BUTTON',null);
                $this->success($title . '分类成功。', U('Question/category'));
            } else {
                $this->error($title . '分类失败!' . $this->questionCategoryModel->getError());
            }
        } else {
            $builder = new AdminConfigBuilder();

            if ($id != 0) {
                $data = $this->questionCategoryModel->find($id);
            } else {
                $father_category_pid = $this->questionCategoryModel->where(array('id' => $pid))->getField('pid');
                if ($father_category_pid != 0) {
                    $this->error('分类不能超过二级！');
                }
            }
            if ($pid != 0) {
                $categorys = $this->questionCategoryModel->where(array('pid' => 0, 'status' => array('egt', 0)))->select();
            }
            $opt = array();
            foreach ($categorys as $category) {
                $opt[$category['id']] = $category['title'];
            }
            $builder->title($title . '分类')
                ->data($data)
                ->keyId()->keyText('title', '标题')
                ->keySelect('pid', '父分类', '选择父级分类', array('0' => '顶级分类') + $opt)->keyDefault('pid', $pid)
                ->keyInteger('sort', '排序')->keyDefault('sort', 0)
                ->keyStatus()->keyDefault('status', 1)
                ->buttonSubmit(U('Question/add'))->buttonBack()
                ->display();
        }

    }

    /**
     * 设置问题分类状态：删除=-1，禁用=0，启用=1
     * @param $ids
     * @param $status
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function setStatus($ids, $status)
    {
        !is_array($ids) && $ids = explode(',', $ids);
        if (in_array(1, $ids)) {
            $this->error('id为 1 的分类是问题基础分类，不能被禁用、删除！');
        }
        if ($status == 0 || $status == -1) {
            $map['category'] = array('in', $ids);
            $this->questionModel->where($map)->setField('category', 1);
        }
        $builder = new AdminListBuilder();
        $builder->doSetStatus('QuestionCategory', $ids, $status);
    }

    //分类管理 end

    public function config()
    {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();


        $builder->title('问答基础设置')
            ->data($data);

        $builder->keyRadio('QUESTION_NEED_AUDIT', '问题是否需要审核', '', array('0' => '否', '1' => '是'))->keyDefault('QUESTION_NEED_AUDIT', 1)

            ->keyText('QUESTION_SHOW_TITLE', '标题名称', '在首页展示块的标题')->keyDefault('QUESTION_SHOW_TITLE', '热门问题')
            ->keyText('QUESTION_SHOW_COUNT', '显示问题的个数', '只有在网站首页模块中启用了问题块之后才会显示')->keyDefault('QUESTION_SHOW_COUNT', 4)
            ->keyRadio('QUESTION_SHOW_TYPE', '问题的筛选范围', '', array('1' => '后台推荐', '0' => '全部'))->keyDefault('QUESTION_SHOW_TYPE',0)
            ->keyRadio('QUESTION_SHOW_ORDER_FIELD', '排序值', '展示模块的数据排序方式', array('answer_num' => '回答数', 'create_time' => '发表时间', 'update_time' => '更新时间'))->keyDefault('QUESTION_SHOW_ORDER_FIELD', 'answer_num')
            ->keyRadio('QUESTION_SHOW_ORDER_TYPE', '排序方式', '展示模块的数据排序方式', array('desc' => '倒序，从大到小', 'asc' => '正序，从小到大'))->keyDefault('QUESTION_SHOW_ORDER_TYPE', 'desc')
            ->keyText('QUESTION_SHOW_CACHE_TIME', '缓存时间', '默认600秒，以秒为单位')->keyDefault('QUESTION_SHOW_CACHE_TIME', '600')

            ->group('基本配置', 'QUESTION_NEED_AUDIT')->group('首页展示配置', 'QUESTION_SHOW_TITLE,QUESTION_SHOW_COUNT,QUESTION_SHOW_TYPE,QUESTION_SHOW_ORDER_FIELD,QUESTION_SHOW_ORDER_TYPE,QUESTION_SHOW_CACHE_TIME')


            ->buttonSubmit()->buttonBack()
            ->display();
    }

    public function index($page = 1, $r = 20)
    {
        $aStatus = I('get.status', 0, 'intval');
        if ($aStatus == 0) {
            $map['status'] = 1;
        } elseif ($aStatus == 1) {
            $map['status'] = 2;
        } else {
            $map['status'] = 0;
        }
        list($list, $totalCount) = $this->questionModel->getListPageByMap($map, $page, 'is_recommend desc,create_time desc', $r, '*');

        $builder = new AdminListBuilder();
        $builder->title('问题列表页');
        $builder->setSelectPostUrl(U('Question/index'))
            ->select('', 'status', 'select', '', '', '', array(array('id' => 0, 'value' => '当前问题（启用）'), array('id' => 1, 'value' => '未审核'), array('id' => 2, 'value' => '审核失败或已禁用')));
        $builder->setStatusUrl(U('Question/setQuestionStatus'));
        if ($aStatus == 1) {
            $builder->buttonEnable(null, '审核通过')->buttonDisable(null, '审核失败')->buttonDelete(null, '直接删除');
        } elseif ($aStatus == 2) {
            $builder->buttonEnable(null, '启用或审核通过')->buttonDelete();
        } else {
            $builder->buttonEnable()->buttonDisable()->buttonDelete()->ajaxButton(U('Question/recommend'),array('recommend'=>1),'设为推荐')->ajaxButton(U('Question/recommend'),array('recommend'=>0),'取消推荐');
        }
        $builder->keyId()->keyLink('title','标题','Question/Index/detail?id=###')->keyUid()->keyText('category', '分类')->keyBool('is_recommend','是否为推荐')->keyCreateTime()->keyUpdateTime()->keyStatus();
        $builder->pagination($totalCount, $r)->data($list)->display();
    }

    public function recommend($ids,$recommend=1)
    {
        !is_array($ids)&&$ids=explode(',',$ids);
        $map['id']=array('in',$ids);
        $res=$this->questionModel->where($map)->setField('is_recommend',$recommend);
        if($res){
            if($recommend==1){
                $messageModel=D('Message');
                foreach($ids as $val){
                    /**
                     * @param $to_uid 接受消息的用户ID
                     * @param string $content 内容
                     * @param string $title 标题，默认为  您有新的消息
                     * @param $url 链接地址，不提供则默认进入消息中心
                     * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
                     * @param int $type 消息类型，0系统，1用户，2应用
                     */
                    $question=$this->questionModel->find($val);
                    $messageModel->sendMessage($question['uid'], '你的问题【'.$question['title'].'】被管理员设为推荐！', '问题被推荐', U('Question/Index/detail',array('id'=>$val)), is_login(), 0);
                }
            }
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！');
        }
    }

    public function setQuestionStatus($ids, $status = 1)
    {
        !is_array($ids) && $ids = explode(',', $ids);
        $builder = new AdminListBuilder();
        if($status==0||$status==-1){
            $map['question_id']=array('in',$ids);
            $this->questionAnswerModel->where($map)->setField('status',$status);
        }
        $messageModel=D('Message');
        if($status==1){
            foreach($ids as $val){
                /**
                 * @param $to_uid 接受消息的用户ID
                 * @param string $content 内容
                 * @param string $title 标题，默认为  您有新的消息
                 * @param $url 链接地址，不提供则默认进入消息中心
                 * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
                 * @param int $type 消息类型，0系统，1用户，2应用
                 */
                $question=$this->questionModel->find($val);
                if($question['status']==2){
                    $messageModel->sendMessage($question['uid'], '你的问题【'.$question['title'].'】通过了审核！！', '问题被审核', U('Question/Index/detail',array('id'=>$val)), is_login(), 2);
                }
            }
        }else if($status==0){
            foreach($ids as $val){
                /**
                 * @param $to_uid 接受消息的用户ID
                 * @param string $content 内容
                 * @param string $title 标题，默认为  您有新的消息
                 * @param $url 链接地址，不提供则默认进入消息中心
                 * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
                 * @param int $type 消息类型，0系统，1用户，2应用
                 */
                $question=$this->questionModel->find($val);
                if($question['status']==2){
                    $messageModel->sendMessage($question['uid'], '你的问题【'.$question['title'].'】没有通过审核！！', '问题审核失败', U('Question/Index/detail',array('id'=>$val)), is_login(), 2);
                }
            }
        }else{
            foreach($ids as $val){
                /**
                 * @param $to_uid 接受消息的用户ID
                 * @param string $content 内容
                 * @param string $title 标题，默认为  您有新的消息
                 * @param $url 链接地址，不提供则默认进入消息中心
                 * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
                 * @param int $type 消息类型，0系统，1用户，2应用
                 */
                $question=$this->questionModel->find($val);
                if($question['status']==2){
                    $messageModel->sendMessage($question['uid'], '你的问题【'.$question['title'].'】被管理员直接删除！！', '问题审核失败', U('Question/Index/myQuestion'), is_login(), 2);
                }
            }
        }
        $builder->doSetStatus('Question', $ids, $status);
    }

    public function answer($page=1,$r=20)
    {
        list($list,$totalCount)=$this->questionAnswerModel->getSimpleListPage(array('status'=>array('egt',0)),$page,'create_time desc',$r);
        foreach($list as &$val){
            $question=$this->questionModel->getData($val['question_id']);
            $val['question']='<a target="_black" href="'.U('Question/Index/detail',array('id'=>$val['question_id'])).'">'.$question['title'].'</a>';
            $val['content']=msubstr(text($val['content']),0,100);
            if($question['best_answer']==$val['id']){
                $val['best_answer']=1;
            }else{
                $val['best_answer']=0;
            }
        }
        $builder=new AdminListBuilder();
        $builder->title('回答列表')
            ->setStatusUrl(U('Question/setAnswerStatus'))
            ->buttonEnable()->buttonDisable()->buttonDelete()
            ->keyId()
            ->keyUid()
            ->keyText('question','问题')
            ->keyText('content','回答内容')
            ->keyBool('best_answer','是否是最佳答案')
            ->keyStatus()
            ->keyUpdateTime()
            ->keyCreateTime()
            ->pagination($totalCount,$r)
            ->data($list)
            ->display();
    }

    public function setAnswerStatus($ids,$status=1)
    {
        !is_array($ids)&&$ids=explode(',',$ids);
        $builder=new AdminListBuilder();
        if($status==0||$status==-1){
            $map['best_answer']=array('in',$ids);
            $best_ids=$this->questionModel->getList($map,'best_answer');
            if(count($best_ids)){
                $best_ids=array_column($best_ids,'best_answer');
                $best_ids=implode(',',$best_ids);
                $this->error("id 为 {$best_ids} 的答案是问题的最佳答案，不能被禁用或删除！");
            }
        }
        $builder->doSetStatus('QuestionAnswer',$ids,$status);
    }
} 