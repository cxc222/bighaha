<?php
namespace Home\Controller;
use Think\Controller;
class SinglepageController extends Controller
{
    // 视图
    public function index()
    {
        $filename = $_GET['filename'];
        $this->filename = $filename;
        $where = array(
            'filename' => $filename
        );
        $singlepage = M('singlepage')->where($where)->find();
        $this->singlepage = $singlepage;
        $type = $singlepage['type'];
        $templates = $singlepage['templates'];
        $this->pagelist = M('singlepage')->where(array(
            'type' => $type
        ))
            ->order('sort asc')
            ->select();
        if (empty($templates)) {
            $this->display();
        } else {
            $this->display($templates);
        }
    }

    public function add()
    {
        // 保存表单数据 包括附件数据
        $friendlink = M("friendlink"); // 实例化User对象
        $friendlink->create(); // 创建数据对象
                               // 写入用户数据到数据库
        if ($friendlink->add()) {
            $this->success("添加成功", '/html/friendlink.html');
        } else {
            $this->error('添加失败');
        }
    }
}
?>