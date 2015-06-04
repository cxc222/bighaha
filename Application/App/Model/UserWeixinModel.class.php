<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-29
 * Time: 下午5:22
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace App\Model;

use Think\Model;

class UserWeixinModel extends Model
{
    /* 用户模型自动完成 */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    public function bindUser($data=array()){
        $data=$this->create($data);
        $result=$this->add($data);
        if($result) return $result;
        return false;
    }
    public function unbindUser($data=array()){
        $result=$this->where($data)->delete();
        if($result) return $result;
        return false;
    }
    public function alreadyBind($uid){
        $map['uid']=$uid;
        $result=$this->where($map)->find();
        return count($result)?1:0;
    }
} 