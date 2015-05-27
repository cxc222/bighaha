-- -----------------------------
-- 表结构 `ocenter_appstore_developer`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_developer` (
  `uid` int(11) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `des` varchar(5000) NOT NULL,
  `goodat` varchar(200) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `refuse_message` tinyint(4) NOT NULL COMMENT ' 拒绝接收下载消息 ',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='开发者资料表';


-- -----------------------------
-- 表结构 `ocenter_appstore_goods`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '名称',
  `summary` text NOT NULL COMMENT '摘要，简介',
  `cover` int(11) NOT NULL COMMENT '封面',
  `uid` int(11) NOT NULL COMMENT '作者',
  `compat` varchar(100) NOT NULL COMMENT '兼容版本',
  `rate` int(11) NOT NULL COMMENT '打分，评级，1-10',
  `view_count` int(11) NOT NULL COMMENT '浏览量',
  `type_id` int(11) NOT NULL COMMENT '分类',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `entity` tinyint(4) NOT NULL COMMENT '1.插件 2.模块 3.主题 4.服务',
  `author` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='商品基础表';


-- -----------------------------
-- 表结构 `ocenter_appstore_module`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_module` (
  `id` int(11) NOT NULL COMMENT '与goods表id统一',
  `rely` varchar(20) NOT NULL COMMENT '依赖模块',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_appstore_plugin`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_plugin` (
  `id` int(11) NOT NULL COMMENT '与goods统一',
  `hook` varchar(50) NOT NULL COMMENT '用到的钩子',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';


-- -----------------------------
-- 表结构 `ocenter_appstore_resource`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_resource` (
  `id` int(11) NOT NULL COMMENT '与goods的id对应',
  `screensnap` varchar(100) NOT NULL COMMENT '截图，id，逗号分隔',
  `latest_version` varchar(20) NOT NULL COMMENT '最新的版本号',
  `etitle` varchar(50) NOT NULL COMMENT '英文名',
  `instruction` varchar(5000) NOT NULL COMMENT '使用说明',
  `total_download_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_appstore_theme`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_theme` (
  `id` int(11) NOT NULL,
  `color` varchar(50) NOT NULL COMMENT '颜色搭配，色系',
  `style` varchar(50) NOT NULL COMMENT '设计风格',
  `fit` varchar(50) NOT NULL COMMENT '适用网站',
  `is_response` tinyint(4) NOT NULL COMMENT '是否支持响应式',
  `response_cover` int(11) NOT NULL COMMENT '响应式截图',
  `compatible` varchar(100) NOT NULL COMMENT '兼容的浏览器',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='主题表';


-- -----------------------------
-- 表结构 `ocenter_appstore_type`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `entity` tinyint(4) NOT NULL COMMENT '1.插件 2.模块 3.主题 4.服务',
  `status` tinyint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='商品分类表';


-- -----------------------------
-- 表结构 `ocenter_appstore_version`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_appstore_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '版本号',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `log` varchar(500) NOT NULL COMMENT '更新日志',
  `status` tinyint(4) NOT NULL,
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `goods_id` int(11) NOT NULL COMMENT '对应的商品ID',
  `fee` decimal(20,2) NOT NULL COMMENT '费用',
  `pack` int(11) NOT NULL,
  `download_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COMMENT='版本表';

