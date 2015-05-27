<?php


namespace Appstore\Controller;

use Appstore\Model;
use Think\Controller;

class CloudController extends Controller
{

    public function index()
    {

        $new_plugin = D('AppstorePlugin')->getLimit(array('status' => 1), 4, 'create_time desc');
        $new_module = D('AppstoreModule')->getLimit(array('status' => 1), 4, 'create_time desc');
        $new_theme = D('AppstoreTheme')->getLimit(array('status' => 1), 4, 'create_time desc', 1);
        $new_service = D('AppstoreService')->getLimit(array('status' => 1), 2, 'create_time desc');

        $this->assign('plugins', $new_plugin);
        $this->assign('modules', $new_module);
        $this->assign('themes', $new_theme);
        $this->assign('services', $new_service);

        $this->display();
    }



}