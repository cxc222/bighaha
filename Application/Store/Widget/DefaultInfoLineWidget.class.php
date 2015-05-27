<?php
namespace Store\Widget;
use Think\Controller;
/**
 * Created by JetBrains PhpStorm.
 * User: 95
 * Date: 13-7-13
 * Time: 下午8:42
 * To change this template use File | Settings | File Templates.
 */
class DefaultInfoLineWidget extends Controller
{
    public function render($data)
    {
        $data['vo'] = $data['info'];
        // dump($data['vo']);exit;
        $data['vo']['user'] =query_user(array('nickname','avatar64','space_url'),$data['vo']['uid']);
        /*  if ($data['type'] == 'list') {
              foreach ($data['infos']['data'] as $key => $vo) {
                  $data['infos']['data'][$key]['user'] = D('User')->getUserInfo($vo['uid']);
              }
          } else {
              foreach ($data['infos'] as $key => $vo) {
                  $data['infos'][$key]['user'] = D('User')->getUserInfo($vo['uid']);
              }
          }*/

        $this->assign($data);
        $content =$this->fetch('Widget/DefaultInfoLine/tpl');
        return $content;
    }
}