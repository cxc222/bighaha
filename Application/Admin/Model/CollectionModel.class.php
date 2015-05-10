<?php

namespace Admin\Model;

use Think\Model;

/**
 * 采集模型
 * 
 * @author zff
 */
class CollectionModel extends Model {
	public $pathDir = 'C:/Users/Administrator/Documents/locoy';
	
	protected $connection = array (
			'db_type' => 'mysql',
			'db_user' => 'locoy',
			'db_pwd' => 'z3787582',
			'db_host' => '112.124.42.201',
			'db_port' => '3306',
			'db_name' => 'locoy',
			'db_charset' => 'utf8' 
	);
	
	protected $trueTableName = 'item';
	
}