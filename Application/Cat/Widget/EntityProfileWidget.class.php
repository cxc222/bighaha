<?php
namespace Cat\Widget;
use Think\Controller;
/**实体单属性输出部件
 * Class EntityProfileWidget
 */
class EntityProfileWidget extends Controller
{
    public function render($data)
    {
        if (isset($data['name'])) {
            $map['name'] = $data['name'];
        }
        if (isset($data['entity_id'])) {
            $map['id'] = $data['entity_id'];
        }
        $entity =D('cat_entity')->where($map)->find();

        echo  $entity[$data['p_name']];
    }
}