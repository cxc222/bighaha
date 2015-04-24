CREATE TABLE IF NOT EXISTS `opensns_ucenter_user_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uc_uid` int(11) NOT NULL,
  `uc_username` varchar(50) NOT NULL,
  `uc_email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;