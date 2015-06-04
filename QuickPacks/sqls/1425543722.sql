--
-- 表的结构 `ocenter_user_config`
--

DROP TABLE IF EXISTS `ocenter_user_config`;
CREATE TABLE IF NOT EXISTS `ocenter_user_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `model` varchar(30) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户配置信息表' AUTO_INCREMENT=1 ;