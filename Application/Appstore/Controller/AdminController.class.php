<?php


namespace Appstore\Controller;

use Appstore\Model;
use Think\Controller;

class AdminController extends BaseController
{

    public function _initialize()
    {
        if (!is_login()) {
            $this->error('请登陆后再发布。将跳转到登陆页面。', U('home/user/login'));
        }
        $this->getPercent();
        parent::_initialize();
    }

    public function index()
    {

        $this->display();
    }

    public function  addversion($id = 0)
    {
        $form = new \Vendor\zebra\Zebra_Form('form');
        if (IS_POST) {
            if (empty($_POST)) {
                $this->error('您所上传的文件过大。');
            }
            if (!$form->validate()) {
                $this->error('验证不通过。');
            };

            $id = intval(I('get.id'));
            $goods = D('AppstoreGoods')->find($id);
            //       dump($goods);exit;
            if ($goods['uid'] != is_login() && !is_administrator()) {
                $this->error('404');
            }
            $version['title'] = op_t(I('post.title'));
            $version['create_time'] = time();
            $version['log'] = op_t(I('post.log'));
            $version['status'] = 1;

            $version['update_time'] = time();
            $version['fee'] = I('post.fee', '0', 'floatval');
            $version['goods_id'] = $id;

            $version['pack'] = intval(I('post.pack'));


            $result = D('AppstoreVersion')->add($version);

            D('AppstoreGoods')->where(array('id' => intval(I('post.goods_id'))))->setField('update_time', time());
            D('AppstoreResource')->where(array('id' => intval(I('post.goods_id'))))->setField('latest_version', $version['title']);
            if ($result) {
                $type=array(1=>'plugin',2=>'module',3=>'theme',4=>'service');
                $this->success('添加成功。', U('index/' . $type[$goods['entity']] . 'detail', array('id' => $id)));
            } else {
                $this->error('添加失败。');
            }
        } else {
            $id = intval($id);
            if ($id == 0) {
                $this->error('404');
            }
            //检查所有者
            $goods = D('AppstoreGoods')->find($id);
            if ($goods['uid'] != is_login()) {
                $this->error('404');
            }

            if ($goods['entity'] == 1) {
                $this->assign('entity', '插件');
            }

            /*构建表单*/

            /*名称 title 字段*/


            $form->add('label', 'label_goodstitle', 'goodstitle', '当前更新的商品', null);
            $obj = $form->add('text', 'goodstitle', $goods['title'], array('readonly' => 'readonly'));
            $obj->set_rule(array(
                'readonly' => 1,
            ));

            $form = $this->getMoney($form);
            $form->add('label', 'label_title', 'title', '版本号', null);
            $obj = $form->add('text', 'title', '');
            $obj->set_rule(array(
                'required' => array('error', '版本号必须填写。'),
                'length' => array(2, 20, 'error', '长度必须在2-20个字之间。')
            ));
            $form->add('note', 'note_title', 'title', '版本号，最短2个字，最长20个字，建议采用<a target="_blank" href="http://blog.csdn.net/ccj2020/article/details/7833035">linux版本号命名法</a>', array('style' => 'width:400px'));

            /*日志 log 字段*/
            $form->add('label', 'label_log', 'log', '更新日志');
            $obj = $form->add('textarea', 'log', '', array('autocomplete' => 'off', 'style' => 'width:500px'));
            $obj->set_rule(array(
                'required' => array('error', '必须填写更新日志，否则小伙伴搞不清楚的哟。'),
                'length' => array(0, 5000, 'error', '不得超过500个字', true)
            ));
            $form->add('note', 'note_log', 'log', '必填，填写更新日志，例如修复了什么？增加了什么？', array('style' => 'width:400px'));

            /*日志 log 字段*/
            $form->add('hidden', 'goods_id', $goods['id']);


            /*图标cover 字段*/
            $form->add('label', 'label_cover', 'pack', '文件');
            $obj = $form->add('wuploader', 'pack');
            // set rules
            $obj->set_rule(array(
                'image' => array('error', '图标必须为 jpg, png or gif !'),
                'filesize' => array(1024000, 'error', '图标不能超过 10MB!'),
                'filetype' => array('zip,rar', 'error', '文件类型不允许')
            ));

            $form->add('note', 'note_pack', 'pack', '只允许zip和rar格式。不能超过10MB，超过请上传至第三方网盘。');
            $form->clientside_validation(
                array(
                    'close_tips' => false, //  don't show a "close" button on tips with error messages
                    'on_ready' => false, //  no function to be executed when the form is ready
                    'disable_upload_validation' => true, //  using a custom plugin for managing file uploads
                    'scroll_to_error' => true, //  don't scroll the browser window to the error message
                    'tips_position' => 'right', //  position tips with error messages to the right of the controls
                    'validate_on_the_fly' => true, //  don't validate controls on the fly
                    'validate_all' => false, //  show error messages one by one upon trying to submit an invalid form
                )
            );

            // 提交按钮
            $form->add('submit', 'btnsubmit', '提交', array('class' => 'btn-primary'));


            /*渲染*/
            $form = $form->render('*horizontal', true);


            $this->assign('form', $form);
            $this->assign('data', $goods);
            $this->display();
        }

    }

    /**统一转发函数，通过判断对象类别来转发到对应的函数
     * @param int $id
     * @auth 陈一枭
     */
    public function add($id = 0)
    {
        $good = D('AppstoreGoods')->find(intval($id));
        switch ($good['entity']) {
            case 1:
                redirect(U('addplugin?id=' . $good['id']));
                break;
            case 2:
                redirect(U('addmodule?id=' . $good['id']));
        }
    }

    public function  addplugin($id = 0)
    {

        $form = new \Vendor\zebra\Zebra_Form('form');
        if (IS_POST) {
            // 验证表单
            if ($form->validate()) {
                $base = array(); //基本数据
                $resource = array(); //资源数据
                $plugin = array(); //插件数据
                $id = intval($id);
                if ($id != 0) {
                    $base = D('AppstoreGoods')->find($id);
                    $this->checkPermission($base); //检查权限，防止越权修改
                    $base = $this->getBaseData($base);
                    D('AppstoreGoods')->save($base);
                    $resource = $this->getResourceData($base['id'], $resource);
                    D('AppstoreResource')->save($resource);
                    $plugin = $this->getPluginData($base['id'], $plugin);
                    D('AppstorePlugin')->save($plugin);
                    $this->success('插件数据修改成功。');
                } else {

                    $base = $this->getBaseData($base);
                    $base['entity'] = 1;
                    $base['create_time'] = time();
                    $base['status'] = C('INIT_STATUS');
                    $goods_id = D('AppstoreGoods')->add($base);
                    if ($goods_id == 0) {
                        $this->error('创建失败。');
                    }
                    $resource = $this->getResourceData($goods_id, $resource);
                    D('AppstoreResource')->add($resource);
                    $plugin = $this->getPluginData($goods_id, $plugin);
                    $result = D('AppstorePlugin')->add($plugin);

                    $user = query_user(array('nickname'));
                    $admin_uids = explode(',', C('USER_ADMINISTRATOR'));
                    foreach ($admin_uids as $admin_uid) {
                        D('Common/Message')->sendMessage($admin_uid, "{$user['nickname']}提交了新的插件《{$base['title']}》，请到后台审核。", $title = '应用商店插件提交提醒', U('admin/appstore/verify'), is_login(), 2);
                    }

                    $this->success('插件创建成功。请耐心等待审核，在此期间，您可以上传一个版本，只有审核通过，且有新版本的插件才能够显示在前台。', U('index/pluginDetail', array('id' => $goods_id)));
                }

            } else {
                $this->error('验证失败');
            }

        } else {


            $id = intval($id);
            $data = null;
            if ($id != 0) {
                $data = D('AppstorePlugin')->getById($id);

            }
            $this->checkCreatePermission($data);
            /*日志 log 字段*/
            $form->add('hidden', 'goods_id', $data['id']);

            /*名称 title 字段*/
            $form->add('label', 'label_title', 'title', '名称', null);
            $obj = $form->add('text', 'title', op_t($data['title']));
            $obj->set_rule(array(
                'required' => array('error', '插件名称必须填写。'),
                'length' => array(2, 20, 'error', '长度必须在2-20个字之间。')
            ));

            $form->add('note', 'note_title', 'title', '必填，最短2个字，最长20个字。', array('style' => 'width:200px'));


            /*英文名称 etitle 字段*/
            $form->add('label', 'label_etitle', 'etitle', '英文名称', null);
            $obj = $form->add('text', 'etitle', $data['etitle']);
            $obj->set_rule(array(
                'required' => array('error', '插件名称必须填写。'),
                'length' => array(1, 20, 'error', '长度必须在1-20个字之间。')
            ));
            $form->add('note', 'note_etitle', 'etitle', '必填，最短1个字，最长20个字。尽量不要与其他插件重名。', array('style' => 'width:400px'));


            /*作者 author 字段*/
            $form->add('label', 'label_author', 'author', '作者', null);
            $obj = $form->add('text', 'author', $data['author']);
            $obj->set_rule(array(
                'length' => array(2, 20, 'error', '长度必须在2-20个字之间。')
            ));
            $form->add('note', 'note_author', 'author', '填写该插件的作者名称，如果不填则为当前登录ID。', array('style' => 'width:300px'));

            /*分类 type_id 字段*/
            $options = get_type_select(1);
            $form->add('label', 'label_type', 'type_id', '分类');
            $obj = $form->add('select', 'type_id', $data['type_id']);
            $obj->add_options($options, true);
            $obj->set_rule(array(
                'required' => array('error', '请务必选择分类。')
            ));

            /*测试uploader*/

            /*测试uploader*/

            /*图标cover 字段*/
            $form->add('label', 'label_cover', 'cover', '插件图标');
            $obj = $form->add('wuploadpicture', 'cover', $data['cover'], array('width' => 80, 'height' => 80));
            // set rules
            $obj->set_rule(array(
                'filetype' => array('jpg,png,gif', 'error', '图标必须为 jpg, png or gif !'),
                'filesize' => array(1024000, 'error', '图标不能超过 1MB!'),
            ));
            $form->add('note', 'note_cover', 'cover', '建议尺寸 80 * 80 ', array('style' => 'width:200px'));


            /*兼容版本 compat 字段*/
            $form->add('label', 'label_extra', 'compat', '兼容版本');
            if ($data) {

                $obj = $form->add('checkboxes', 'compat[]', get_all_compat_version(), decode_compat_to_array_from_db($data['compat']));
            } else {
                $obj = $form->add('checkboxes', 'compat[]', get_all_compat_version(), array('5', '4', '3'));
            }

            $obj->set_rule(array(
                'required' => array('error', '至少兼容一种版本。')
            ));


            /*插件扩展字段*/
            /*名称 title 字段*/
            $form->add('label', 'label_hook', 'hook', '调用到的钩子');
            $obj = $form->add('text', 'hook', $data['hook'], array('autocomplete' => 'off', 'style' => 'width:350px'));
            $obj->set_rule(array(
                'required' => array('error', '请填写调用到的钩子。')
            ));
            $form->add('note', 'note_hook', 'hook', '必填，请填写您的插件所调用到的钩子，多个用空格分隔。', array('style' => 'width:350px'));

            /*简介 summary 字段*/
            $form->add('label', 'label_summary', 'summary', '商品说明');
            $obj = $form->add('ueditor', 'summary', html($data['summary']), array('style' => 'width:800px'));
            $obj->set_rule(array(
                'length' => array(10, 5000, 'error', '模块说明不得超过5000字字。')
            ));
            $form->add('note', 'note_summary', 'summary', '必填，请系统化地介绍一下您的商品，有助于销量。', array('style' => 'width:500px,height:500px '));


            /*简介 summary 字段*/
            $form->add('label', 'label_instruction', 'instruction', '使用说明');
            $obj = $form->add('ueditor', 'instruction', html($data['instruction']), array('style' => 'width:500px'));
            $obj->set_rule(array(
                'length' => array(0, 5000, 'error', '使用说明不得超过5000字之间。')
            ));
            $form->add('note', 'note_instruction', 'instruction', '选填，介绍如何使用这个插件。', array('style' => 'width:200px'));

            $form->clientside_validation(
                array(
                    'close_tips' => false, //  don't show a "close" button on tips with error messages
                    'on_ready' => false, //  no function to be executed when the form is ready
                    'disable_upload_validation' => true, //  using a custom plugin for managing file uploads
                    'scroll_to_error' => true, //  don't scroll the browser window to the error message
                    'tips_position' => 'right', //  position tips with error messages to the right of the controls
                    'validate_on_the_fly' => true, //  don't validate controls on the fly
                    'validate_all' => false, //  show error messages one by one upon trying to submit an invalid form
                )
            );
            // 提交按钮
            $form->add('submit', 'btnsubmit', '提交', array('class' => 'btn-primary'));

            /*渲染*/
            $form = $form->render('*horizontal', true, array('class' => 'form-horizontal'));
            $this->assign('current', 'plugin');
            $this->assign('form', $form);
            $this->display();
        }


    }

    public function addtheme($id = 0)
    {
        $form = new \Vendor\zebra\Zebra_Form('form');
        if (IS_POST) {
            // 验证表单
            if ($form->validate()) {
                $base = array(); //基本数据
                $resource = array(); //资源数据
                $theme = array(); //插件数据
                $id = intval($id);
                if ($id != 0) {
                    $base = D('AppstoreGoods')->find($id);
                    $this->checkPermission($base); //检查权限，防止越权修改
                    $base = $this->getBaseData($base);
                    D('AppstoreGoods')->save($base);
                    $resource = $this->getResourceData($base['id'], $resource);
                    D('AppstoreResource')->save($resource);
                    $theme = $this->getThemeData($base['id'], $theme);
                    D('AppstoreTheme')->save($theme);
                    $this->success('商品数据修改成功。');
                } else {

                    $base = $this->getBaseData($base);
                    $base['entity'] = 3;
                    $base['create_time'] = time();
                    $base['status'] = C('INIT_STATUS');
                    $goods_id = D('AppstoreGoods')->add($base);
                    if ($goods_id == 0) {
                        $this->error('创建失败。');
                    }
                    $resource = $this->getResourceData($goods_id, $resource);
                    D('AppstoreResource')->add($resource);
                    $theme = $this->getModuleData($goods_id, $theme);
                    $result = D('AppstoreTheme')->add($theme);

                    $user = query_user(array('nickname'));
                    $admin_uids = explode(',', C('USER_ADMINISTRATOR'));
                    foreach ($admin_uids as $admin_uid) {
                        D('Common/Message')->sendMessage($admin_uid, "{$user['nickname']}提交了新的主题《{$base['title']}》，请到后台审核。", $title = '应用商店模块提交提醒', U('admin/appstore/verify'), is_login(), 2);
                    }

                    $this->success('商品创建成功。请耐心等待审核，在此期间，您可以上传一个版本，只有审核通过，且有新版本的商品才能够显示在前台。', U('appstore/index/themeDetail', array('id' => $goods_id)));
                }

            } else {
                $this->error('验证失败');
            }

        } else {
            $this->checkAuth(null, -1, '您不具备发布主题的权限，无法发布主题。');
            $id = intval($id);
            $data = array();
            if ($id != 0) {
                $data = D('AppstoreTheme')->getById($id);

            }
            $this->checkCreatePermission($data);
            $obj = $this->buildBaseForm($form, $data, 3);

            /*兼容版本 compat 字段*/
            $form->add('label', 'label_compatible', 'compatible', '兼容浏览器');
            if ($data) {

                $obj = $form->add('checkboxes', 'compatible[]', array('ie8' => 'IE8', 'ie9' => 'ie9', 'ie10' => 'ie10', 'ie11' => 'ie11', 'ff' => 'firefox', 'chrome' => 'chrome', 'safari' => 'safari', 'edge'), decode_compat_to_array_from_db($data['compat']));
            } else {
                $obj = $form->add('checkboxes', 'compatible[]', array('ie8' => 'IE8', 'ie9' => 'ie9', 'ie10' => 'ie10', 'ie11' => 'ie11', 'ff' => 'firefox', 'chrome' => 'chrome', 'safari' => 'safari', 'edge'));
            }

            $this->buildEditor($form, $data);


            /*渲染*/
            $form = $form->render('*horizontal', true, array('class' => 'form-horizontal'));

            $this->assign('form', $form);
            $this->assign('current', 'theme');
            $this->display();
        }

    }

    public function  addmodule($id = 0)
    {
        $form = new \Vendor\zebra\Zebra_Form('form');
        if (IS_POST) {
            // 验证表单
            if ($form->validate()) {
                $base = array(); //基本数据
                $resource = array(); //资源数据
                $module = array(); //插件数据
                $id = intval($id);
                if ($id != 0) {
                    $base = D('AppstoreGoods')->find($id);
                    $this->checkPermission($base); //检查权限，防止越权修改
                    $base = $this->getBaseData($base);
                    D('AppstoreGoods')->save($base);
                    $resource = $this->getResourceData($base['id'], $resource);
                    D('AppstoreResource')->save($resource);
                    $module = $this->getModuleData($base['id'], $module);
                    D('AppstoreModule')->save($module);
                    $this->success('商品数据修改成功。');
                } else {

                    $base = $this->getBaseData($base);
                    $base['entity'] = 2;
                    $base['create_time'] = time();
                    $base['status'] = C('INIT_STATUS');
                    $goods_id = D('AppstoreGoods')->add($base);
                    if ($goods_id == 0) {
                        $this->error('创建失败。');
                    }
                    $resource = $this->getResourceData($goods_id, $resource);
                    D('AppstoreResource')->add($resource);
                    $module = $this->getModuleData($goods_id, $module);
                    $result = D('AppstoreModule')->add($module);

                    $user = query_user(array('nickname'));
                    $admin_uids = explode(',', C('USER_ADMINISTRATOR'));
                    foreach ($admin_uids as $admin_uid) {
                        D('Common/Message')->sendMessage($admin_uid, "{$user['nickname']}提交了新的模块《{$base['title']}》，请到后台审核。", $title = '应用商店模块提交提醒', U('admin/appstore/verify'), is_login(), 2);
                    }

                    $this->success('商品创建成功。请耐心等待审核，在此期间，您可以上传一个版本，只有审核通过，且有新版本的商品才能够显示在前台。', U('index/moduleDetail', array('id' => $goods_id)));
                }

            } else {
                $this->error('验证失败');
            }

        } else {
            $this->checkAuth(null, -1, '您不具备发布模块的权限，无法发布模块。');
            $id = intval($id);
            $data = array();
            if ($id != 0) {
                $data = D('AppstoreModule')->getById($id);

            }
            $this->checkCreatePermission($data);
            $obj = $this->buildBaseForm($form, $data);


            /*插件扩展字段*/
            /*名称 title 字段*/
            $form->add('label', 'label_rely', 'rely', '依赖的模块');
            $obj = $form->add('text', 'rely', $data['rely'], array('autocomplete' => 'off', 'style' => 'width:350px'));
            $form->add('note', 'note_rely', 'rely', '必填，请填写您的模块所依赖的模块，如果您的模块完全独立则不需要填，逗号分隔<br/>例如：Weibo,Forum。', array('style' => 'width:500px'));
            $this->buildEditor($form, $data);


            /*渲染*/
            $form = $form->render('*horizontal', true, array('class' => 'form-horizontal'));

            $this->assign('form', $form);
            $this->assign('current', 'module');
            $this->display();
        }


    }

    public function testupload()
    {

        $this->display();
    }

    public function testpicture()
    {
        $this->display();
    }

    public function testuploadone()
    {

        $this->display();
    }

    private function getMoney($form)
    {


        /*售价 fee 字段*/
        $form->add('label', 'label_fee', 'fee', '售价', null);
        $obj = $form->add('text', 'fee', '0');
        $obj->set_rule(array(
            'required' => array('error', '售价必须填写。'),
            'length' => array(1, 20, 'error', '长度必须在1-20个字之间。'),
            'regexp' => array('^(([1-9]\d{0,9})|0)(\.\d{1,2})?$', 'error', '必须输入一个有效的金额')
        ));
        $form->add('note', 'note_fee', 'fee', '填0则为免费。单位人民币，最多两位小数。', array('style' => 'width:400px'));
        $form->add('note', 'note_fee2', 'fee', '如果为收费插件，请务必完善开发者资料，让用户可以联系到您。', array('style' => 'width:400px'));

        return $form;
    }


    public function my()
    {
        $verifing = D('AppstoreGoods')->getLimit(array('status' => 2, 'uid' => is_login()), 10);
        $this->assign('verifing', $verifing);

        $selling = D('AppstoreGoods')->getLimit(array('status' => 1, 'uid' => is_login()), 10);

        $this->assign('selling', $selling);
        $dev = $this->getPercent();

        $this->assign('dev', $dev);
        $this->display();
    }

    public function settip($tip)
    {
        $aTip = I('tip', 0, 'intval');
        $developerModel = D('AppstoreDeveloper');
        $developer = $developerModel->find(is_login());
        if ($developer) {

            $developer['refuse_message'] = !$aTip;
            $developerModel->save($developer);
        } else {
            $developer['uid'] = is_login();
            $developer['refuse_message'] = !$aTip;
            $developerModel->add($developer);
        }


    }

    public function verify()
    {
        if (IS_POST) {
            $error = array();
            if (I('name') == '') {
                $error[] = $this->getError('请输入姓名');
            }
            if (I('qq') == '') {
                $error[] = $this->getError('请输入QQ号');
            }
            if (I('phone') == '') {
                $error[] = $this->getError('请输入电话号码');
            }
            if (I('goodat') == '') {
                $error[] = $this->getError('请输入擅长技能');
            }
            if (I('des') == '') {
                $error[] = $this->getError('请输入自我介绍');
            }
            if (count($error) > 0) {
                $this->error($error);
            }

            $dev['uid'] = is_login();
            $dev['name'] = I('name');
            $dev['qq'] = I('qq');
            $dev['phone'] = I('phone');
            $dev['goodat'] = I('goodat');
            $dev['des'] = I('des');
            D('AppstoreDeveloper')->add($dev, null, 1);
            $this->success(<<<string
    <div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
   保存成功！
</div>
string
            );

        }
        $dev = D('AppstoreDeveloper')->find(is_login());
        $this->assign('dev', $dev);


        $this->display();
    }

    private function getError($tip)
    {
        return
            <<<string
    <div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <strong>错误！</strong> {$tip}
</div>
string;
    }

    public function myplugin()
    {
        $verifing = D('AppstorePlugin')->getLimit(array('status' => 2, 'uid' => is_login(), 'entity' => 1), 1000, 'update_time desc', 1);


        $this->assign('verifing', $verifing);

        $selling = D('AppstorePlugin')->getList(array('status' => 1, 'uid' => is_login(), 'entity' => 1), 10, 'update_time desc', 1);

        $this->assign('selling', $selling);

        $this->display();
    }

    public function mymodule()
    {
        $verifing = D('AppstoreModule')->getLimit(array('status' => 2, 'uid' => is_login(), 'entity' => 1), 1000, 'update_time desc', 1);


        $this->assign('verifing', $verifing);

        $selling = D('AppstoreModule')->getList(array('status' => 1, 'uid' => is_login(), 'entity' => 1), 10, 'update_time desc', 1);

        $this->assign('selling', $selling);

        $this->display();
    }

    /**
     * @param $base
     * @param $v
     * @return array
     * @auth 陈一枭
     */
    private function getBaseData($base)
    {
        $base['title'] = text(I('post.title'));
        $base['summary'] = html(I('post.summary', '', 'html'));
        $base['cover'] = intval(I('post.cover'));
        $base['uid'] = is_login();
        $base['author'] = op_t(I('post.author'));
        $compat = I('post.compat');
        foreach ($compat as &$v) {
            $v = '[' . $v . ']';
        }
        unset($v);

        $base['compat'] = implode(',', $compat);
        $base['type_id'] = intval(I('post.type_id'));

        $base['update_time'] = time();
        return $base;
    }

    /**
     * @param $goods_id
     * @param $resource
     * @return mixed
     * @auth 陈一枭
     */
    private function getResourceData($goods_id, $resource)
    {
        $resource['id'] = $goods_id;
        $resource['etitle'] = op_t(I('post.etitle'));
        $resource['instruction'] = op_h(I('post.instruction', '', 'html'));
        return $resource;
    }

    /**
     * @param $goods_id
     * @param $plugin
     * @return mixed
     * @auth 陈一枭
     */
    private function getPluginData($goods_id, $plugin)
    {
        $plugin['id'] = $goods_id;
        $plugin['hook'] = op_t(I('post.hook'));
        return $plugin;
    }

    /**
     * @param $goods_id
     * @param $plugin
     * @return mixed
     * @auth 陈一枭
     */
    private function getModuleData($goods_id, $module)
    {
        $module['id'] = $goods_id;
        $module['hook'] = text(I('post.rely'));
        return $module;
    }

    private function getThemeData($goods_id, $theme)
    {
        $module['id'] = $goods_id;
        $module['theme'] = text(I('post.theme'));
        return $module;
    }


    /**
     * @param $base
     * @auth 陈一枭
     */
    private function checkPermission($base)
    {
        if ($base['uid'] != is_login() && !check_auth('Appstore/Admin/manage')) {
            $this->error('请不要操作他人的商品。');
        }
    }

    public function checkCreatePermission($data)
    {
        if ($data == null) {
            $this->checkAuth(null, -1, '您不具备发布插件的权限，无法发布插件。');
        } else {
            if ($data['uid'] != is_login() && !check_auth('Appstore/Admin/manage')) {
                $this->error('请不要越权操作。');
            }
        }

    }

    /**
     * @return mixed
     * @auth 陈一枭
     */
    private function getPercent()
    {
        $dev = D('AppstoreDeveloper')->find(is_login());
        $total = 0;
        $filled = 0;
        foreach ($dev as $key => $v) {
            if (!in_array($key, explode(',', 'uid,status,refuse_message'))) {
                $total++;
                if ($v != '') {
                    $filled++;
                }
            }
        }
        $percent = $filled / $total * 100;

        $this->assign('percent', $percent);
        return $dev;
    }

    /**
     * @param $form
     * @param $data
     * @return mixed
     */
    public function buildBaseForm($form, $data, $entity = 2)
    {
        /*日志 log 字段*/
        $form->add('hidden', 'goods_id', $data['id']);

        /*名称 title 字段*/
        $form->add('label', 'label_title', 'title', '名称', null);
        $obj = $form->add('text', 'title', op_t($data['title']));
        $obj->set_rule(array(
            'required' => array('error', '名称必须填写。'),
            'length' => array(2, 20, 'error', '长度必须在2-20个字之间。')
        ));

        $form->add('note', 'note_title', 'title', '必填，最短2个字，最长20个字。', array('style' => 'width:200px'));


        /*英文名称 etitle 字段*/
        $form->add('label', 'label_etitle', 'etitle', '英文名称', null);
        $obj = $form->add('text', 'etitle', $data['etitle']);
        $obj->set_rule(array(
            'required' => array('error', '名称必须填写。'),
            'length' => array(1, 20, 'error', '长度必须在1-20个字之间。')
        ));
        $form->add('note', 'note_etitle', 'etitle', '必填，最短1个字，最长20个字。尽量不要与其他模块重名。', array('style' => 'width:400px'));


        /*作者 author 字段*/
        $form->add('label', 'label_author', 'author', '作者', null);
        $obj = $form->add('text', 'author', $data['author']);
        $obj->set_rule(array(
            'length' => array(2, 20, 'error', '长度必须在2-20个字之间。')
        ));
        $form->add('note', 'note_author', 'author', '填写该插件的作者名称，如果不填则为当前登录ID。', array('style' => 'width:300px'));

        /*分类 type_id 字段*/
        $options = get_type_select($entity);
        $form->add('label', 'label_type', 'type_id', '分类');
        $obj = $form->add('select', 'type_id', $data['type_id']);
        $obj->add_options($options, true);
        $obj->set_rule(array(
            'required' => array('error', '请务必选择分类。')
        ));

        /*图标cover 字段*/
        $form->add('label', 'label_cover', 'cover', '图标');
        $obj = $form->add('wuploadpicture', 'cover', $data['cover'], array('width' => 80, 'height' => 80));
        // set rules
        $obj->set_rule(array(
            'filetype' => array('jpg,png,gif', 'error', '图标必须为 jpg, png or gif !'),
            'filesize' => array(1024000, 'error', '图标不能超过 1MB!'),
        ));
        $form->add('note', 'note_cover', 'cover', '建议尺寸 80 * 80 ', array('style' => 'width:200px'));


        /*兼容版本 compat 字段*/
        $form->add('label', 'label_extra', 'compat', '兼容版本');
        if ($data) {

            $obj = $form->add('checkboxes', 'compat[]', get_all_compat_version(), decode_compat_to_array_from_db($data['compat']));
        } else {
            $obj = $form->add('checkboxes', 'compat[]', get_all_compat_version(), array('5', '4', '3'));
        }

        $obj->set_rule(array(
            'required' => array('error', '至少兼容一种版本。')
        ));
        return $obj;
    }

    /**
     * @param $form
     * @param $data
     */
    public function buildEditor($form, $data)
    {
        /*简介 summary 字段*/
        $form->add('label', 'label_summary', 'summary', '商品介绍');
        $obj = $form->add('ueditor', 'summary', html($data['summary']), array('style' => 'width:800px'));
        $obj->set_rule(array(
            'length' => array(10, 5000, 'error', '模块说明不得超过5000字字。')
        ));
        $form->add('note', 'note_summary', 'summary', '必填，请系统化地介绍一下您的模块，有助于销量。', array('style' => 'width:500px,height:500px '));


        /*简介 summary 字段*/
        $form->add('label', 'label_instruction', 'instruction', '使用说明');
        $obj = $form->add('ueditor', 'instruction', html($data['instruction']), array('style' => 'width:800px'));
        $obj->set_rule(array(
            'length' => array(0, 5000, 'error', '使用说明不得超过5000字之间。')
        ));
        $form->add('note', 'note_instruction', 'instruction', '选填，介绍如何使用。', array('style' => 'width:200px'));

        $form->clientside_validation(
            array(
                'close_tips' => false, //  don't show a "close" button on tips with error messages
                'on_ready' => false, //  no function to be executed when the form is ready
                'disable_upload_validation' => true, //  using a custom plugin for managing file uploads
                'scroll_to_error' => true, //  don't scroll the browser window to the error message
                'tips_position' => 'right', //  position tips with error messages to the right of the controls
                'validate_on_the_fly' => true, //  don't validate controls on the fly
                'validate_all' => false, //  show error messages one by one upon trying to submit an invalid form
            )
        );
        // 提交按钮
        $form->add('submit', 'btnsubmit', '提交', array('class' => 'btn-primary'));
    }


}