<?php
/**
 * 所属项目 cox.
 * 开发者: 陈一枭
 * 创建日期: 8/5/14
 * 创建时间: 4:52 PM
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Appstore\Model;


interface BaseModel
{
    public function getList($map = array(), $limit = 10, $order = 'id desc', $more = 0, $field = '*');
    public function getById($id);
} 