<?php


namespace Mob\Controller;

use Think\Controller;

class BlogController extends Controller{
//渲染资讯
    public function index($mark=0)
    {
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 2, 'op_t');
       if($mark==1){                        //判断是否为热点咨询或全站资讯
           $blog= D('News')->where(array('status' => 1,))->order('create_time desc,view desc')->page($aPage, $aCount)->select();
           $blog_mark['mark']=1;//标记为热点资讯
       }else{
           $blog= D('News')->where(array('status' => 1,))->order('create_time desc')->page($aPage, $aCount)->select();
           $blog_mark['mark']=0;//标记为全站资讯
       }
        foreach ($blog as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover'],119,89);
            $v['count']=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$v['id']))->order('create_time desc')->count();
        }

        $this->assign('hotblog',$blog);
        $this->assign('blogmark',$blog_mark);
        $this->display();
    }
    //加载更多资讯（热点资讯）
    public function addMoreBlog(){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 2, 'op_t');
        $aMark= I('post.mark', 0, 'op_t');
        if($aMark==1){
            $blog= D('News')->where(array('status' => 1,))->order('create_time desc,view desc')->page($aPage, $aCount)->select();
        }else{
            $blog= D('News')->where(array('status' => 1,))->order('create_time desc')->page($aPage, $aCount)->select();
        }
        foreach ($blog as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover'],119,89);
            $v['count']=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$v['id']))->order('create_time desc')->count();
        }
        if ($blog) {
            $data['html'] = "";
            foreach ($blog as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_bloglist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }



    public function blogDetail($id){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 2, 'op_t');
        $blog_detail= D('News')->where(array('status' => 1,'id'=>$id))->find();

        $blog_detail['user'] = query_user(array('nickname', 'avatar32'), $blog_detail['uid']);
        $blog_detail['cover_url'] = getThumbImageByCoverId($blog_detail['cover']);

        $blog_content= D('NewsDetail')->where(array('news_id'=>$id))->find();

        $blog_comment=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$id))->order('create_time desc')->page($aPage, $aCount)->select();
        $blog_detail['count']=count($blog_comment);
        foreach ($blog_comment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover']);
        }
       // dump($blog_comment);exit;

        $this->assign('blogdetail',$blog_detail);
        $this->assign('blogcontent',$blog_content);
        $this->assign('blogcomment',$blog_comment);
        $this->display();
    }
    public function addMoreComment(){
        dump('11111111');exit;
    }

    public function doAddComment(){
        if (!is_login()) {
            $this->error('请您先进行登录', U('Mob/index/index'), 1);
        }

        $aContent = I('post.content', '', 'op_t');              //获取评论内容
        $aBlogId = I('post.blogId', 0, 'intval');             //获取当前专辑ID


        $uid = is_login();

        $result = D('LocalComment')->addBlogComment($uid, $aBlogId, $aContent);
        action_log('add_issue_comment', 'local_comment', $result, $uid);
        // dump($result);exit;

        $blog_comment=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'id'=>$result))->order('create_time desc')->select();
        foreach ($blog_comment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32'), $v['uid']);
            $v['cover_url'] = getThumbImageByCoverId($v['cover']);
        }

        if ($blog_comment) {
            $data['html'] = "";
            foreach ($blog_comment as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_blogcomment");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
            $data['info'] = '评论失败!';
        }
        $this->ajaxReturn($data);
    }

    /**
     * @param $id
     * @param $user
     * 资讯评论模态弹窗内的内容
     */
    public function atComment($id, $user)
    {
        //$id是发帖人的IDa
        //$user是用户名


        $map['id'] = array('eq', $id);
        $blog = D('News')->where(array('status' => 1, $map))->select();
        // dump($issue);exit;


        foreach ($blog as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            $v['at_user_id'] = $user;

        }

       // dump($blog);exit;
//dump($blog[0]);
        $this->assign('blog', $blog[0]);
        $this->display(T('Application://Mob@Blog/atcomment'));

    }


    /**
     * 删除评论
     */
    public function delBlogComment()
    {
        $aCommentId = I('post.commentId', 0, 'intval');              //接收评论ID
        $aBlogId = I('post.blogId', 0, 'intval');                   //接收资讯ID
       // dump($aCommentId);
       // dump($aBlogId);exit;


        $blog_uid = D('News')->where(array('status' => 1, 'id' => $aBlogId))->find();//根据资讯ID查找资讯发送人的UID
        $comment_uid = D('LocalComment')->where(array('status' => 1, 'id' => $aCommentId))->find();//根据评论ID查找评论发送人的UID
        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }


        if (is_administrator(get_uid()) || $blog_uid['uid'] == get_uid() || $comment_uid['uid'] == get_uid()) {                                     //如果是管理员，则可以删除评论
            $result = D('LocalComment')->deleteBlogComment($aCommentId);
        }
        if ($result) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['info'] = '删除失败';
        }
        $this->ajaxReturn($return);
    }


    /**
     * 资讯分类内容显示
     */
    public function blogType()
    {
        $blog_top = D('NewsCategory')->where(array('status' => 1, 'pid' => 0))->select();        //查找顶级分类pid=0的

        foreach ($blog_top as &$v) {
            $v['lever_two'] = D('NewsCategory')->where(array('status' => 1, 'pid' => $v['id']))->select();        //查找二级分类pid=$issue_top的id
            $v['count'] = count($v['lever_two']);                //二级分类数量
            foreach ($v['lever_two'] as &$k) {
                $k['count_content'] = D('IssueContent')->where(array('status' => 1, 'issue_id' => $k['id']))->count();
            }
        }

        // dump($blog_top);exit;
        $this->assign("blog_top", $blog_top);         //顶级分类
        $this->display();
    }

    /**
     * 发布帖子页面内容渲染
     */
    public function addBlog(){
        $this->_needLogin();
        if(IS_POST){
            $this->_doEdit();
        }else{
            $aId=I('id',0,'intval');
            if($aId){
                $data=$this->newsModel->getData($aId);
                $this->checkAuth(null,$data['uid'],'你没有编辑该资讯权限！');
                if($data['status']==1){
                    $this->error('该资讯已被审核，不能被编辑！');
                }
                $this->assign('data',$data);
            }else{
                $this->checkAuth('News/Index/add',-1,'你没有投稿权限！');
            }
            $title=$aId?"编辑":"新增";
            $category=D('News/NewsCategory')->getCategoryList(array('status'=>1,'can_post'=>1),1);
            $this->assign('category',$category);
            $this->assign('title',$title);
        }
        $this->display();

    }
    private function _needLogin()
    {
        if(!is_login()){
            $this->error('请先登录！');
        }
    }

    public function doSendBlog(){
        $aId=I('post.id',0,'intval');
        $data['category']=I('post.category',0,'intval');


        if($aId){
            $data['id']=$aId;
            $now_data=D('News/newsModel')->getData($aId);
            $this->checkAuth(null,$now_data['uid'],'你没有编辑该资讯权限！');
            if($now_data['status']==1){
                $this->error('该资讯已被审核，不能被编辑！');
            }
            $category=D('News/newsCategoryModel')->where(array('status'=>1,'id'=>$data['category']))->find();

            if($category){
                if($category['can_post']){
                    if($category['need_audit']){
                        $data['status']=2;
                    }else{
                        $data['status']=1;
                    }
                }else{
                    $this->error('该分类不能投稿！');
                }
            }else{
                $this->error('该分类不存在或被禁用！');
            }
            $data['status']=2;
            $data['template']=$now_data['detail']['template']?:'';
        }else{
            $this->checkAuth('News/Index/add',-1,'你没有投稿权限！');
            $this->checkActionLimit('add_news','news',0,is_login(),true);
            $data['uid']=get_uid();
            $data['sort']=$data['position']=$data['view']=$data['comment']=$data['collection']=0;
            $category=D('News/NewsCategory')->where(array('status'=>1,'id'=>$data['category']))->find();

            if($category){
                if($category['can_post']){
                    if($category['need_audit']){
                        $data['status']=2;
                    }else{
                        $data['status']=1;
                    }
                }else{
                    $this->error('该分类不能投稿！');
                }
            }else{
                $this->error('该分类不存在或被禁用！');
            }
            $data['template']='';
        }
        $data['title']=I('post.title','','text');
        $data['cover']=I('post.attach_ids',0,'intval');
        $data['description']=I('post.description','','text');
        $data['dead_line']=I('post.dead_line','','text');
        if($data['dead_line']==''){
            $data['dead_line']=99999999999;
        }else{
            $data['dead_line']=strtotime($data['dead_line']);
        }
        $data['source']=I('post.source','','text');
        $data['content']=I('post.content','','html');


        if(!mb_strlen($data['title'],'utf-8')){
            $this->error('标题不能为空！');
        }
        if(mb_strlen($data['content'],'utf-8')<20){
            $this->error('内容不能少于20个字！');
        }


        $res=D('News/news')->editData($data);
          // dump(D('News/newsModel')->getLastSql());exit;

        $title=$aId?"编辑":"新增";
        if($res){
            if(!$aId){
                $aId=$res;
                if($category['need_audit']){
                    $this->success($title.'资讯成功！请等待审核~',U('News/Index/detail',array('id'=>$aId)));
                }
            }
            $this->success($title.'资讯成功！',U('News/Index/detail',array('id'=>$aId)));
        }else{
            $this->error($title.'资讯失败！'.$this->newsModel->getError());
        }


    }
} 