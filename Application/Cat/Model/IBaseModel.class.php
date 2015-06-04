<?php
namespace Cat\Model;

use Think\Model;

/**实体接口
 * Class IBaseModel
 */
interface IBaseModel
{
    function getList($map = '', $num = 10, $order = 'cTime desc');

    function getLimit($map = '', $num = 10, $order = 'cTime desc');

    function getById($id);
}