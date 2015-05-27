<?php
namespace Admin\Controller;
define('IT_SINGLE_TEXT', 0);
define('IT_MULTI_TEXT', 1);
define('IT_SELECT', 2);
define('IT_EDITOR', 6);
define('IT_DATE', 5);
define('IT_RADIO', 3);
define('IT_PIC', 7);
define('IT_CHECKBOX', 4);

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Think\Controller;

class CatController extends AdminController
{
    private $_model_category;


    public function _initialize()
    {
        $this->BAN_FIELD_NAME = array('name', 'app', 'act', 'mod', 'entity_id', 'field_id', 'title');

    }


    /**
     * 信息管理页面
     */
    public function info($entity_id = 0, $page = 1, $r = 20, $title = '')
    {
        $entity_id = intval($entity_id);
        $map['status'] = array('in', array(1,0));
        if ($title != '') {
            $map['title'] = array('like', "%{$title}%");
        }
        if ($entity_id != 0) {
            $map['entity_id'] = $entity_id;
            $list = D('cat_info')->where($map)->order('create_time desc')->page($page, $r)->select();
            $totalCount = D('cat_info')->where($map)->count();
            $entity = D('cat_entity')->find($entity_id);
            foreach ($list as &$v) {
                $v['entity_alias'] = $entity['alias'];
            }
            unset($v);
        } else {
            $list = D('cat_info')->where($map)->order('create_time desc')->page($page, $r)->select();
            $totalCount = D('cat_info')->where($map)->count();
            foreach ($list as &$v) {
                $entity = D('cat_entity')->find($v['entity_id']);
                $v['entity_alias'] = $entity['alias'];
            }
            unset($v);
        }

        $listBuilder = new AdminListBuilder();

        $listBuilder
            ->ajaxButton(U('setInfoField'), array('field' => 'status', 'value' => -1), '删除')
            ->ajaxButton(U('setInfoField'), array('field' => 'top', 'value' => 1), '设为置顶')
            ->ajaxButton(U('setInfoField'), array('field' => 'top', 'value' => 0), '取消置顶')
            ->ajaxButton(U('setInfoField'), array('field' => 'recom', 'value' => 1), '设为推荐')
            ->ajaxButton(U('setInfoField'), array('field' => 'recom', 'value' => 0), '取消推荐')
            ->ajaxButton(U('setInfoField'), array('field' => 'status', 'value' => 2), '审核不通过')
            ->search('搜索', 'title', null, '标题');
        $listBuilder->title('信息管理页面')
            ->keyId()
            ->keyLink('entity_alias', '信息模型', '?entity_id={$entity_id}')
            ->keyLink('title','标题','Cat/Index/info?info_id=###')
            ->keyStatus()
            ->keyBool('top', '置顶')
            ->keyBool('recom', '推荐');
        $listBuilder->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function verify($entity_id = 0, $title = '', $page = 1, $r = 20)
    {
        $entity_id = intval($entity_id);
        $map['status'] = array('eq', 2);
        if ($title != '') {
            $map['title'] = array('like', "%{$title}%");
        }
        if ($entity_id != 0) {
            $map['entity_id'] = $entity_id;
            $list = D('cat_info')->where($map)->order('create_time desc')->page($page, $r)->select();
            $totalCount = D('cat_info')->where($map)->count();
            $entity = D('cat_entity')->find($entity_id);
            foreach ($list as &$v) {
                $v['entity_alias'] = $entity['alias'];
            }
            unset($v);
        } else {
            $list = D('cat_info')->where($map)->order('create_time desc')->page($page, $r)->select();
            $totalCount = D('cat_info')->where($map)->count();
            foreach ($list as &$v) {
                $entity = D('cat_entity')->find($v['entity_id']);
                $v['entity_alias'] = $entity['alias'];
            }
            unset($v);
        }

        $listBuilder = new AdminListBuilder();

        $listBuilder
            ->ajaxButton(U('setInfoField'), array('field' => 'status', 'value' => -1), '删除')
            ->ajaxButton(U('setInfoField'), array('field' => 'status', 'value' => 1), '审核通过');
        $listBuilder->title('信息审核页面')
            ->keyId()
            ->keyLink('entity_alias', '信息模型', '?entity_id={$entity_id}')
            ->keyTitle()
            ->keyStatus()
            ->keyBool('top', '置顶')
            ->keyBool('recom', '推荐');
        $listBuilder->search('搜索', 'title', null, '标题');
        $listBuilder->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    /**信息管理页面
     *
     */
    public function infoTrash($entity_id = 0, $page = 1, $r = 20, $title = '')
    {
        $entity_id = intval($entity_id);
        $map['status'] = array('eq', -1);
        if ($title != '') {
            $map['title'] = array('like', "%{$title}%");
        }
        if ($entity_id != 0) {
            $map['entity_id'] = $entity_id;
            $list = D('cat_info')->where($map)->order('create_time desc')->page($page, $r)->select();
            $totalCount = D('cat_info')->where($map)->count();
            $entity = D('cat_entity')->find($entity_id);
            foreach ($list as &$v) {
                $v['entity_alias'] = $entity['alias'];
            }
            unset($v);
        } else {
            $list = D('cat_info')->where($map)->order('create_time desc')->page($page, $r)->select();
            $totalCount = D('cat_info')->where($map)->count();
        }

        $listBuilder = new AdminListBuilder();

        $listBuilder
            ->ajaxButton(U('setInfoField'), array('field' => 'status', 'value' => 1), '还原');
        //TODO 支持清空回收站
        $listBuilder->title('信息回收站')
            ->keyId()
            ->keyLink('entity_alias', '信息模型', '?entity_id={$entity_id}')
            ->keyTitle()
            ->keyStatus()
            ->keyBool('top', '置顶')
            ->keyBool('recom', '推荐');
        $listBuilder->search('搜索', 'title', null, '标题');

        $listBuilder->data($list)
            ->pagination($totalCount, $r)
            ->display();
    }

    public function setInfoField($ids = array(), $field = 'top', $value = 1)
    {
        $infoModel = D('cat_info');
        $messageModel = D('Common/Message');
        foreach ($ids as $id) {
            $info = $infoModel->find($id);
            if ($info['status'] == 2 && $field == 'status') {

                $messageModel->sendMessage($info['uid'], '恭喜您，管理员审核通过了您发布的信息【' . $info['title'] . '】。', '【分类信息】信息审核通过通知', U('cat/index/info', array('info_id' => $info['id'])), get_uid());
            }
            $infoModel->where(array('id' => $id))->setField($field, $value);
        }

        $this->success('设置成功', $_SERVER['HTTP_REFERER']);
    }


    /**
     * 字段列表
     */
    function field($page = 1, $r = 20)
    {
        $listBuilder = new AdminListBuilder();

        $listBuilder->title('字段管理');

        $model = D('cat_field');
        $data = $model->where('entity_id=' . I('get.entity_id', 'intval') . ' and status>-1')->order('sort desc')->page($page, $r)->select();
        $totalCount = $model->where('entity_id=' . I('get.entity_id', 'intval') . ' and status>-1')->count();

        $type_alias = array(IT_SINGLE_TEXT => '单行文本', IT_MULTI_TEXT => '多行文本', IT_SELECT => '下拉框', IT_CHECKBOX => '多选框', IT_RADIO => '单选框', IT_EDITOR => '编辑器', IT_PIC => '单图上传');
        foreach ($data as $key => $v) {
            $data[$key]['input_type'] = $type_alias[$v['input_type']];
            $entity = D('cat_entity')->find($v['entity_id']);
            $data[$key]['entity_alias'] = $entity['alias'];
        }

        $listBuilder->keyId();
        $listBuilder->keyText('name', '英文名')->keyLink('entity_alias', '模型', 'field?entity_id={$entity_id}')->keyText('alias', '中文名')->keyText('sort', '排序')->keyText('input_type', '输入类型');
        $listBuilder->keyDoActionEdit('editField?id=####');


        $listBuilder->buttonNew(U('editField', array('entity_id' => I('get.entity_id', 'intval'))));
        $listBuilder->buttonDelete(U('setFieldStatus', array('status' => -1)));
        // $listBuilder->buttonSort(U('sortField'));

        $listBuilder->data($data);
        $listBuilder->pagination($totalCount, $r);
        $listBuilder->display();

    }

    public function fieldTrash($page = 1, $r = 20)
    {
        $listBuilder = new AdminListBuilder();

        $listBuilder->title('字段管理');

        $data = D('cat_field')->where(' status=-1')->order('sort desc')->page($page, $r)->select();
        $totalCount = D('cat_field')->where(' status=-1')->count();
        $type_alias = array(IT_SINGLE_TEXT => '单行文本', IT_MULTI_TEXT => '多行文本', IT_SELECT => '下拉框', IT_CHECKBOX => '多选框', IT_RADIO => '单选框', IT_EDITOR => '编辑器', IT_PIC => '单图上传');
        foreach ($data as $key => $v) {
            $data[$key]['input_type'] = $type_alias[$v['input_type']];
            $entity = D('cat_entity')->find($v['entity_id']);
            $data[$key]['entity_alias'] = $entity['alias'];
        }

        $listBuilder->keyId();
        $listBuilder->keyText('name', '英文名')->keyLink('entity_alias', '模型', 'field?entity_id={$entity_id}')->keyText('alias', '中文名')->keyText('sort', '排序')->keyText('input_type', '输入类型');
        $listBuilder->keyDoActionEdit('editField?id=####');

        $listBuilder->buttonRestore(U('setFieldStatus', array('status' => 1)));
        // $listBuilder->buttonSort(U('sortField'));

        $listBuilder->pagination($totalCount, $r);
        $listBuilder->data($data);

        $listBuilder->display();
    }

    /**
     * 删除字段
     */
    public function setFieldStatus($ids, $status)
    {
        $field_id = array('in', implode($ids));
        D('cat_field')->where(array('id' => $field_id))->setField('status', $status);
        D('cat_data')->where(array('field_id' => $field_id))->setField('status', $status);
        $this->success('操作成功。');
    }

    public function editField()
    {
        $id = I('request.id', 0, 'intval');
        $name = I('post.name', 0, 'op_t');
        $entity_id = I('request.entity_id', 0, 'intval');
        if (IS_POST) {
            $cant_use_name = $this->BAN_FIELD_NAME;
            if (in_array($name, $cant_use_name)) {
                $this->error('不能使用此字段名，此字段名被系统保留！');
            }

            $field = D('cat_field')->create();
            $field['status'] = 1;


            $rs = D('cat_field')->add($field, null, true);
            if ($rs) {
                $this->success('修改成功。');
            } else {
                $this->error('修改失败。');
            }


        } else {
            $configBuilder = new AdminConfigBuilder();

            if ($id != 0) {
                $data = D('cat_field')->find($id);
            } else {

                $data['entity_id'] = $entity_id;
                $data['can_search'] = 0;
                $data['sort'] = 0;
                $data['can_empty'] = 1;
                $data['input_type'] = 1;
                $data['status'] = 1;

            }
            $cat = D('cat_entity')->select();
            $entity = array();
            foreach ($cat as $v) {
                $entity[$v['id']] = $v['alias'];
            }

            $configBuilder->title('字段编辑')
                ->keyId()
                ->keySelect('entity_id', '实体', null, $entity)
                ->keyText('name', '英文名', '不允许使用 ' . implode(',', $this->BAN_FIELD_NAME))
                ->keyText('alias', '中文名')
                ->keyBool('can_search', '允许搜索', '只对部分字段有效')
                ->keySelect('input_type', '输入类型', null, array(0 => '单行文本', '多行文本', '下拉框', '单选框', '多选框', '日期选择', '编辑器', '单张图片上传'))
                ->keyTextArea('args', '参数')
                ->keyTextArea('option', '选项', '一行一个选项')
                ->keyText('tip', '输入提示')
                ->keyBool('over_hidden', '是否过期隐藏')
                ->keyText('sort', '排序')
                ->keyText('default_value', '默认值')
                ->keyBool('can_empty', '是否允许为空')
                ->data($data)
                ->buttonSubmit(__SELF__)
                ->buttonBack()
                ->display();
        }

    }

    public function config()
    {
        $admin_config = new AdminConfigBuilder();
        $data = $admin_config->handleConfig();

        $admin_config->title('分类信息设置')->keyTextArea('CSS', '扩展的CSS')->keyTextArea('JS', '扩展的JS')->buttonSubmit('', '保存')->data($data);
        $admin_config->display();
    }

    /**
     * 实体页面
     */
    public function entity($page = 1, $r = 20)
    {

        $data = D('cat_entity')->where('status>-1')->page($page, $r)->order('sort desc')->select();
        $totalCount = D('cat_entity')->where('status>-1')->count();
        foreach ($data as &$v) {
            $v['fields'] = '字段管理';
        }
        unset($v);
        $listBuilder = new AdminListBuilder();

        $listBuilder->title('模型管理')
            ->keyId()
            ->keyLink('name', '模型英文名', 'info?entity_id=###')
            ->keyLink('alias', '模型中文名', 'info?entity_id=###')
            ->keyLink('fields', '字段管理', 'field?entity_id=###')
            ->keyStatus()
            ->keyText('sort', '排序')
            ->keyDoActionEdit('editEntity?entity_id=###')
            ->data($data)
            ->pagination($totalCount, $r);

        $listBuilder->buttonNew(U('editentity'))
            ->buttonDelete(U('dodelentity'));
        $listBuilder->display();
    }

    public function entityTrash($page = 1, $r = 20)
    {
        $data = D('cat_entity')->where('status=-1')->page($page, $r)->order('sort desc')->select();
        $totalCount = D('cat_entity')->where('status=-1')->count();
        foreach ($data as &$v) {
            $v['fields'] = '字段管理';
        }
        unset($v);
        $listBuilder = new AdminListBuilder();

        $listBuilder->title('模型回收站')
            ->keyId()
            ->keyLink('name', '模型英文名', 'info?entity_id=###')
            ->keyLink('alias', '模型中文名', 'info?entity_id=###')
            ->keyLink('fields', '字段管理', 'field?entity_id=###')
            ->keyStatus()
            ->keyText('sort', '排序')
            /*->buttonSort(U('sort'))*/ //TODO 排序功能
            ->keyDoActionEdit('editEntity?entity_id=###')
            ->data($data)
            ->pagination($totalCount, $r);

        $listBuilder->buttonRestore(U('dodelentity'));
        $listBuilder->display();
    }

    /**
     * 删除实体
     */
    public function doDelEntity($ids, $status = -1)
    {
        D('cat_entity')->where(array('id' => array('in', implode(',', $ids))))->setField('status', $status);
        //删除关联信息
        D('cat_info')->where(array('entity_id' => array('in', implode(',', $ids))))->setField('status', $status);
        $this->success('操作成功。');
    }

    //  public function setEntityStatus($)
    public function editEntity($entity_id = 0)
    {
        $entity_id = intval($entity_id);
        if (IS_POST) {

            if ($entity_id != 0) {
                $entity = D('cat_entity')->create();
                if(mb_strlen($entity['name'],'utf-8')==0||mb_strlen($entity['alias'],'utf-8')==0){
                    $this->error('英文名称和中文名称都不能为空！');
                }

                $rs = D('cat_entity')->save($entity);
                if ($rs) {
                    $this->success('保存成功。');
                } else {
                    $this->error('保存失败。');
                }
            } else {
                $entity = D('cat_entity')->create();
                if(mb_strlen($entity['name'],'utf-8')==0||mb_strlen($entity['alias'],'utf-8')==0){
                    $this->error('英文名称和中文名称都不能为空！');
                }
                $entity['status'] = 1;
                $rs = D('cat_entity')->add($entity);
                if ($rs) {
                    $this->success('添加成功。');
                } else {
                    $this->error('添加失败。');
                }
            }

        } else {


            if ($entity_id != 0) {
                $entity = D('cat_entity')->find($entity_id);
            }

            $configBuilder = new AdminConfigBuilder();

            $entitys = D('cat_entity')->where(array('status'=>array('gt',-1)))->limit(999)->select();
            $rec_entity_options = array();
            foreach ($entitys as $v) {
                if($v['alias']){
                    $rec_entity_options[$v['id']] = $v['alias'];
                }
            }


            $path = APP_PATH . 'Cat/View/default/Tpls';
            $dir = $this->getFile($path);
            $dir_file = array();;
            foreach ($dir as $v) {
                $dir_file[$v] = $v;
            }
            $configBuilder
                ->keyId()
                ->keyText('name', '英文名称')
                ->keyText('alias', '中文名称')
                ->keyBool('show_nav', '显示在导航栏中')
                ->keyBool('show_post', '显示发布按钮')
                ->keyBool('show_index', '在首页显示')
                ->keyBool('need_active', '信息需要审核')
                ->keyCheckBox('rec_entity', '接收的实体模型', null, $rec_entity_options)
                ->keyText('sort', '排序')
                ->keyMultiUserGroup('can_post_gid', '允许发布的用户组')
                ->keyMultiUserGroup('can_read_gid', '允许阅读的用户组')
                ->keyBool('can_over', '允许设置到期时间', '可设置一些字段在到期后自动隐藏')
                ->keySelect('use_detail', '使用详情模板', null, array(0 => '自动生成', 1 => '——以下为模板文件——') + $dir_file)
                ->keySelect('use_list', '使用列表模板', null, array(-1 => '自动生成', 0 => '默认模板', 1 => '自定义模板1', 2 => '自定义模板2', 3 => '自定义模板3', -2 => '——以下为模板文件——') + $dir_file)
                ->keyTextArea('tpl_list', '默认模板Html代码')
                ->keyTextArea('tpl1', '自定义模板1Html代码')
                ->keyTextArea('tpl2', '自定义模板2Html代码')
                ->keyTextArea('tpl3', '自定义模板3Html代码')
                ->keyTextArea('des1', '自定义描述1')
                ->keyTextArea('des2', '自定义描述1')
                ->keyTextArea('des3', '自定义描述1')
                ->keyHidden('can_rec',null)->keyDefault('can_rec',0)//'允许接收其他模型信息', '在详情页显示发送按钮，用户可以向发布者发送其他模型的信息'
                ->buttonSubmit()
                ->buttonBack()
                ->data($entity)
                ->display();
        }


    }


    /**
     * 获取文件列表
     */
    private function getFile($folder)
    {
        //打开目录
        $fp = opendir($folder);
        //阅读目录
        while (false != $file = readdir($fp)) {
            //列出所有文件并去掉'.'和'..'
            if ($file != '.' && $file != '..') {
                //$file="$folder/$file";
                $file = "$file";

                //赋值给数组
                $arr_file[] = $file;

            }
        }
        //输出结果
        if (is_array($arr_file)) {
            while (list($key, $value) = each($arr_file)) {
                $files[] = $value;
            }
        }
        //关闭目录
        closedir($fp);
        return $files;


    }


}
