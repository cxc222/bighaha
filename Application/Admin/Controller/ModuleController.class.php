<?php
/**
 * 所属项目 110.
 * 开发者: 陈一枭
 * 创建日期: 2014-11-18
 * 创建时间: 10:09
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Common\Model\ModuleModel;
use Think\Controller;

class ModuleController extends AdminController
{
    protected $moduleModel;

    function _initialize()
    {
        $this->moduleModel = D('Module');
        parent::_initialize();
    }

    public function lists()
    {

        $listBuilder = new AdminListBuilder();

        /*刷新模块列表时清空缓存*/
        $aRefresh = I('get.refresh', 0, 'intval');
        if ($aRefresh == 1) {
            D('Module')->reload();
        } else if ($aRefresh == 2) {
            D('Module')->cleanModulesCache();
        }
        /*刷新模块列表时清空缓存 end*/

        $modules = $this->moduleModel->getAll();

        foreach ($modules as &$m) {
            $m['alias'] = '<a href="' . U('edit?', array('name' => $m['name'])) . '"><i class="icon-' . $m['icon'] . '"></i> ' . $m['alias'] . '</a>';
            if ($m['is_setup']) {
                $m['name'] = '<i class="icon-ok" style="color: green;"></i> ' . $m['name'];
                if ($m['can_uninstall'] == 1)
                    $m['do'] = '<a class="btn btn-error "  href="' . U('uninstall', array('id' => $m['id'])) . '"><span style="color: red;" ><i class="icon-cut"></i></span> 卸载</a>';
                else {
                    $m['do'] = '系统模块';
                }
            } else {
                $m['do'] = '<a class="btn" href="' . U('install', array('name' => $m['name'])) . '"><span style="color: green;"<i class="icon-check"></i></span> 安装</a>';
                $m['name'] = '<i class="icon-remove" style="color: red;"></i> ' . $m['name'];
                // $m['do'] = '<a class="btn" onclick="moduleManager.install(\'' . $m['id'] . '\')"><span style="color: green;"<i class="icon-check"></i></span> 安装</a>';

            }

        }
        unset($m);

        $listBuilder->data($modules);
        $listBuilder->title('模块管理');

        $listBuilder->button('刷新（读入新的模块）', array('href' => U('Admin/Module/lists', array('refresh' => 2))));
        $listBuilder->button('重置（根据预设模块信息重置）', array('href' => U('Admin/Module/lists', array('refresh' => 1))));

        $listBuilder->keyId()->keyHtml('alias', '模块名')->keyText('name', '模块英文名')->keyText('summary', '模块介绍')
            ->keyText('version', '版本号')
            ->keyLink('developer', '开发者', '{$website}')->keyText('entry', '前台入口')->keyText('admin_entry', '后台入口')
            ->keyHtml('do', '操作');
        $listBuilder->display();
    }

    /**
     * 编辑模块
     */
    public function edit()
    {
        if (IS_POST) {
            $aName = I('name', '', 'text');
            $module['id'] = I('id', 0, 'intval');
            $module['name'] = empty($aName) ? $this->error('模块名不能为空。') : $aName;
            $aAlias = I('alias', '', 'text');
            $module['alias'] = empty($aAlias) ? $this->error('模块中文名不能为空。') : $aAlias;
            $aIcon = I('icon', '', 'text');
            $module['icon'] = empty($aIcon) ? $this->error('图标不能为空。') : $aIcon;
            $aSummary = I('summary', '', 'text');
            $module['summary'] = empty($aSummary) ? $this->error('介绍不能为空。') : $aSummary;
            $aEntry = I('entry', '', 'text');
            $module['entry'] = empty($aEntry) ? $this->error('前台入口不能为空。') : $aEntry;
            $aAdminEntry = I('admin_entry', '', 'text');
            $module['admin_entry'] = empty($aAdminEntry) ? $this->error('后台入口不能为空。') : $aAdminEntry;
            $module['title'] = I('name', '', '');

            if ($this->moduleModel->save($module) === false) {
                $this->error('编辑模块失败。');
            } else {
                $this->moduleModel->cleanModuleCache($name);
                $this->moduleModel->cleanModulesCache();
                $this->success('编辑模块成功。');
            }
        } else {
            $aName = I('name', '', 'text');
            $module = $this->moduleModel->getModule($aName);
            $builder = new AdminConfigBuilder();
            $builder->title('编辑模块—' . $module['alias']);
            $builder->keyId()->keyReadOnly('name', '模块名')->keyText('alias', '模块中文名')->keyReadOnly('version', '版本')
                ->keyText('icon', '图标')
                ->keyTextArea('summary', '模块介绍')
                ->keyReadOnly('developer', '开发者')
                ->keyText('entry', '前台入口')
                ->keyText('admin_entry', '后台入口');
            $builder->data($module);
            $builder->buttonSubmit()->buttonBack()->display();
        }

    }

    public function uninstall()
    {
        $aId = I('id', 0, 'intval');
        $aNav=I('remove_nav',0,'intval');
        $moduleModel = new ModuleModel();

        $module = $moduleModel->getModuleById($aId);

        if (IS_POST) {
            $aWithoutData = I('withoutData', 1, 'intval');//是否保留数据
            $res = $this->moduleModel->uninstall($aId, $aWithoutData);
            if ($res == true) {
                if (file_exists(APP_PATH . '/' . $module['name'] . '/Info/uninstall.php')) {
                    require_once(APP_PATH . '/' . $module['name'] . '/Info/uninstall.php');
                }
                if($aNav) {
                    M('Channel')->where(array('url'=>$module['entry']))->delete();
                    S('common_nav',null);
                }
                $this->success('卸载模块成功。', U('lists'));
            } else {
                $this->error('卸载模块失败。' . $this->moduleModel->error);
            }

        }


        $builder = new AdminConfigBuilder();
        $builder->title($module['alias'] . '——卸载模块');
        $module['remove_nav']=1;
        $builder->keyReadOnly('id', '模块编号');
        $builder->suggest('<span class="text-danger">请谨慎操作，此操作无法还原。</span>');
        $builder->keyReadOnly('alias', '卸载的模块');
        $builder->keyBool('withoutData', '是否保留模块数据?', '默认保留模块数据')  ->keyBool('remove_nav','移除导航','卸载后自动卸载掉对应的菜单，或者<a target="_blank" href="'.U('channel/index').'">手动设置</a>');

        $module['withoutData'] = 1;
        $builder->data($module);
        $builder->buttonSubmit();
        $builder->buttonBack();
        $builder->display();


    }


    public function install()
    {
        $aName = I('get.name', '', 'text');
        $aNav=I('add_nav',0,'intval');
        $module = $this->moduleModel->getModule($aName);

        if (IS_POST) {
            //执行guide中的内容
            $res = $this->moduleModel->install($module['id']);

            if ($res === true) {
                if($aNav){
                    $channel['title']=$module['alias'];
                    $channel['url']=$module['entry'];
                    $channel['sort']=100;
                    $channel['status']=1;
                    $channel['icon']=$module['icon'];
                    M('Channel')->add($channel);
                    S('common_nav',null);
                }


                $this->success('安装模块成功。', U('lists'));
            } else {
                $this->error('安装模块失败。' .$this->moduleModel->getError());
            }


        } else {
            $builder = new AdminConfigBuilder();


            $builder->title($module['alias'] . '——模块安装向导');

            $builder->keyId()->keyReadOnly('name', '模块名')->keyText('alias', '模块中文名')->keyReadOnly('version', '版本')
                ->keyText('icon', '图标')
                ->keyTextArea('summary', '模块介绍')
                ->keyReadOnly('developer', '开发者')
                ->keyText('entry', '前台入口')
                ->keyText('admin_entry', '后台入口');

//, 'repair' => '修复模式'修复模式不会导入模块专用数据表，只导入菜单、权限、行为、行为限制
            $builder->keyRadio('mode', '安装模式', '', array('install' => '覆盖安装模式'));
            if($module['entry']){
                $builder->keyBool('add_nav','添加导航','安装后自动在导航栏中加入菜单，或者<a target="_blank" href="'.U('channel/index').'">手动设置</a>');
            }

         /*   $builder->keyRadio('add_nav','添加导航菜单','默认不会添加导航',array(1=>'不添加',2=>'添加'));*/
            $builder->group('安装选项', 'mode,add_nav');
           /* $builder->group('模块信息', 'id,name,alias,version,icon,summary,developer,entry,admin_entry');*/


            $module['mode'] = 'install';
            $module['add_nav'] = '1';
            $builder->data($module);
            $builder->buttonSubmit();
            $builder->buttonBack();
            $builder->display();
        }


        /*  */


    }

} 