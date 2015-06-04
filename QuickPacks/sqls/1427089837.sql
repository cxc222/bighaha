--
-- 表的结构 `ocenter_invite`
--

DROP TABLE IF EXISTS `ocenter_invite`;
CREATE TABLE IF NOT EXISTS `ocenter_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY',
  `invite_type` int(11) NOT NULL COMMENT '邀请类型id',
  `code` varchar(25) NOT NULL COMMENT '邀请码',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `can_num` int(10) NOT NULL COMMENT '可以注册用户（含升级）',
  `already_num` int(10) NOT NULL COMMENT '已经注册用户（含升级）',
  `end_time` int(11) NOT NULL COMMENT '有效期至',
  `status` tinyint(2) NOT NULL COMMENT '0：已用完，1：还可注册，2：用户取消邀请，-1：管理员删除',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邀请码表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ocenter_invite`
--

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_buy_log`
--

DROP TABLE IF EXISTS `ocenter_invite_buy_log`;
CREATE TABLE IF NOT EXISTS `ocenter_invite_buy_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY',
  `invite_type` int(11) NOT NULL COMMENT '邀请类型id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `num` int(10) NOT NULL COMMENT '可邀请名额',
  `content` varchar(200) NOT NULL COMMENT '记录信息',
  `create_time` int(11) NOT NULL COMMENT '创建时间（做频率用）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户购买邀请名额记录' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ocenter_invite_buy_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_log`
--

DROP TABLE IF EXISTS `ocenter_invite_log`;
CREATE TABLE IF NOT EXISTS `ocenter_invite_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `inviter_id` int(11) NOT NULL COMMENT '邀请人id',
  `invite_id` int(11) NOT NULL COMMENT '邀请码id',
  `content` varchar(200) NOT NULL COMMENT '记录内容',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邀请注册成功记录表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ocenter_invite_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_type`
--

DROP TABLE IF EXISTS `ocenter_invite_type`;
CREATE TABLE IF NOT EXISTS `ocenter_invite_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY',
  `title` varchar(25) NOT NULL COMMENT '标题',
  `length` int(10) NOT NULL DEFAULT '11' COMMENT '验证码长度',
  `time` varchar(50) NOT NULL COMMENT '有效时长，带单位的时间',
  `cycle_num` int(10) NOT NULL COMMENT '周期内可购买个数',
  `cycle_time` varchar(50) NOT NULL COMMENT '周期时长，带单位的时间',
  `roles` varchar(50) NOT NULL COMMENT '绑定角色ids',
  `auth_groups` varchar(50) NOT NULL COMMENT '允许购买的用户组ids',
  `pay_score` int(10) NOT NULL COMMENT '购买消耗积分',
  `pay_score_type` int(11) NOT NULL COMMENT '购买消耗积分类型',
  `income_score` int(10) NOT NULL COMMENT '每邀请成功一个用户，邀请者增加积分',
  `income_score_type` int(11) NOT NULL COMMENT '邀请成功后增加积分类型id',
  `is_follow` tinyint(2) NOT NULL COMMENT '邀请成功后是否互相关注',
  `status` tinyint(2) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邀请注册码类型表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ocenter_invite_type`
--

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_user_info`
--

DROP TABLE IF EXISTS `ocenter_invite_user_info`;
CREATE TABLE IF NOT EXISTS `ocenter_invite_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY',
  `invite_type` int(11) NOT NULL COMMENT '邀请类型id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `num` int(11) NOT NULL COMMENT '可邀请名额',
  `already_num` int(11) NOT NULL COMMENT '已邀请名额',
  `success_num` int(11) NOT NULL COMMENT '成功邀请名额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='邀请注册用户信息' AUTO_INCREMENT=1 ;