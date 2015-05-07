<?php

namespace Admin\Model;

use Think\Model;

/**
 * 采集模型
 * 
 * @author zff
 */
class CollectionModel extends Model {
	public $pathDir = 'C:/Users/Administrator/Documents/火车头';
	
	protected $connection = array (
			'db_type' => 'mysql',
			'db_user' => 'root',
			'db_pwd' => '',
			'db_host' => 'localhost',
			'db_port' => '3306',
			'db_name' => 'locoy',
			'db_charset' => 'utf8' 
	);
	
	protected $trueTableName = 'item';
	
}