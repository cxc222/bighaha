--
-- 表的结构 `ocenter_user_tag`
--

DROP TABLE IF EXISTS `ocenter_user_tag`;
CREATE TABLE IF NOT EXISTS `ocenter_user_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `sort` tinyint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签分类表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `ocenter_user_tag`
--

INSERT INTO `ocenter_user_tag` (`id`, `title`, `status`, `pid`, `sort`) VALUES
(1, '默认', 1, 0, 0),
(2, '开发者', 1, 1, 0),
(3, '站长', 1, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_tag_link`
--

DROP TABLE IF EXISTS `ocenter_user_tag_link`;
CREATE TABLE IF NOT EXISTS `ocenter_user_tag_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tags` varchar(200) NOT NULL COMMENT '标签ids',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户标签关联表' AUTO_INCREMENT=1 ;