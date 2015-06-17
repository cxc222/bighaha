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
    	array('uid', 'require', '缺少发布者', self::EXISTS_VALIDATE ),
    	//array('image_id', 'require', '缺少图片', self::MUST_VALIDATE ),
        array('content', '1,200', '内容长度1-200字', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('addtime', NOW_TIME, self::MODEL_INSERT),
        array('status', '0', self::MODEL_INSERT),
        array('uid', 'is_login',3, 'function'),
    );

    public function getListByPage($map,$page=1,$order='addtime desc',$field='*',$r=20)
    {
        require_once "Application/Atlas/Common/function.php";
        $totalCount=$this->where($map)->count();
        if($totalCount){
            $list=$this->where($map)->page($page,$r)->order($order)->field($field)->select();
            foreach($list as $k => $v){
                //处理一下图片显示问题
                if($v['type'] == 1){
                    $list[$k]['image'] = getAtlasQiniuImageById($v['image_id'],1,'gravity/Center/crop/120x120');
                }
            }
        }
        return array($list,$totalCount);
    }
}
