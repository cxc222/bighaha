<?php


namespace People\Controller;

use Think\Controller;


class IndexController extends Controller
{
    /**
     * 业务逻辑都放在 WeiboApi 中
     * @var
     */
    public function _initialize()
    {
    }

    public function index($page = 1, $keywords = '')
    {
        $map = $this->setMap();
        $map['status'] = 1;
        $map['last_login_time'] = array('neq', 0);
        $peoples = S('People_peoples_'.I('page',0,'intval').'_' . serialize($map));
        if (empty($peoples)) {
            $peoples = D('Member')->where($map)->field('uid', 'reg_time', 'last_login_time')->order('last_login_time desc')->findPage(20);

            $userConfigModel = D('Ucenter/UserConfig');
            $titleModel = D('Ucenter/Title');
            foreach ($peoples['data'] as &$v) {
                $v = query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'icons_html', 'score', 'title', 'fans', 'following', 'rank_link'), $v['uid']);
                $v['level'] = $titleModel->getCurrentTitleInfo($v['uid']);
                //获取用户封面id
                $where = getUserConfigMap('user_cover', '', $v['uid']);
                $where['role_id'] = 0;
                $model = $userConfigModel;
                $cover = $model->findData($where);
                $v['cover_id'] = $cover['value'];
                $v['cover_path'] = getThumbImageById($cover['value'], 273, 80);
            }
            unset($v);
            S('People_peoples_' . serialize($map), $peoples,300);
        }


        $this->assign('tab','index');
        $this->assign('lists', $peoples);
        $this->display();
    }

    private function setMap()
    {
        $aTag = I('tag', 0, 'intval');
        $map = array();
        if ($aTag) {
            !isset($_GET['tag']) && $_GET['tag'] = $_POST['tag'];
            $map_uids['tags'] = array('like', '%[' . $aTag . ']%');
            $links = D('Ucenter/UserTagLink')->getListByMap($map_uids);
            $uids = array_column($links, 'uid');
            $map['uid'] = array('in', $uids);
            $this->assign('tag_id', $aTag);
        }
        $userTagModel = D('Ucenter/UserTag');
        $tag_list = $userTagModel->getTreeList();
        $this->assign('tag_list', $tag_list);

        $nickname = I('keywords', '', 'op_t');
        if ($nickname != '') {
            !isset($_GET['keywords']) && $_GET['keywords'] = $_POST['keywords'];
            $map['nickname'] = array('like', '%' . $nickname . '%');
            $this->assign('nickname', $nickname);
        }
        return $map;
    }
}