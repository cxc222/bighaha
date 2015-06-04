<?php
namespace Store\Model;

use Think\Model;
interface IBaseModel {
    function getList($map = '', $num = 10, $order = 'cTime desc');
    function getLimit($map = '', $num = 10, $order = 'cTime desc');
    function getById($id);
}