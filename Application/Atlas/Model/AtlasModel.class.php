<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Atlas\Model;
use Think\Model;
/**
 * 活动模型
 * Class EventModel
 * @package Event\Model
 * autor:xjw129xjt
 */
class AtlasModel extends Model{
    protected $_validate = array(
    	array('uid', 'require', '缺少发布者', self::MUST_VALIDATE ),
    	array('image_id', 'require', '缺少图片', self::MUST_VALIDATE ),
        array('content', '1,200', '内容长度1-200字', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('addtime', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
        array('uid', 'is_login',3, 'function'),
    );
    
}
