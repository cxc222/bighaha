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