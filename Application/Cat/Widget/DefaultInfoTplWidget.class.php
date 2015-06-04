<?php
namespace Cat\Widget;
use Think\Controller;
class DefaultInfoTplWidget extends Controller
{
    public function render($data)
    {

        $data['entity'] = D('cat_entity')->find($data['info']['entity_id']);
        $data['data'] = D('Data')->getByInfoId($data['info']['id']);
        $data['user'] =query_user(array('nickname','space_url','avatar64','avatar128'),$data['info']['uid']);
        $data['user']['info_count']=D('cat_info')->where('uid='.$data['info']['uid'])->count();
        $map['info_id'] = $data['info']['info_id'];
       // $data['info']['com'] = D('Com')->getList($map, 5);
        $data['mid'] = is_login();
        $this->assign($data);
        $content =$this->fetch('Widget/DefaultInfoTpl/tpl');
        return $content;
    }
}