<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-8
 * Time: 下午5:09
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Question\Widget;

use Question\Model\QuestionAnswerModel;
use Question\Model\QuestionModel;
use Think\Controller;

class UcenterBlockWidget extends Controller
{
    public function render($uid = 0, $page = 1, $tab = null, $count = 10)
    {
        !$tab&&$tab='q';
        !$uid && $uid = is_login();
        //查询条件
        $map['uid']=$uid;
        $this->assign('uid',$uid);
        if($tab=='q'){
            $map['status']=1;
            list($list,$totalCount)=$this->_getList($map,$page,$count);
            $this->assign('tab','q');
        }else{
            $questionAnswerModel=new QuestionAnswerModel();
            list($list,$totalCount)=$questionAnswerModel->getMyListPage($uid,$page,'support desc,create_time desc',$count,'*');
            $user=query_user(array('uid','nickname','space_url'));
            $this->assign('user',$user);
            $this->assign('tab','a');
        }
        /* 模板赋值并渲染模板 */
        $this->assign('question_list', $list);
        $this->assign('totalCount',$totalCount);

        $this->display(T('Question@Widget/ucenterblock'));
    }

    private function _getList($map,$page=1,$r=20)
    {
        $questionModel=new QuestionModel();
        $questionAnswerModel=new QuestionAnswerModel();
        list($list,$totalCount)=$questionModel->getListPageByMap($map,$page,'create_time desc',$r,'*');
        foreach($list as &$val){
            $val['info']=msubstr(op_t($val['description']),0,200);
            $val['img']=get_pic($val['description']);
            $val['user']=query_user(array('uid','space_url','nickname'),$val['uid']);
            if($val['best_answer']){
                $val['best_answer_info']=$questionAnswerModel->getData(array('id'=>$val['best_answer'],'status'=>1));
            }else{
                $val['best_answer_info']=$questionAnswerModel->getData(array('question_id'=>$val['id'],'status'=>1),'support desc');
            }
            if($val['best_answer_info']){
                $val['best_answer_info']['content']=msubstr(op_t($val['best_answer_info']['content']),0,200);
            }
        }
        return array($list,$totalCount);
    }
} 