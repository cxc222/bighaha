<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-30
 * Time: 下午1:28
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Question\Widget;


use Question\Model\QuestionAnswerModel;
use Question\Model\QuestionModel;
use Think\Controller;

class HomeBlockWidget extends Controller{
    public function render()
    {
        $this->assignQuestion();
        $this->display(T('Application://Question@Widget/homeblock'));
    }

    public function oneQuestion($question=array())
    {
        $this->assign('data',$question);
        $this->display(T('Application://Question@Public/_default_list'));
    }

    private function assignQuestion()
    {
        $num = modC('QUESTION_SHOW_COUNT', 4, 'Question');
        $type= modC('QUESTION_SHOW_TYPE', 0, 'Question');
        $field = modC('QUESTION_SHOW_ORDER_FIELD', 'answer_num', 'Question');
        $order = modC('QUESTION_SHOW_ORDER_TYPE', 'desc', 'Question');
        $cache = modC('QUESTION_SHOW_CACHE_TIME', 600, 'Question');
        $list = S('question_home_data');
        if (!$list) {
            if($type){
                $map['is_recommend']=1;
            }
            $map['status']=1;
            $list=$this->_getList($map,$num,$field.' '.$order);
            if(!$list){
                $list=1;
            }
            S('question_home_data', $list, $cache);
        }
        unset($v);
        if($list==1){
            $list=null;
        }
        $this->assign('question_lists', $list);
    }

    private function _getList($map,$limit,$order)
    {
        $questionModel=new QuestionModel();
        $questionAnswerModel=new QuestionAnswerModel();
        $list=$questionModel->getList($map,'*',$limit,$order);
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
        return $list;
    }
} 