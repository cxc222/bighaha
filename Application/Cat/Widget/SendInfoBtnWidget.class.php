<?php
namespace Cat\Widget;
use Think\Controller;
class SendInfoBtnWidget extends Controller
{
    public function render($data)
    {
        if (!$data['entity']['can_rec']) {
            return '';
        }
        $data['mid'] = is_login();
        if ($data['entity']['rec_entity'] == 0) {
            $data['send_entitys'] = D('cat_entity')->select();
        } else {
            $data['send_entitys'] = D('cat_entity')->where('id in (' . $data['entity']['rec_entity'] . ')')->order('sort desc')->select();
        }
        $data['first_entity_info'] = D('Info')->getList('entity_id=' . $data['send_entitys'][0]['id'] . ' and uid=' . is_login() .' and status=1', 8);

        $this->assign($data);
        $this->display('Widget/SendInfoBtn/tpl');
    }
}