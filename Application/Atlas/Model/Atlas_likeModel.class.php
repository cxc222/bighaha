<?php
namespace Atlas\Model;
use Think\Model;
class Atlas_likeModel extends Model{
	protected $_validate = array(
			array('uid', 'require', '缺少发布者', self::MUST_VALIDATE ),
			array('atlas_id', 'require', '缺少图集ID', self::MUST_VALIDATE )
	);
	
	protected $_auto = array(
			array('create_time', NOW_TIME, self::MODEL_INSERT),
			array('uid', 'is_login',3, 'function')
	);
}