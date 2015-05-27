<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-5
 * Time: 下午1:52
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Question\Controller;

class IndexController extends BaseController{


    public function index()
    {
        redirect(is_login() ? U('Question/Index/waitAnswer') : U('Question/Index/goodQuestion'));
    }

    public function questions($page=1,$r=20)
    {
        $quesionCategory = $this->questionCategoryModel->getQuestionCategoryList();
        $this->assign($quesionCategory);

        $aCategory=I('get.category',0,'intval');
        if($aCategory){
            $map_cate=$this->questionCategoryModel->getCategoryList(array('pid'=>$aCategory));
            $map_cate=array_column($map_cate,'id');
            $map['category']=array('in',array_merge(array($aCategory),$map_cate));
            $this->assign('question_cate',$aCategory);
        }
        $map['status']=1;
        list($list,$totalCount)=$this->_getList($map,$page,$r);
        $this->assign('list',$list);
        $this->assign('totalCount',$totalCount);
        $this->assign('current','questions');
        $this->display();
    }

    public function myQuestion($page=1,$r=20)
    {
        $this->_needLogin();
        $aType=I('get.type','q','text');//类型 q:question,a:answer
        if($aType=='q'){
            $map['status']=array('egt',0);
            $map['uid']=get_uid();
            list($list,$totalCount)=$this->_getList($map,$page,$r);
            $this->assign('type','q');
        }else{
            list($list,$totalCount)=$this->questionAnswerModel->getMyListPage(0,$page,'support desc,create_time desc',$r,'*');
            $user=query_user(array('uid','nickname','space_url'));
            $this->assign('user',$user);
            $this->assign('type','a');
        }

        $this->assign('list',$list);
        $this->assign('totalCount',$totalCount);
        $this->assign('current','myQuestion');
        $this->display();
    }

    public function waitAnswer($page=1,$r=20)
    {
        $map['status']=1;
        $map['update_time']=array('gt',get_time_ago('month',1));
        $map['best_answer']=0;
        list($list,$totalCount)=$this->questionModel->getListPageByMap($map,$page,'create_time desc',$r,'*');
        foreach($list as &$val){
            $val['info']=msubstr(op_t($val['description']),0,200);
            $val['img']=get_pic($val['description']);
            $val['user']=query_user(array('uid','space_url','nickname'),$val['uid']);
        }
        unset($val);
        $this->assign('list',$list);
        $this->assign('totalCount',$totalCount);
        $this->assign('current','waitAnswer');
        $this->display();
    }

    public function goodQuestion($page=1,$r=20)
    {
        $map['status']=1;
        list($list,$totalCount)=$this->_getList($map,$page,$r,'answer_num desc');
        $this->assign('list',$list);
        $this->assign('totalCount',$totalCount);
        $this->assign('current','goodQuestion');
        $this->display();
    }

    public function search($page=1,$r=20)
    {
        $aKeywords=I('keywords','','text');
        $_GET['keywords']=$_GET['keywords']?:$_POST['keywords'];
        $map['status']=1;
        $map['title']=array('like','%'.$aKeywords.'%');
        list($list,$totalCount)=$this->_getList($map,$page,$r,'answer_num desc');
        $this->assign('list',$list);
        $this->assign('totalCount',$totalCount);
        $this->assign('search_keywords',$aKeywords);
        $this->display();
    }
    public function detail($page=1,$r=10)
    {
        $aId=I('id',0,'intval');
        $data=$this->questionModel->getData($aId);
        if(!$data||$data['status']==-1){
            $this->error('该问题不存在或已被删除！');
        }else{
            if($data['status']==2){
                $data['audit_info']='<span style="color: #D79F39;">待审核</span>';
            }elseif($data['status']==0){
                $data['audit_info']='<span style="color: #A6A6A6;">审核失败或被禁用！<a href="'.U('question/index/edit',array('id'=>$aId)).'">编辑问题</a> 重新审核</span>';
            }else{
                $data['audit_info']="";
            }
        }
        if($data['best_answer']){
            $best_answer=$this->questionAnswerModel->getData(array('id'=>$data['best_answer'],'status'=>1));
            $this->assign('best_answer',$best_answer);
            $this->_getAnswer($aId,$page,$r,array('id'=>array('neq',$data['best_answer'])));
        }else{
            $this->_getAnswer($aId,$page,$r);
        }

        $this->assign('question',$data);
        $this->display();
    }

    public function edit()
    {
        $this->_needLogin();
        if(IS_POST){
            $this->_doEdit();
        }else{
            $aId=I('id',0,'intval');
            $title=$aId?"编辑问题":"提问题";

            if($aId){
                $data=$this->questionModel->getData($aId);
                $this->checkAuth('Question/Index/edit',$data['uid'],'没有编辑该问题权限！');
                $need_audit=modC('QUESTION_NEED_AUDIT',1,'Question');
                if($need_audit){
                    $data['status']=2;
                }
            }else{
                $data['title']=I('title','','text');
                $this->checkAuth('Question/Index/add',-1,'没有发布问题的权限！');
            }
            $this->assign('data',$data);

            $category=$this->questionCategoryModel->getCategoryList(array('status'=>1),1);
            $this->assign('category',$category);
            $this->assign('edit_title',$title);
            $this->assign('current','create');
            $this->display();
        }
    }

    private function _doEdit()
    {
        $aId=I('post.id',0,'intval');
        $need_audit=modC('QUESTION_NEED_AUDIT',1,'Question');
        if($aId){
            $data['id']=$aId;
            $now_data=$this->questionModel->getData($aId);
            $this->checkAuth('Question/Index/edit',$now_data['uid'],'没有编辑该问题权限！');
            if($need_audit){
                $data['status']=2;
            }
            $this->checkActionLimit('edit_question','question',$now_data['id'],get_uid());
        }else{
            $this->checkAuth('Question/Index/add',-1,'没有发布问题的权限！');
            $this->checkActionLimit('add_question','question',0,get_uid());
            $data['uid']=get_uid();
            $data['answer_num']=$data['good_question']=0;
            if($need_audit){
                $data['status']=2;
            }else{
                $data['status']=1;
            }
        }
        $data['title']=I('post.title','','text');
        $data['category']=I('post.category',0,'intval');
        $data['description']=I('post.description','','html');

        if(!mb_strlen($data['title'],'utf-8')){
            $this->error('标题不能为空！');
        }

        $res=$this->questionModel->editData($data);
        $title=$aId?"编辑":"提";
        if($res){
            if(!$aId){
                $aId=$res;
                if($need_audit){
                    $this->success($title.'问题成功！'.cookie('score_tip').' 请等待审核~',U('Question/Index/detail',array('id'=>$aId)));
                }
            }
            if(D('Common/Module')->isInstalled('Weibo')){//安装了微博模块
                //同步到微博
                $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Question/Index/detail',array('id'=>$aId));
                $weiboModel=D('Weibo/Weibo');
                $weiboModel->addWeibo("我问了一个问题【" . $data['title'] . "】：" . $postUrl);
            }
            $this->success($title.'问题成功！'.cookie('score_tip'),U('Question/Index/detail',array('id'=>$aId)));
        }else{
            $this->error($title.'问题失败！'.$this->questionModel->getError());
        }
    }

    private function _getAnswer($question_id,$page=1,$r=10,$map=array())
    {
        $map['question_id']=$question_id;
        $map['status']=1;
        list($list,$totalCount)=$this->questionAnswerModel->getListByMapPage($map,$page,'support desc,create_time desc',$r,$field='*');
        $this->assign('list',$list);
        $this->assign('totalCount',$totalCount);
        return true;
    }

    private function _getList($map,$page=1,$r=20,$order='create_time desc')
    {
        list($list,$totalCount)=$this->questionModel->getListPageByMap($map,$page,$order,$r,'*');
        foreach($list as &$val){
            $val['info']=msubstr(op_t($val['description']),0,200);
            $val['img']=get_pic($val['description']);
            $val['user']=query_user(array('uid','space_url','nickname'),$val['uid']);
            if($val['best_answer']){
                $val['best_answer_info']=$this->questionAnswerModel->getData(array('id'=>$val['best_answer'],'status'=>1));
            }else{
                $val['best_answer_info']=$this->questionAnswerModel->getData(array('question_id'=>$val['id'],'status'=>1),'support desc');
            }
            if($val['best_answer_info']){
                $val['best_answer_info']['content']=msubstr(op_t($val['best_answer_info']['content']),0,200);
            }
        }
        return array($list,$totalCount);
    }
} 