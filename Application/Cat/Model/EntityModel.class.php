<?php
namespace Cat\Model;

use Think\Model;

/**实体模型
 * Class EntityModel
 */
class EntityModel extends Model implements IBaseModel
{
    function getList($map = '', $num = 10, $order = 'cTime desc')
    {
    }

    function getLimit($map = '', $num = 10, $order = 'cTime desc')
    {
    }

    function getById($id)
    {
    }
}