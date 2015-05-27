<?php


namespace Appstore\Controller;

use Appstore\Model;
use Think\Controller;

class IndexController extends BaseController
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
      /*  dump($new_theme);exit;*/
        $this->assign('services', $new_service);
        $this->assign('tab','home');
        $this->display();
    }

    public function plugin($keywords = '', $tid = 0)
    {
        $keywords = text($keywords);
        $tid = I('tid', 0, 'intval');
        if ($tid != 0) {
            $map['type_id'] = $tid;
        }
        $map['status'] = 1;
        $map = $this->handleKeywords($keywords, $map);
        $data = D('AppstorePlugin')->getList($map, 10, 'update_time desc', 1);
        $this->assign('plugins', $data);
        $all = array(array('id' => 0, 'title' => '全部', 'count' => D('AppstoreGoods')->where(array('entity' => 1, 'status' => 1))->count()));
        $types = D('AppstoreType')->where(array('entity' => 1, 'status' => 1))->order('sort desc')->select();

        foreach ($types as &$v) {
            $v['count'] = D('AppstoreGoods')->where(array('entity' => 1, 'type_id' => $v['id'], 'status' => 1))->count();
        }
        unset($v);
        $types = array_merge($all, $types);

        $this->assign('types', $types);
        $this->assign('current_type', $tid);
        $this->assign('current', 'plugin');
        $this->display('plugin');
    }

    public function goodsdetail($id)
    {
        $good = D('AppstoreGoods')->find(intval($id));
        switch ($good['entity']) {
            case 1:
                redirect(U('pluginDetail?id=' . $good['id']));
            case 2:
                redirect(U('moduleDetail?id=' . $good['id']));
        }
    }

    public function plugindetail($id = 0)
    {
        $data = D('AppstorePlugin')->getById($id);


        if (!$data) {
            $this->error('404');
        } elseif ($data['status'] == 2 && !is_administrator() && $data['uid'] != is_login()) {
            $this->error('该插件还未审核。');
        } else {
            $versions = D('AppstoreVersion')->where(array('status' => 1, 'goods_id' => $data['id']))->order('update_time desc')->limit(100)->select();
            $this->assign('versions', $versions);
            $this->assign('data', $data);
            $this->setTitle('{$data.title|text}');
            $this->assign('current','plugin');
            $this->display();
        }
    }

    public function moduledetail($id = 0)
    {
        $data = D('AppstoreModule')->getById($id);


        if (!$data) {
            $this->error('404');
        } elseif ($data['status'] == 2 && !is_administrator() && $data['uid'] != is_login()) {
            $this->error('该插件还未审核。');
        } else {
            $versions = D('AppstoreVersion')->where(array('status' => 1, 'goods_id' => $data['id']))->order('update_time desc')->limit(100)->select();
            $this->assign('versions', $versions);
            $this->assign('data', $data);
            $this->setTitle('{$data.title|text}');
            $this->assign('current', 'module');
            $this->display();
        }
    }
    public function themedetail($id = 0)
    {
        $data = D('AppstoreTheme')->getById($id);


        if (!$data) {
            $this->error('404');
        } elseif ($data['status'] == 2 && !is_administrator() && $data['uid'] != is_login()) {
            $this->error('该插件还未审核。');
        } else {
            $versions = D('AppstoreVersion')->where(array('status' => 1, 'goods_id' => $data['id']))->order('update_time desc')->limit(100)->select();
            $this->assign('versions', $versions);
            $this->assign('data', $data);
            $this->setTitle('{$data.title|text}');
            $this->assign('current', 'theme');
            $this->display();
        }
    }
    public function module($keywords = '')
    {
        $keywords = op_t($keywords);
        $tid = I('tid', 0, 'intval');
        if ($tid != 0) {
            $map['type_id'] = $tid;
        }
        $map['status'] = 1;
        $map = $this->handleKeywords($keywords, $map);
        $data = D('AppstoreModule')->getList($map, 10, 'update_time desc', 1);
        $this->assign('plugins', $data);
        $all = array(array('id' => 0, 'title' => '全部', 'count' => D('AppstoreGoods')->where(array('entity' => 2, 'status' => 1))->count()));
        $types = D('AppstoreType')->where(array('entity' => 2, 'status' => 1))->order('sort desc')->select();

        foreach ($types as &$v) {
            $v['count'] = D('AppstoreGoods')->where(array('entity' => 2, 'type_id' => $v['id'], 'status' => 1))->count();
        }
        unset($v);
        $types = array_merge($all, $types);

        $this->assign('types', $types);
        $this->assign('current_type', $tid);
        $this->assign('current', 'module');
        $this->display('module');
    }

    public function theme($keywords = '')
    {
        $keywords = text($keywords);
        $tid = I('tid', 0, 'intval');
        if ($tid != 0) {
            $map['type_id'] = $tid;
        }
        $map['status'] = 1;
        $map = $this->handleKeywords($keywords, $map);
        $data = D('AppstoreTheme')->getList($map, 10, 'update_time desc', 1);
        $this->assign('themes', $data);
        $all = array(array('id' => 0, 'title' => '全部', 'count' => D('AppstoreGoods')->where(array('entity' => 3, 'status' => 1))->count()));
        $types = D('AppstoreType')->where(array('entity' => 3, 'status' => 1))->order('sort desc')->select();

        foreach ($types as &$v) {
            $v['count'] = D('AppstoreGoods')->where(array('entity' => 3, 'type_id' => $v['id'], 'status' => 1))->count();
        }
        unset($v);
        $types = array_merge($all, $types);

        $this->assign('types', $types);
        $this->assign('current_type', $tid);
        $this->assign('current', 'theme');
        $this->display();
    }

    /**
     * @param $keywords
     * @param $map
     * @return mixed
     * @auth 陈一枭
     */
    private function handleKeywords($keywords, $map)
    {
        if ($keywords != '') {
            $keywords = op_t(urldecode($keywords));
            $_GET['keywords'] = urlencode($keywords);
            $map['title'] = array('like', '%' . $keywords . '%');
            $this->assign('keywords', $keywords);
            return $map;
        }
        return $map;
    }

    public function download($id = 0)
    {

        if (intval($id) == 0) {
            $this->error('文件不存在。');
        } else {
            $version = D('AppstoreVersion')->find(intval(($id)));

            if (intval($version['fee']) != 0) {
                //TODO 判断是否已付费
                $this->error('无法下载付费的文件，请先购买。');
            }
            if (intval($version['pack']) == 0) {
                $this->error('文件不存在。');
            }
            $goods = D('AppstoreGoods')->find($version['goods_id']);
            D('AppstoreVersion')->where(array('id' => intval($id)))->setInc('download_count');

            D('AppstoreResource')->where(array('id' => intval($version['goods_id'])))->setInc('total_download_count');


            if (is_tip($goods['uid'])) {
                D('Common/Message')->sendMessage($goods['uid'], '下载的插件提示——' . op_t($goods['title']), '您在应用平台发布的插件有人下载了。', U('appstore/admin/myplugin'), is_login(), 2);
            }
            D('File')->download(C('DOWNLOAD_UPLOAD.rootPath'), intval($version['pack']));
        }

    }


}