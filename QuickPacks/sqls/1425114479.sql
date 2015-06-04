
INSERT INTO `ocenter_menu` ( `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`) VALUES
( '积分类型列表', 16, 0, 'User/scoreList', 0, '', '行为管理', 0, ''),
( '新增/编辑类型', 16, 0, 'user/editScoreType', 1, '', '行为管理', 0, ''),
( '充值积分', 16, 0, 'user/recharge', 1, '', '用户管理', 0, '');


ALTER TABLE  `ocenter_member` ADD  `score1` FLOAT NOT NULL COMMENT  'score1' AFTER  `pos_community`;
ALTER TABLE  `ocenter_member` ADD  `score2` FLOAT NOT NULL COMMENT  'score2' AFTER  `pos_community`;
ALTER TABLE  `ocenter_member` ADD  `score3` FLOAT NOT NULL COMMENT  'score3' AFTER  `pos_community`;
ALTER TABLE  `ocenter_member` ADD  `score4` FLOAT NOT NULL COMMENT  'score4' AFTER  `pos_community`;


CREATE TABLE IF NOT EXISTS `ocenter_ucenter_score_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `unit` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
