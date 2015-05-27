<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-8
 * Time: 下午1:26
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Question\Widget;


use Question\Model\QuestionModel;
use Think\Controller;

class RightBlockWidget extends Controller{

    public function recommend()
    {
        $questionModel=new QuestionModel();
        $recommend_list=$questionModel->getList(array('is_recommend'=>1,'status'=>1),'*',5);
        foreach($recommend_list as &$val)
        {
            $val['info']=msubstr(op_t($val['description']),0,50);
        }
        unset($val);
        $this->assign('recommend_list',$recommend_list);
        $this->display(T('Application://Question@Widget/recommend'));
    }

    public function category($category_id=0)
    {
        $map['category']=$category_id;
        $map['status']=1;
        $questionModel=new QuestionModel();
        $hot_list=$questionModel->getList($map,'*',5,'answer_num desc');
        foreach($hot_list as &$val)
        {
            $val['info']=msubstr(op_t($val['description']),0,50);
        }
        unset($val);
        $this->assign('hot_list',$hot_list);
        $this->display(T('Application://Question@Widget/category'));
    }

} 