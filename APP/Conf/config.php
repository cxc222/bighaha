<?php
return array(
	//'配置项'=>'配置值'
	'APP_GROUP_LIST' => 'Index,Admin',
	'DEFAULT_GROUP' => 'Index',
	'APP_GROUP_MODE' => 1 ,
	'APP_GROUP_PATH' => 'Modules',
	'DB_PREFIX'=>'shangfox_', //设置表前缀
	'SHOW_PAGE_TRACE'=> false,
	'DB_DSN'=>'mysql://root:z3787582@112.124.42.201:3306/gaoxiao',//使用DSN方式配置数据库信息

	'LOAD_EXT_CONFIG' => 'verify,water,webconfig', //载入验证码,水印的配置文件

	'VAR_FILTERS'=>'filter_default',

	//配置路由
	'URL_MODEL' => 2,
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => array(
		'/^(\d+)$/' => 'Index/Article/index?id=:1', 
		//'/^gif-list-(\d+)$/' => 'Index/GifList/index?id=:1', 
		'/^(\w+)$/' => 'Index/List/index?cate=:1', 
		//'/^(\d+)$/' => 'Index/Singlepage/index?id=:1', //单页文档
		'/^Html\/(\w+)$/' => 'Index/Singlepage/index?filename=:1', //单页文档
	),
);
?>
