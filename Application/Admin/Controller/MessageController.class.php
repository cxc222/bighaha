<?php
namespace Admin\Controller;
use Admin\Builder\AdminConfigBuilder;


/**
 * Class MessageController  消息控制器
 * @package Admin\Controller
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class MessageController extends AdminController
{

    public function index()
    {
        $admin_config = new AdminConfigBuilder();
        $data = $admin_config->handleConfig();

        $admin_config->title('消息群发')

            ->keyText('NEW_USER_FANS', '新用户粉丝', '输入用户id，多个用户以‘,’分割')
            ->keyText('NEW_USER_FRIENDS', '新用户好友', '输入用户id，多个用户以‘,’分割')

            ->buttonSubmit('', '保存')->data($data);
        $admin_config->display();
    }
}
