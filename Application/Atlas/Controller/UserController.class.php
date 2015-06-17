<?php
/**
 * 用户的搞笑类
 *
 * User: Administrator
 * Date: 2015/6/17
 * Time: 22:38
 */
namespace Atlas\Controller;
use Think\Controller;
use Atlas\Api\AtlasApi;

class UserController extends FrontBaseController {

    function index($page = 1){
        $this->requireLogin();
        $uid = isset($_GET['uid']) ? op_t($_GET['uid']) : is_login();

        $map['status'] = 1;
        $map['uid'] = $uid;

        $page = intval($page);
        $atlas_list = $this->atlasModel->where($map)->page($page, 10)->order('addtime desc, id desc')->select();
        $totalCount = $this->atlasModel->where($map)->count();
        $list_ids = getSubByKey($atlas_list, 'id');
        $atlas_list = $this->getAtlasByIds($list_ids);
        $this->assign('atlas_list', $atlas_list);
        $this->assign('totalCount', $totalCount);
        $this->assign('current','user');

    }
}