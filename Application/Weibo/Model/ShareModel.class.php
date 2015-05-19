<?php
namespace Weibo\Model;

use Think\Model;
use Think\Hook;


class ShareModel extends Model
{
    public function getInfo($param)
    {
        $info = D($param['app'].'/'.$param['model'])->$param['method']($param['id']);
        return $info;
    }

}