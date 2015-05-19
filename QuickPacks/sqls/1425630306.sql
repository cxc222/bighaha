ALTER TABLE  `ocenter_field` ADD  `role_id` INT( 11 ) NOT NULL AFTER  `id`;
ALTER TABLE  `ocenter_member` ADD  `last_login_role` INT( 11 ) NOT NULL AFTER  `last_login_ip`;
ALTER TABLE  `ocenter_member` ADD  `show_role` INT( 11 ) NOT NULL COMMENT  '个人主页显示角色' AFTER  `last_login_role`;

--
-- 表的结构 `ocenter_role`
--

DROP TABLE IF EXISTS `ocenter_role`;
CREATE TABLE IF NOT EXISTS `ocenter_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL COMMENT '角色组id',
  `name` varchar(25) NOT NULL COMMENT '英文标识',
  `title` varchar(25) NOT NULL COMMENT '中文标题',
  `description` varchar(500) NOT NULL COMMENT '描述',
  `user_groups` varchar(200) NOT NULL COMMENT '默认用户组ids',
  `invite` tinyint(4) NOT NULL COMMENT '预留字段(类型：是否需要邀请注册等)',
  `audit` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否需要审核',
  `sort` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_role_config`
--

DROP TABLE IF EXISTS `ocenter_role_config`;
CREATE TABLE IF NOT EXISTS `ocenter_role_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL COMMENT '标识',
  `category` varchar(25) NOT NULL COMMENT '归类标识',
  `value` text NOT NULL COMMENT '配置值',
  `data` text NOT NULL COMMENT '该配置的其它值',
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='角色配置表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_role_group`
--

DROP TABLE IF EXISTS `ocenter_role_group`;
CREATE TABLE IF NOT EXISTS `ocenter_role_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COMMENT='角色分组' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_role`
--

DROP TABLE IF EXISTS `ocenter_user_role`;
CREATE TABLE IF NOT EXISTS `ocenter_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '2：未审核，1:启用，0：禁用，-1：删除',
  `step` varchar(50) NOT NULL COMMENT '记录当前执行步骤',
  `init` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否初始化',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户角色关联' AUTO_INCREMENT=1 ;

REPLACE INTO `ocenter_role` (`id`, `group_id`, `name`, `title`, `description`, `user_groups`, `invite`, `audit`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, 0, 'default', '普通用户', '普通用户', '1', 0, 0, 0, 1, 1426664188, 1426741608);
REPLACE INTO `ocenter_user_role` (`id`, `uid`, `role_id`, `status`, `step`, `init`) VALUES
(1, 1, 1, 1, 'finish', 1);
UPDATE `ocenter_member` SET `show_role`=1 WHERE `uid`=1;
