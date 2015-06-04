<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-5-7
 * Time: 上午9:30
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Question\Controller;


use Think\Controller;

class BaseController extends Controller{
    protected  $questionModel;
    protected $questionAnswerModel;
    protected $questionCategoryModel;
    protected $questionSupportModel;

    public function _initialize()
    {
        $this->questionModel=D('Question/Question');
        $this->questionAnswerModel=D('Question/QuestionAnswer');
        $this->questionCategoryModel=D('Question/QuestionCategory');
        $this->questionSupportModel=D('Question/QuestionSupport');

        $sub_menu =
            array(
                'left' =>
                    array(
                        array('tab' => 'waitAnswer', 'title' => '待回答', 'href' => U('Question/Index/waitAnswer')),
                        array('tab' => 'goodQuestion', 'title' => '热门问题', 'href' => U('Question/Index/goodQuestion')),
                        array('tab' => 'myQuestion', 'title' => "我的" . $this->MODULE_ALIAS, 'href' => is_login() ? U('Question/Index/myQuestion') : "javascript:toast.error('登录后才能操作')"),
                        array('tab' => 'questions', 'title' => "全部" . $this->MODULE_ALIAS, 'href' => U('Question/Index/questions')),
                    ),
                'right' =>
                    array(
                        array('tab'=>'create','title' => '<i class="icon-edit"></i> 提问', 'href' =>is_login()?U('Question/index/edit'):"javascript:toast.error('登录后才能操作')"),
                        array('type'=>'search', 'input_title' => '输入问题关键字','input_name'=>'keywords','from_method'=>'post', 'action' =>U('Question/index/search')),
                    )
            );
        $this->assign('sub_menu', $sub_menu);
    }

    protected  function _needLogin()
    {
        if(!is_login()){
            $this->error('请先登录！');
        }
    }
} 