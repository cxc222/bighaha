<?php
/**
 * 列表页控制器
 */
namespace Home\Controller;

use Think\Controller;

class ArticleController extends Controller
{
    
    // 视图
    public function index()
    {
        import('Class.Category', APP_PATH);
        $id = (int) $_GET['id']; // 获取当前id
        
        $where = array(
            'id' => $id
        );
        $field = array(
            'id',
            'title',
            'click',
            'time',
            'content',
            'cid',
            'ding',
            'cai',
            'username'
        );
        $article = M('article')->where($where)->find($field);
        $this->article = $article;
        $this->user = M('user')->where(array(
            'username' => $article['username']
        ))->getField('avatar');
        
        $cid = $this->article['cid']; // 获取当前文章所在栏目
        $cateinfo = M('cate')->where(array(
            'id' => $cid
        ))->select();
        $this->cateinfo = $cateinfo[0];
        
        // 标签
        $this->tag = M('tag')->where(array(
            'aid' => $id
        ))->select();
        // 上一页
        $data['id'] = array(
            lt,
            $id
        );
        $data['cid'] = $cid;
        $data['_logic'] = 'and';
        $this->front = M('article')->where($data)
            ->limit(1)
            ->order('id desc')
            ->find($field);
        // 下一页
        $this->next = M('article')->where('id>' . $id . ' and cid=' . $cid)
            ->limit(1)
            ->order('id asc')
            ->find($field);
        $this->display();
    }
    
    // 顶
    public function ding()
    {
        header("Content-Type: text/html;charset=utf-8");
        header("Cache-Control:no-cache");
        $id = $_POST['id']; // 获取id
        $ck = "articleding" . $id; // 获取本页cookie
        if (isset($_COOKIE[$ck])) {
            $res = '{"count":"0"}';
            echo $res;
        } else {
            M('article')->where(array(
                'id' => $id
            ))->setInc('ding');
            $count = M('article')->where(array(
                'id' => $id
            ))->getField('ding');
            $res = '{"count":"' . $count . '"}';
            cookie("articleding" . $id, "gifding" . $id, 3600);
            echo $res;
        }
    }
    
    // 顶
    public function cai()
    {
        header("Content-Type: text/html;charset=utf-8");
        header("Cache-Control:no-cache");
        $id = $_POST['id']; // 获取id
        $ck = "articlecai" . $id; // 获取本页cookie
        if (isset($_COOKIE[$ck])) {
            $res = '{"count":"0"}';
            echo $res;
        } else {
            M('article')->where(array(
                'id' => $id
            ))->setInc('cai');
            $count = M('article')->where(array(
                'id' => $id
            ))->getField('cai');
            $res = '{"count":"' . $count . '"}';
            cookie("articlecai" . $id, "gifcai" . $id, 3600);
            echo $res;
        }
    }

    public function dingNum()
    {
        $id = (int) $_GET['id'];
        $num = M('article')->where(array(
            'id' => $id
        ))->getField('ding');
        echo 'document.write(' . $num . ')';
    }

    public function caiNum()
    {
        $id = (int) $_GET['id'];
        $num = M('article')->where(array(
            'id' => $id
        ))->getField('cai');
        echo 'document.write(' . $num . ')';
    }
    
    // 通过js来获取点击次数
    public function clickNum()
    {
        $id = (int) $_GET['id']; // 获取文章id
        M('article')->where(array(
            'id' => $id
        ))->setInc('click'); // click自增
        $click = M('article')->where(array(
            'id' => $id
        ))->getField('click'); // 查出click
        echo 'document.write(' . $click . ')'; // 赋值
    }
}
?>