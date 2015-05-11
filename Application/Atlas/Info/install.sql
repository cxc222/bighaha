CREATE TABLE IF NOT EXISTS `opensns_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '发布者uid',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `image_id` int(11) NOT NULL COMMENT '关联图片id',
  `tag` varchar(80) NOT NULL COMMENT '标签',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态, 0=需要审核,1=通过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='搞笑图集';



/* 表的结构 `big_atlas_like` */

CREATE TABLE IF NOT EXISTS `opensns_atlas_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atlas_id` int(11) NOT NULL COMMENT '图集id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `type` int(11) NOT NULL COMMENT '类型, 1=支持,2=不支持',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='喜欢关系表';

/* 表的结构 `big_atlas_config` */

CREATE TABLE IF NOT EXISTS `opensns_atlas_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(126) NOT NULL COMMENT '名称',
  `value` varchar(255) NOT NULL COMMENT '配置值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图集配置';

/* menu 插入 */

INSERT INTO `opensns_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '图集', 0, 22, 'atlas/index', 0, '', '', 0);

set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where title = '图集';

INSERT INTO `opensns_menu` ( `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '内容管理', @tmp_id, 0, 'atlas/index', 0, '', '内容管理', 0);
