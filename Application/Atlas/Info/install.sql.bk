-- -----------------------------
-- 表结构 `big_atlas`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '发布者uid',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `image_id` int(11) NOT NULL COMMENT '关联图片id',
  `tag` varchar(80) NOT NULL COMMENT '标签',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态, 0=需要审核,1=通过',
  `like_count` int(11) NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `unlike_count` int(11) NOT NULL DEFAULT '0' COMMENT '不喜欢数',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论内容',
  `is_recommend` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否推荐, 0:没有, 1:推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='搞笑图集';


-- -----------------------------
-- 表结构 `big_atlas_collection`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '采集名称',
  `url` varchar(128) NOT NULL COMMENT '采集地址',
  `page` int(11) NOT NULL DEFAULT '1' COMMENT '页码',
  `start_id` int(11) NOT NULL DEFAULT '0' COMMENT '开始采集Id',
  `end_id` int(11) NOT NULL DEFAULT '0' COMMENT '结束采集id',
  `className` varchar(26) NOT NULL COMMENT '类名',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='可以采集列表';


-- -----------------------------
-- 表结构 `big_atlas_config`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(126) NOT NULL COMMENT '名称',
  `value` varchar(255) NOT NULL COMMENT '配置值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图集配置';


-- -----------------------------
-- 表结构 `big_atlas_like`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atlas_id` int(11) NOT NULL COMMENT '图集id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `type` int(11) NOT NULL COMMENT '类型, 1=支持,2=不支持',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='喜欢关系表';

-- -----------------------------
-- 表内记录 `big_atlas_collection`
-- -----------------------------
INSERT INTO `big_atlas_collection` VALUES ('1', '百思不得姐', 'http://www.budejie.com/', '1', '14484340', '14570351', 'budejie', '1432473113');
