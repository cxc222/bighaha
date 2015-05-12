<?php
/**
 * 基础不动配置
 * 
 */
return array(
		/* 数据库配置 */
		'DB_TYPE' => 'mysqli', // 数据库类型
		'DB_HOST' => '112.124.42.201', // 服务器地址
		'DB_NAME' => 'gaoxiao2', // 数据库名
		'DB_USER' => 'gaoxiao2', // 用户名
		'DB_PWD' => 'z3787582', // 密码
		'DB_PORT' => '3306', // 端口
		'DB_PREFIX' => 'big_', // 数据库表前缀
		
		/* 'DB_TYPE' => 'mysqli', // 数据库类型
		'DB_HOST' => 'localhost', // 服务器地址
		'DB_NAME' => 'gaoxiao', // 数据库名
		'DB_USER' => 'root', // 用户名
		'DB_PWD' => 'z3787582', // 密码
		'DB_PORT' => '3306', // 端口
		'DB_PREFIX' => 'big_', // 数据库表前缀 */
		
		/* 七牛上传相关配置 */
		'QINIU_CONFIG' => array (
				'accessKey' => 'WPWs-mQSibJXZd7m_kL_cM0hwTIMCyFjzvgTFeRq', // 七牛 accessKey
				'secrectKey' => 'TTUZUuWL8jug5LzxtQGwCPuVmN8-9DXMeFSrDzBa', // 七牛 secrectKey
				'bucket' => 'wedding', // 七牛 空间名
				'domain' => '7xi9zc.com1.z0.glb.clouddn.com'  // 七牛资源域名
		)
);