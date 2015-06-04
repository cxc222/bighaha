<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-6
 * Time: 下午5:23
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Question\Controller;


class AnswerController extends BaseController{

    public function edit()
    {
        $this->_needLogin();
        if(IS_POST){
            $this->_doEdit();
        }else{
            $aAnswerId=I('get.answer_id',0,'intval');
            $answer=$this->questionAnswerModel->getData(array('id'=>$aAnswerId,'status'=>1));
            if(!$answer){
                $this->error('非法操作！');
            }
            $this->checkAuth('Question/Index/edit',$answer['uid'],'没有编辑该答案的权限！');
            $user=query_user(array('space_url','nickname','avatar128','uid','nickname'));

            //获取当前问题的最佳答案
            $question=$this->questionModel->getData($answer['question_id']);
            $this->assign('question',$question);
            if($question['best_answer']){
                $map['id']=$question['best_answer'];
                $best_answer=$this->questionAnswerModel->getData($map);
            }else{
                $map['question_id']=$answer['question_id'];
                $map['id']=array('neq',$aAnswerId);
                $best_answer=$this->questionAnswerModel->getData($map,'support desc');
            }
            $this->assign('best_answer',$best_answer);
            //获取当前问题的最佳答案 end

            $this->assign('user',$user);
            $this->assign('answer',$answer);
            $this->display();
        }
    }

    public function support()
    {
        $this->_needLogin();
        $aId=I('post.answer_id',0,'intval');
        $aType=I('post.type',1,'intval');
        $res['status']=0;
        if(!$aId){
            $res['info']="操作失败！";
            $this->ajaxReturn($res);
        }
        $this->checkActionLimit('support_answer','question_answer_support',$aId,get_uid());
        $answer=$this->questionAnswerModel->getData(array('id'=>$aId,'status'=>1));
        if($answer['uid']==get_uid()){
            $res['info']="不能支持、反对自己的回答！";
            $this->ajaxReturn($res);
        }
        if(!($this->questionSupportModel->where(array('uid'=>get_uid(),'tablename'=>'QuestionAnswer','row'=>$aId))->count())){
            $resultAdd=$this->questionSupportModel->addData('QuestionAnswer',$aId,$aType);
        }else{
            $res['info']="你已经支持或反对过该回答，不能重复操作！";
            $this->ajaxReturn($res);
        }
        if($resultAdd){
            $result=$this->questionAnswerModel->changeNum($aId,$aType);
        }
        if($result){
            //发送消息
            $question=$this->questionModel->find($answer['question_id']);
            if($aType){
                $user_info=query_user(array('nickname','uid'));
                $tip = '用户'.$user_info['nickname'].'支持了你关于问题'.$question['title'].'的回答。';
                $title='答案被支持';
            }else{
                $tip = '你的关于问题'.$question['title'].'的回答被某些不同意见的人反对了。';
                $title='答案被反对';
            }
            /**
             * @param $to_uid 接受消息的用户ID
             * @param string $content 内容
             * @param string $title 标题，默认为  您有新的消息
             * @param $url 链接地址，不提供则默认进入消息中心
             * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
             * @param int $type 消息类型，0系统，1用户，2应用
             */
            D('Common/Message')->sendMessage($answer['uid'], $tip,$title , U('Question/index/detail',array('id'=>$answer['question_id'])), 0, 1);
            //发送消息 end
            action_log('support_answer','question_answer_support',$aId,get_uid());
            $res['info']='操作成功！'.cookie('score_tip');
            $res['status']=1;
        }else{
            $res['info']="操作失败！";
        }
        $this->ajaxReturn($res);
    }

    public function setBest()
    {
        $aAnswerId=I('post.answer_id',0,'intval');
        $aQuestion=I('post.question_id',0,'intval');
        $question=$this->questionModel->getData($aQuestion);
        $this->checkAuth('Question/Answer/setBest',$question['uid'],'没有设置权限！');
        $res['status']=0;
        if($question&&$aAnswerId){
            if($question['best_answer']){
                $this->checkAuth('Question/Answer/setBest',-1,'已有最佳答案！不能重复设置');
            }
            $result=$this->questionModel->editData(array('id'=>$aQuestion,'best_answer'=>$aAnswerId));
            if($result){
                $res['status']=1;
                $tip = '在问题【'.$question['title'].'】中你的回答被设为最佳答案。';
                $answer=$this->questionAnswerModel->getData(array('id'=>$aAnswerId));
                D('Common/Message')->sendMessage($answer['uid'], $tip, '答案被设为最佳答案', U('Question/index/detail',array('id'=>$aQuestion)), is_login(), 1);
            }else{
                $res['info']='操作失败！';
            }
        }else{
            $res['info']="非法操作！";
        }
        $this->ajaxReturn($res);
    }

    private function _doEdit()
    {
        $aQuestion=$data['question_id']=I('post.question_id',0,'intval');
        $aContent=$data['content']=I('post.content','','html');
        $aAnswerId=I('post.answer_id',0,'intval');

        if($aAnswerId){
            $now_answer=$this->questionAnswerModel->getData(array('id'=>$aAnswerId,'status'=>1));
            $this->checkAuth('Question/Answer/edit',$now_answer['uid'],'没有编辑该答案的权限');
            $this->checkActionLimit('edit_answer','question_answer',$now_answer['id'],get_uid());
            $data['id']=$aAnswerId;
            $title="编辑";
        }else{
            $this->checkAuth('Question/Answer/add',-1,'没有回答的权限');
            $this->checkActionLimit('add_answer','question_answer',0,get_uid());
            $title="发布";
        }
        $result['status']=0;
        if(!$aQuestion){
            $result['info']='参数错误！问题不存在。';
            $this->ajaxReturn($result);
        }
        if(mb_strlen($aContent,'utf-8')<20){
            $result['info']='回答内容不能少于20个字！';
            $this->ajaxReturn($result);
        }
        $res=$this->questionAnswerModel->editData($data);
        if($res){
            //发送消息
            $messageModel=D('Message');
            $user_info=query_user(array('nickname','uid'));
                /**
                 * @param $to_uid 接受消息的用户ID
                 * @param string $content 内容
                 * @param string $title 标题，默认为  您有新的消息
                 * @param $url 链接地址，不提供则默认进入消息中心
                 * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
                 * @param int $type 消息类型，0系统，1用户，2应用
                 */
            $question=$this->questionModel->find($aQuestion);
            $messageModel->sendMessage($question['uid'], $user_info['nickname'].'回答了你的问题【'.$question['title'].'】或编辑了 Ta 的答案，快去看看吧！', '问题被回答', U('Question/Index/detail',array('id'=>$aQuestion)), is_login(), 1);
            //发送消息 end
            $result['status']=1;
            if($aAnswerId){
                $result['url']=U('Question/Index/detail',array('id'=>$aQuestion));
            }
            $result['info']=$title.'回答成功！'.cookie('score_tip');
        }else{
            $result['info']=$title.'回答失败！';
        }
        $this->ajaxReturn($result);
    }
} 