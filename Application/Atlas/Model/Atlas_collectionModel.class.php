<?php
/**
 * 采集列表, 可以采集的对象
 * 
 */
namespace Atlas\Model;
use Think\Model;
class Atlas_collectionModel extends Model{
    protected $_validate = array(
        array('name', 'require', '缺少采集名称', self::MUST_VALIDATE ),
        array('url', 'require', '缺少采集URL', self::MUST_VALIDATE )
    );

    protected $_auto = array(
        array('addtime', NOW_TIME, self::MODEL_INSERT),
    );

    public function editData($data)
    {
        if($data['id']){
            $res=$this->save($data);
        }else{
            $data['addtime']=time();
            $res=$this->add($data);
            action_log('add_atlas_collection', 'Atlas', $res, is_login());
        }
        return $res;
    }
}