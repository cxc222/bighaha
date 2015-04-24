SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `opensns_action`;
CREATE TABLE IF NOT EXISTS `opensns_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表' AUTO_INCREMENT=17 ;

INSERT INTO `opensns_action` (`id`, `name`, `title`, `remark`, `rule`, `log`, `type`, `status`, `update_time`) VALUES
(1, 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user|get_nickname]在[time|time_format]登录了后台', 1, 1, 1387181220),
(2, 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', 2, 0, 1380173180),
(3, 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', 2, 1, 1383285646),
(4, 'add_document', '发表文档', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', '[user|get_nickname]在[time|time_format]发表了一个微博。\r\n表[model]，记录编号[record]。', 1, 0, 1394866289),
(5, 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', 2, 0, 1383285551),
(6, 'update_config', '更新配置', '新增或修改或删除配置', '', '', 1, 1, 1383294988),
(7, 'update_model', '更新模型', '新增或修改模型', '', '', 1, 1, 1383295057),
(8, 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', 1, 1, 1383295963),
(9, 'update_channel', '更新导航', '新增或修改或删除导航', '', '', 1, 1, 1383296301),
(10, 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', 1, 1, 1383296392),
(11, 'update_category', '更新分类', '新增或修改或删除分类', '', '', 1, 1, 1383296765),
(13, 'add_weibo', '发微博', '积分+2，金币+1，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+2|cycle:24|max:5|tox_money_rule:tox_money+1|tox_money_field:tox_money', '', 1, 1, 1408935799),
(14, 'add_weibo_comment', '微博评论', '积分+1，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+1|cycle:24|max:5', '', 1, 1, 1396342907),
(15, 'add_post', '发帖子', '积分+3，金币+1，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+3|cycle:24|max:5|tox_money_rule:tox_money+1|tox_money_field:tox_money', '', 1, 1, 1408935783),
(16, 'add_post_reply', '发帖子回复', '积分+1，每天上限5次，', 'table:member|field:score|condition:uid={$self}|rule:score+1|cycle:24|max:5', '', 1, 1, 1408935759);

DROP TABLE IF EXISTS `opensns_action_log`;
CREATE TABLE IF NOT EXISTS `opensns_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表' AUTO_INCREMENT=7179 ;

DROP TABLE IF EXISTS `opensns_addons`;
CREATE TABLE IF NOT EXISTS `opensns_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='插件表' AUTO_INCREMENT=94 ;

INSERT INTO `opensns_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`) VALUES
(15, 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', 1, '{"editor_type":"2","editor_wysiwyg":"1","editor_height":"500px","editor_resize_type":"1"}', 'thinkphp', '0.1', 1383126253, 0),
(2, 'SiteStat', '站点统计信息', '统计站点的基础信息', 1, '{"title":"\\u7cfb\\u7edf\\u4fe1\\u606f","width":"1","display":"1","status":"0"}', 'thinkphp', '0.1', 1379512015, 0),
(89, 'DevTeam', '开发团队信息', '开发团队成员信息', 1, '{"title":"OpenSNS\\u5f00\\u53d1\\u56e2\\u961f","width":"2","display":"1"}', 'thinkphp', '0.1', 1409038881, 0),
(4, 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', 1, '{"title":"\\u7cfb\\u7edf\\u4fe1\\u606f","width":"2","display":"1"}', 'thinkphp', '0.1', 1379512036, 0),
(5, 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', 1, '{"editor_type":"2","editor_wysiwyg":"1","editor_height":"300px","editor_resize_type":"1"}', 'thinkphp', '0.1', 1379830910, 0),
(6, 'Attachment', '附件', '用于文档模型上传附件', 1, 'null', 'thinkphp', '0.1', 1379842319, 1),
(9, 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', 1, '{"comment_type":"1","comment_uid_youyan":"","comment_short_name_duoshuo":"","comment_data_list_duoshuo":""}', 'thinkphp', '0.1', 1380273962, 0),
(16, 'Avatar', '头像插件', '用于头像的上传', 1, '{"random":"1"}', 'caipeichao', '0.1', 1394449710, 1),
(49, 'Checkin', '签到', '签到积分', 1, '{"random":"1"}', '想天软件工作室', '0.1', 1403764341, 1),
(58, 'SyncLogin', '同步登陆', '同步登陆', 1, '{"type":null,"meta":"","bind":"0","QqKEY":"","QqSecret":"","SinaKEY":"","SinaSecret":""}', 'xjw129xjt', '0.1', 1406598876, 0),
(41, 'LocalComment', '本地评论', '本地评论插件，不依赖社会化评论平台', 1, '{"can_guest_comment":"1"}', 'caipeichao', '0.1', 1399440324, 0),
(44, 'InsertImage', '插入图片', '微博上传图片', 1, 'null', '想天软件工作室', '0.1', 1402390777, 0),
(48, 'Repost', '转发', '转发', 1, 'null', '想天软件工作室', '0.1', 1403763025, 0),
(63, 'Advertising', '广告位置', '广告位插件', 1, 'null', 'onep2p', '0.1', 1406689090, 1),
(64, 'Advs', '广告管理', '广告插件', 1, 'null', 'onep2p', '0.1', 1406689131, 1),
(68, 'ImageSlider', '图片轮播', '图片轮播，需要先通过 http://www.onethink.cn/topic/2153.html 的方法，让配置支持多图片上传', 1, '{"second":"3000","direction":"horizontal","imgWidth":"760","imgHeight":"350","url":"","images":"92,93,94"}', 'birdy', '0.1', 1407144129, 0),
(70, 'SuperLinks', '合作单位', '合作单位', 1, '{"random":"1"}', '苏南 newsn.net', '0.1', 1407156572, 1),
(91, 'Rank_checkin', '签到排名', '设置每天某一时刻开始 按时间先后 签到排名，取前十', 1, '{"random":"1","ranktime":null}', '嘉兴想天信息科技有限公司', '0.1', 1409109841, 1),
(84, 'Support', '赞', '赞的功能', 1, 'null', '嘉兴想天信息科技有限公司', '0.1', 1408499141, 0);

DROP TABLE IF EXISTS `opensns_advertising`;
CREATE TABLE IF NOT EXISTS `opensns_advertising` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '广告位置名称',
  `type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '广告位置展示方式  0为默认展示一张',
  `width` char(20) NOT NULL DEFAULT '' COMMENT '广告位置宽度',
  `height` char(20) NOT NULL DEFAULT '' COMMENT '广告位置高度',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `pos` varchar(50) NOT NULL,
  `s` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告位置表' AUTO_INCREMENT=4 ;

INSERT INTO `opensns_advertising` (`id`, `title`, `type`, `width`, `height`, `status`, `pos`, `s`) VALUES
(1, '微博发布框下方', 2, '620', '87', 1, 'weibo_below_sendbox', 0),
(2, '微博首页签到排行下方', 4, '', '', 1, 'weibo_below_checkrank', 0),
(3, '首页1号广告位', 1, '756', '100', 1, 'home_ad1', 0);

DROP TABLE IF EXISTS `opensns_advs`;
CREATE TABLE IF NOT EXISTS `opensns_advs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '广告名称',
  `position` int(11) NOT NULL COMMENT '广告位置',
  `advspic` int(11) NOT NULL COMMENT '图片地址',
  `advstext` text NOT NULL COMMENT '文字广告内容',
  `advshtml` text NOT NULL COMMENT '代码广告内容',
  `link` char(140) NOT NULL DEFAULT '' COMMENT '链接地址',
  `level` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告表' AUTO_INCREMENT=8 ;

DROP TABLE IF EXISTS `opensns_attachment`;
CREATE TABLE IF NOT EXISTS `opensns_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `opensns_attribute`;
CREATE TABLE IF NOT EXISTS `opensns_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL,
  `validate_time` tinyint(1) unsigned NOT NULL,
  `error_info` varchar(100) NOT NULL,
  `validate_type` varchar(25) NOT NULL,
  `auto_rule` varchar(100) NOT NULL,
  `auto_time` tinyint(1) unsigned NOT NULL,
  `auto_type` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模型属性表' AUTO_INCREMENT=34 ;

INSERT INTO `opensns_attribute` (`id`, `name`, `title`, `field`, `type`, `value`, `remark`, `is_show`, `extra`, `model_id`, `is_must`, `status`, `update_time`, `create_time`, `validate_rule`, `validate_time`, `error_info`, `validate_type`, `auto_rule`, `auto_time`, `auto_type`) VALUES
(1, 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', 0, '', 1, 0, 1, 1384508362, 1383891233, '', 0, '', '', '', 0, ''),
(2, 'name', '标识', 'char(40) NOT NULL ', 'string', '', '同一根节点下标识不重复', 1, '', 1, 0, 1, 1383894743, 1383891233, '', 0, '', '', '', 0, ''),
(3, 'title', '标题', 'char(80) NOT NULL ', 'string', '', '文档标题', 1, '', 1, 0, 1, 1383894778, 1383891233, '', 0, '', '', '', 0, ''),
(4, 'category_id', '所属分类', 'int(10) unsigned NOT NULL ', 'string', '', '', 0, '', 1, 0, 1, 1384508336, 1383891233, '', 0, '', '', '', 0, ''),
(5, 'description', '描述', 'char(140) NOT NULL ', 'textarea', '', '', 1, '', 1, 0, 1, 1383894927, 1383891233, '', 0, '', '', '', 0, ''),
(6, 'root', '根节点', 'int(10) unsigned NOT NULL ', 'num', '0', '该文档的顶级文档编号', 0, '', 1, 0, 1, 1384508323, 1383891233, '', 0, '', '', '', 0, ''),
(7, 'pid', '所属ID', 'int(10) unsigned NOT NULL ', 'num', '0', '父文档编号', 0, '', 1, 0, 1, 1384508543, 1383891233, '', 0, '', '', '', 0, ''),
(8, 'model_id', '内容模型ID', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '该文档所对应的模型', 0, '', 1, 0, 1, 1384508350, 1383891233, '', 0, '', '', '', 0, ''),
(9, 'type', '内容类型', 'tinyint(3) unsigned NOT NULL ', 'select', '2', '', 1, '1:目录\r\n2:主题\r\n3:段落', 1, 0, 1, 1384511157, 1383891233, '', 0, '', '', '', 0, ''),
(10, 'position', '推荐位', 'smallint(5) unsigned NOT NULL ', 'checkbox', '0', '多个推荐则将其推荐值相加', 1, '1:列表推荐\r\n2:频道页推荐\r\n4:首页推荐', 1, 0, 1, 1383895640, 1383891233, '', 0, '', '', '', 0, ''),
(11, 'link_id', '外链', 'int(10) unsigned NOT NULL ', 'num', '0', '0-非外链，大于0-外链ID,需要函数进行链接与编号的转换', 1, '', 1, 0, 1, 1383895757, 1383891233, '', 0, '', '', '', 0, ''),
(12, 'cover_id', '封面', 'int(10) unsigned NOT NULL ', 'picture', '0', '0-无封面，大于0-封面图片ID，需要函数处理', 1, '', 1, 0, 1, 1384147827, 1383891233, '', 0, '', '', '', 0, ''),
(13, 'display', '可见性', 'tinyint(3) unsigned NOT NULL ', 'radio', '1', '', 1, '0:不可见\r\n1:所有人可见', 1, 0, 1, 1386662271, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(14, 'deadline', '截至时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '0-永久有效', 1, '', 1, 0, 1, 1387163248, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(15, 'attach', '附件数量', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '', 0, '', 1, 0, 1, 1387260355, 1383891233, '', 0, '', 'regex', '', 0, 'function'),
(16, 'view', '浏览量', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 1, 0, 1, 1383895835, 1383891233, '', 0, '', '', '', 0, ''),
(17, 'comment', '评论数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 1, 0, 1, 1383895846, 1383891233, '', 0, '', '', '', 0, ''),
(18, 'extend', '扩展统计字段', 'int(10) unsigned NOT NULL ', 'num', '0', '根据需求自行使用', 0, '', 1, 0, 1, 1384508264, 1383891233, '', 0, '', '', '', 0, ''),
(19, 'level', '优先级', 'int(10) unsigned NOT NULL ', 'num', '0', '越高排序越靠前', 1, '', 1, 0, 1, 1383895894, 1383891233, '', 0, '', '', '', 0, ''),
(20, 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 1, '', 1, 0, 1, 1383895903, 1383891233, '', 0, '', '', '', 0, ''),
(21, 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', 0, '', 1, 0, 1, 1384508277, 1383891233, '', 0, '', '', '', 0, ''),
(22, 'status', '数据状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', 0, '-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿', 1, 0, 1, 1384508496, 1383891233, '', 0, '', '', '', 0, ''),
(23, 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 0, '0:html\r\n1:ubb\r\n2:markdown', 2, 0, 1, 1384511049, 1383891243, '', 0, '', '', '', 0, ''),
(24, 'content', '文章内容', 'text NOT NULL ', 'editor', '', '', 1, '', 2, 0, 1, 1383896225, 1383891243, '', 0, '', '', '', 0, ''),
(25, 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '参照display方法参数的定义', 1, '', 2, 0, 1, 1383896190, 1383891243, '', 0, '', '', '', 0, ''),
(26, 'bookmark', '收藏数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 2, 0, 1, 1383896103, 1383891243, '', 0, '', '', '', 0, ''),
(27, 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', 0, '0:html\r\n1:ubb\r\n2:markdown', 3, 0, 1, 1387260461, 1383891252, '', 0, '', 'regex', '', 0, 'function'),
(28, 'content', '下载详细描述', 'text NOT NULL ', 'editor', '', '', 1, '', 3, 0, 1, 1383896438, 1383891252, '', 0, '', '', '', 0, ''),
(29, 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '', 1, '', 3, 0, 1, 1383896429, 1383891252, '', 0, '', '', '', 0, ''),
(30, 'file_id', '文件ID', 'int(10) unsigned NOT NULL ', 'file', '0', '需要函数处理', 1, '', 3, 0, 1, 1383896415, 1383891252, '', 0, '', '', '', 0, ''),
(31, 'download', '下载次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', 1, '', 3, 0, 1, 1383896380, 1383891252, '', 0, '', '', '', 0, ''),
(32, 'size', '文件大小', 'bigint(20) unsigned NOT NULL ', 'num', '0', '单位bit', 1, '', 3, 0, 1, 1383896371, 1383891252, '', 0, '', '', '', 0, ''),
(33, 'zx', '附件', 'int(10) UNSIGNED NOT NULL', 'file', '', '', 1, '', 2, 0, 1, 1395988634, 1395988634, '', 3, '', 'regex', '', 3, 'function');

DROP TABLE IF EXISTS `opensns_auth_extend`;
CREATE TABLE IF NOT EXISTS `opensns_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

INSERT INTO `opensns_auth_extend` (`group_id`, `extend_id`, `type`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 2, 1),
(1, 2, 2),
(1, 3, 1),
(1, 3, 2),
(1, 4, 1),
(1, 37, 1);

DROP TABLE IF EXISTS `opensns_auth_group`;
CREATE TABLE IF NOT EXISTS `opensns_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` text NOT NULL COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `opensns_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`) VALUES
(1, 'admin', 1, '普通用户', '', 1, ',298,292,295,343,341,338');

DROP TABLE IF EXISTS `opensns_auth_group_access`;
CREATE TABLE IF NOT EXISTS `opensns_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `opensns_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(1, 3);

DROP TABLE IF EXISTS `opensns_auth_rule`;
CREATE TABLE IF NOT EXISTS `opensns_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=346 ;

INSERT INTO `opensns_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
(1, 'admin', 2, 'Admin/Index/index', '首页', 1, ''),
(2, 'admin', 2, 'Admin/Article/mydocument', '资讯', 1, ''),
(3, 'admin', 2, 'Admin/User/index', '用户', 1, ''),
(4, 'admin', 2, 'Admin/Addons/index', '扩展', 1, ''),
(5, 'admin', 2, 'Admin/Config/group', '系统', 1, ''),
(7, 'admin', 1, 'Admin/article/add', '新增', 1, ''),
(8, 'admin', 1, 'Admin/article/edit', '编辑', 1, ''),
(9, 'admin', 1, 'Admin/article/setStatus', '改变状态', 1, ''),
(10, 'admin', 1, 'Admin/article/update', '保存', 1, ''),
(11, 'admin', 1, 'Admin/article/autoSave', '保存草稿', 1, ''),
(12, 'admin', 1, 'Admin/article/move', '移动', 1, ''),
(13, 'admin', 1, 'Admin/article/copy', '复制', 1, ''),
(14, 'admin', 1, 'Admin/article/paste', '粘贴', 1, ''),
(15, 'admin', 1, 'Admin/article/permit', '还原', 1, ''),
(16, 'admin', 1, 'Admin/article/clear', '清空', 1, ''),
(17, 'admin', 1, 'Admin/article/index', '文档列表', 1, ''),
(18, 'admin', 1, 'Admin/article/recycle', '回收站', 1, ''),
(19, 'admin', 1, 'Admin/User/addaction', '新增用户行为', 1, ''),
(20, 'admin', 1, 'Admin/User/editaction', '编辑用户行为', 1, ''),
(21, 'admin', 1, 'Admin/User/saveAction', '保存用户行为', 1, ''),
(22, 'admin', 1, 'Admin/User/setStatus', '变更行为状态', 1, ''),
(23, 'admin', 1, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', 1, ''),
(24, 'admin', 1, 'Admin/User/changeStatus?method=resumeUser', '启用会员', 1, ''),
(25, 'admin', 1, 'Admin/User/changeStatus?method=deleteUser', '删除会员', 1, ''),
(26, 'admin', 1, 'Admin/User/index', '用户信息', 1, ''),
(27, 'admin', 1, 'Admin/User/action', '用户行为', 1, ''),
(28, 'admin', 1, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', 1, ''),
(29, 'admin', 1, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', 1, ''),
(30, 'admin', 1, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', 1, ''),
(31, 'admin', 1, 'Admin/AuthManager/createGroup', '新增', 1, ''),
(32, 'admin', 1, 'Admin/AuthManager/editGroup', '编辑', 1, ''),
(33, 'admin', 1, 'Admin/AuthManager/writeGroup', '保存用户组', 1, ''),
(34, 'admin', 1, 'Admin/AuthManager/group', '授权', 1, ''),
(35, 'admin', 1, 'Admin/AuthManager/access', '访问授权', 1, ''),
(36, 'admin', 1, 'Admin/AuthManager/user', '成员授权', 1, ''),
(37, 'admin', 1, 'Admin/AuthManager/removeFromGroup', '解除授权', 1, ''),
(38, 'admin', 1, 'Admin/AuthManager/addToGroup', '保存成员授权', 1, ''),
(39, 'admin', 1, 'Admin/AuthManager/category', '分类授权', 1, ''),
(40, 'admin', 1, 'Admin/AuthManager/addToCategory', '保存分类授权', 1, ''),
(41, 'admin', 1, 'Admin/AuthManager/index', '权限管理', 1, ''),
(42, 'admin', 1, 'Admin/Addons/create', '创建', 1, ''),
(43, 'admin', 1, 'Admin/Addons/checkForm', '检测创建', 1, ''),
(44, 'admin', 1, 'Admin/Addons/preview', '预览', 1, ''),
(45, 'admin', 1, 'Admin/Addons/build', '快速生成插件', 1, ''),
(46, 'admin', 1, 'Admin/Addons/config', '设置', 1, ''),
(47, 'admin', 1, 'Admin/Addons/disable', '禁用', 1, ''),
(48, 'admin', 1, 'Admin/Addons/enable', '启用', 1, ''),
(49, 'admin', 1, 'Admin/Addons/install', '安装', 1, ''),
(50, 'admin', 1, 'Admin/Addons/uninstall', '卸载', 1, ''),
(51, 'admin', 1, 'Admin/Addons/saveconfig', '更新配置', 1, ''),
(52, 'admin', 1, 'Admin/Addons/adminList', '插件后台列表', 1, ''),
(53, 'admin', 1, 'Admin/Addons/execute', 'URL方式访问插件', 1, ''),
(54, 'admin', 1, 'Admin/Addons/index', '插件管理', 1, ''),
(55, 'admin', 1, 'Admin/Addons/hooks', '钩子管理', 1, ''),
(56, 'admin', 1, 'Admin/model/add', '新增', -1, ''),
(57, 'admin', 1, 'Admin/model/edit', '编辑', -1, ''),
(58, 'admin', 1, 'Admin/model/setStatus', '改变状态', -1, ''),
(59, 'admin', 1, 'Admin/model/update', '保存数据', -1, ''),
(60, 'admin', 1, 'Admin/Model/index', '模型管理', -1, ''),
(61, 'admin', 1, 'Admin/Config/edit', '编辑', 1, ''),
(62, 'admin', 1, 'Admin/Config/del', '删除', 1, ''),
(63, 'admin', 1, 'Admin/Config/add', '新增', 1, ''),
(64, 'admin', 1, 'Admin/Config/save', '保存', 1, ''),
(65, 'admin', 1, 'Admin/Config/group', '网站设置', 1, ''),
(66, 'admin', 1, 'Admin/Config/index', '配置管理', 1, ''),
(67, 'admin', 1, 'Admin/Channel/add', '新增', 1, ''),
(68, 'admin', 1, 'Admin/Channel/edit', '编辑', 1, ''),
(69, 'admin', 1, 'Admin/Channel/del', '删除', 1, ''),
(70, 'admin', 1, 'Admin/Channel/index', '导航管理', 1, ''),
(71, 'admin', 1, 'Admin/Category/edit', '编辑', 1, ''),
(72, 'admin', 1, 'Admin/Category/add', '新增', 1, ''),
(73, 'admin', 1, 'Admin/Category/remove', '删除', 1, ''),
(74, 'admin', 1, 'Admin/Category/index', '分类管理', 1, ''),
(75, 'admin', 1, 'Admin/file/upload', '上传控件', -1, ''),
(76, 'admin', 1, 'Admin/file/uploadPicture', '上传图片', -1, ''),
(77, 'admin', 1, 'Admin/file/download', '下载', -1, ''),
(94, 'admin', 1, 'Admin/AuthManager/modelauth', '模型授权', 1, ''),
(79, 'admin', 1, 'Admin/article/batchOperate', '导入', 1, ''),
(80, 'admin', 1, 'Admin/Database/index?type=export', '备份数据库', 1, ''),
(81, 'admin', 1, 'Admin/Database/index?type=import', '还原数据库', 1, ''),
(82, 'admin', 1, 'Admin/Database/export', '备份', 1, ''),
(83, 'admin', 1, 'Admin/Database/optimize', '优化表', 1, ''),
(84, 'admin', 1, 'Admin/Database/repair', '修复表', 1, ''),
(86, 'admin', 1, 'Admin/Database/import', '恢复', 1, ''),
(87, 'admin', 1, 'Admin/Database/del', '删除', 1, ''),
(88, 'admin', 1, 'Admin/User/add', '新增用户', 1, ''),
(89, 'admin', 1, 'Admin/Attribute/index', '属性管理', -1, ''),
(90, 'admin', 1, 'Admin/Attribute/add', '新增', -1, ''),
(91, 'admin', 1, 'Admin/Attribute/edit', '编辑', -1, ''),
(92, 'admin', 1, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(93, 'admin', 1, 'Admin/Attribute/update', '保存数据', -1, ''),
(95, 'admin', 1, 'Admin/AuthManager/addToModel', '保存模型授权', 1, ''),
(96, 'admin', 1, 'Admin/Category/move', '移动', -1, ''),
(97, 'admin', 1, 'Admin/Category/merge', '合并', -1, ''),
(98, 'admin', 1, 'Admin/Config/menu', '后台菜单管理', -1, ''),
(99, 'admin', 1, 'Admin/Article/mydocument', '内容', -1, ''),
(100, 'admin', 1, 'Admin/Menu/index', '菜单管理', 1, ''),
(101, 'admin', 1, 'Admin/other', '其他', -1, ''),
(102, 'admin', 1, 'Admin/Menu/add', '新增', 1, ''),
(103, 'admin', 1, 'Admin/Menu/edit', '编辑', 1, ''),
(104, 'admin', 1, 'Admin/Think/lists?model=article', '文章管理', -1, ''),
(105, 'admin', 1, 'Admin/Think/lists?model=download', '下载管理', 1, ''),
(106, 'admin', 1, 'Admin/Think/lists?model=config', '配置管理', 1, ''),
(107, 'admin', 1, 'Admin/Action/actionlog', '行为日志', 1, ''),
(108, 'admin', 1, 'Admin/User/updatePassword', '修改密码', 1, ''),
(109, 'admin', 1, 'Admin/User/updateNickname', '修改昵称', 1, ''),
(110, 'admin', 1, 'Admin/action/edit', '查看行为日志', 1, ''),
(205, 'admin', 1, 'Admin/think/add', '新增数据', 1, ''),
(111, 'admin', 2, 'Admin/article/index', '文档列表', -1, ''),
(112, 'admin', 2, 'Admin/article/add', '新增', -1, ''),
(113, 'admin', 2, 'Admin/article/edit', '编辑', -1, ''),
(114, 'admin', 2, 'Admin/article/setStatus', '改变状态', -1, ''),
(115, 'admin', 2, 'Admin/article/update', '保存', -1, ''),
(116, 'admin', 2, 'Admin/article/autoSave', '保存草稿', -1, ''),
(117, 'admin', 2, 'Admin/article/move', '移动', -1, ''),
(118, 'admin', 2, 'Admin/article/copy', '复制', -1, ''),
(119, 'admin', 2, 'Admin/article/paste', '粘贴', -1, ''),
(120, 'admin', 2, 'Admin/article/batchOperate', '导入', -1, ''),
(121, 'admin', 2, 'Admin/article/recycle', '回收站', -1, ''),
(122, 'admin', 2, 'Admin/article/permit', '还原', -1, ''),
(123, 'admin', 2, 'Admin/article/clear', '清空', -1, ''),
(124, 'admin', 2, 'Admin/User/add', '新增用户', -1, ''),
(125, 'admin', 2, 'Admin/User/action', '用户行为', -1, ''),
(126, 'admin', 2, 'Admin/User/addAction', '新增用户行为', -1, ''),
(127, 'admin', 2, 'Admin/User/editAction', '编辑用户行为', -1, ''),
(128, 'admin', 2, 'Admin/User/saveAction', '保存用户行为', -1, ''),
(129, 'admin', 2, 'Admin/User/setStatus', '变更行为状态', -1, ''),
(130, 'admin', 2, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', -1, ''),
(131, 'admin', 2, 'Admin/User/changeStatus?method=resumeUser', '启用会员', -1, ''),
(132, 'admin', 2, 'Admin/User/changeStatus?method=deleteUser', '删除会员', -1, ''),
(133, 'admin', 2, 'Admin/AuthManager/index', '权限管理', -1, ''),
(134, 'admin', 2, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', -1, ''),
(135, 'admin', 2, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', -1, ''),
(136, 'admin', 2, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', -1, ''),
(137, 'admin', 2, 'Admin/AuthManager/createGroup', '新增', -1, ''),
(138, 'admin', 2, 'Admin/AuthManager/editGroup', '编辑', -1, ''),
(139, 'admin', 2, 'Admin/AuthManager/writeGroup', '保存用户组', -1, ''),
(140, 'admin', 2, 'Admin/AuthManager/group', '授权', -1, ''),
(141, 'admin', 2, 'Admin/AuthManager/access', '访问授权', -1, ''),
(142, 'admin', 2, 'Admin/AuthManager/user', '成员授权', -1, ''),
(143, 'admin', 2, 'Admin/AuthManager/removeFromGroup', '解除授权', -1, ''),
(144, 'admin', 2, 'Admin/AuthManager/addToGroup', '保存成员授权', -1, ''),
(145, 'admin', 2, 'Admin/AuthManager/category', '分类授权', -1, ''),
(146, 'admin', 2, 'Admin/AuthManager/addToCategory', '保存分类授权', -1, ''),
(147, 'admin', 2, 'Admin/AuthManager/modelauth', '模型授权', -1, ''),
(148, 'admin', 2, 'Admin/AuthManager/addToModel', '保存模型授权', -1, ''),
(149, 'admin', 2, 'Admin/Addons/create', '创建', -1, ''),
(150, 'admin', 2, 'Admin/Addons/checkForm', '检测创建', -1, ''),
(151, 'admin', 2, 'Admin/Addons/preview', '预览', -1, ''),
(152, 'admin', 2, 'Admin/Addons/build', '快速生成插件', -1, ''),
(153, 'admin', 2, 'Admin/Addons/config', '设置', -1, ''),
(154, 'admin', 2, 'Admin/Addons/disable', '禁用', -1, ''),
(155, 'admin', 2, 'Admin/Addons/enable', '启用', -1, ''),
(156, 'admin', 2, 'Admin/Addons/install', '安装', -1, ''),
(157, 'admin', 2, 'Admin/Addons/uninstall', '卸载', -1, ''),
(158, 'admin', 2, 'Admin/Addons/saveconfig', '更新配置', -1, ''),
(159, 'admin', 2, 'Admin/Addons/adminList', '插件后台列表', -1, ''),
(160, 'admin', 2, 'Admin/Addons/execute', 'URL方式访问插件', -1, ''),
(161, 'admin', 2, 'Admin/Addons/hooks', '钩子管理', -1, ''),
(162, 'admin', 2, 'Admin/Model/index', '模型管理', -1, ''),
(163, 'admin', 2, 'Admin/model/add', '新增', -1, ''),
(164, 'admin', 2, 'Admin/model/edit', '编辑', -1, ''),
(165, 'admin', 2, 'Admin/model/setStatus', '改变状态', -1, ''),
(166, 'admin', 2, 'Admin/model/update', '保存数据', -1, ''),
(167, 'admin', 2, 'Admin/Attribute/index', '属性管理', -1, ''),
(168, 'admin', 2, 'Admin/Attribute/add', '新增', -1, ''),
(169, 'admin', 2, 'Admin/Attribute/edit', '编辑', -1, ''),
(170, 'admin', 2, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(171, 'admin', 2, 'Admin/Attribute/update', '保存数据', -1, ''),
(172, 'admin', 2, 'Admin/Config/index', '配置管理', -1, ''),
(173, 'admin', 2, 'Admin/Config/edit', '编辑', -1, ''),
(174, 'admin', 2, 'Admin/Config/del', '删除', -1, ''),
(175, 'admin', 2, 'Admin/Config/add', '新增', -1, ''),
(176, 'admin', 2, 'Admin/Config/save', '保存', -1, ''),
(177, 'admin', 2, 'Admin/Menu/index', '菜单管理', -1, ''),
(178, 'admin', 2, 'Admin/Channel/index', '导航管理', -1, ''),
(179, 'admin', 2, 'Admin/Channel/add', '新增', -1, ''),
(180, 'admin', 2, 'Admin/Channel/edit', '编辑', -1, ''),
(181, 'admin', 2, 'Admin/Channel/del', '删除', -1, ''),
(182, 'admin', 2, 'Admin/Category/index', '分类管理', -1, ''),
(183, 'admin', 2, 'Admin/Category/edit', '编辑', -1, ''),
(184, 'admin', 2, 'Admin/Category/add', '新增', -1, ''),
(185, 'admin', 2, 'Admin/Category/remove', '删除', -1, ''),
(186, 'admin', 2, 'Admin/Category/move', '移动', -1, ''),
(187, 'admin', 2, 'Admin/Category/merge', '合并', -1, ''),
(188, 'admin', 2, 'Admin/Database/index?type=export', '备份数据库', -1, ''),
(189, 'admin', 2, 'Admin/Database/export', '备份', -1, ''),
(190, 'admin', 2, 'Admin/Database/optimize', '优化表', -1, ''),
(191, 'admin', 2, 'Admin/Database/repair', '修复表', -1, ''),
(192, 'admin', 2, 'Admin/Database/index?type=import', '还原数据库', -1, ''),
(193, 'admin', 2, 'Admin/Database/import', '恢复', -1, ''),
(194, 'admin', 2, 'Admin/Database/del', '删除', -1, ''),
(195, 'admin', 2, 'Admin/other', '其他', 1, ''),
(196, 'admin', 2, 'Admin/Menu/add', '新增', -1, ''),
(197, 'admin', 2, 'Admin/Menu/edit', '编辑', -1, ''),
(198, 'admin', 2, 'Admin/Think/lists?model=article', '应用', -1, ''),
(199, 'admin', 2, 'Admin/Think/lists?model=download', '下载管理', -1, ''),
(200, 'admin', 2, 'Admin/Think/lists?model=config', '应用', -1, ''),
(201, 'admin', 2, 'Admin/Action/actionlog', '行为日志', -1, ''),
(202, 'admin', 2, 'Admin/User/updatePassword', '修改密码', -1, ''),
(203, 'admin', 2, 'Admin/User/updateNickname', '修改昵称', -1, ''),
(204, 'admin', 2, 'Admin/action/edit', '查看行为日志', -1, ''),
(206, 'admin', 1, 'Admin/think/edit', '编辑数据', 1, ''),
(207, 'admin', 1, 'Admin/Menu/import', '导入', 1, ''),
(208, 'admin', 1, 'Admin/Model/generate', '生成', 1, ''),
(209, 'admin', 1, 'Admin/Addons/addHook', '新增钩子', 1, ''),
(210, 'admin', 1, 'Admin/Addons/edithook', '编辑钩子', 1, ''),
(211, 'admin', 1, 'Admin/Article/sort', '文档排序', 1, ''),
(212, 'admin', 1, 'Admin/Config/sort', '排序', 1, ''),
(213, 'admin', 1, 'Admin/Menu/sort', '排序', 1, ''),
(214, 'admin', 1, 'Admin/Channel/sort', '排序', 1, ''),
(215, 'admin', 1, 'Admin/Category/operate/type/move', '移动', 1, ''),
(216, 'admin', 1, 'Admin/Category/operate/type/merge', '合并', 1, ''),
(217, 'admin', 1, 'Admin/Forum/forum', '板块管理', 1, ''),
(218, 'admin', 1, 'Admin/Forum/post', '帖子管理', 1, ''),
(219, 'admin', 1, 'Admin/Forum/editForum', '编辑／创建板块', 1, ''),
(220, 'admin', 1, 'Admin/Forum/editPost', '编辑帖子', 1, ''),
(221, 'admin', 2, 'Admin//Admin/Forum/index', '讨论区', -1, ''),
(222, 'admin', 2, 'Admin//Admin/Weibo/index', '微博', -1, ''),
(223, 'admin', 1, 'Admin/Forum/sortForum', '排序', 1, ''),
(224, 'admin', 1, 'Admin/SEO/editRule', '新增、编辑', 1, ''),
(225, 'admin', 1, 'Admin/SEO/sortRule', '排序', 1, ''),
(226, 'admin', 1, 'Admin/SEO/index', '规则管理', 1, ''),
(227, 'admin', 1, 'Admin/Forum/editReply', '新增/编辑回复', 1, ''),
(228, 'admin', 1, 'Admin/Weibo/editComment', '编辑回复', 1, ''),
(229, 'admin', 1, 'Admin/Weibo/editWeibo', '编辑微博', 1, ''),
(230, 'admin', 1, 'Admin/SEO/ruleTrash', '规则回收站', 1, ''),
(231, 'admin', 1, 'Admin/Rank/userList', '查看用户', 1, ''),
(232, 'admin', 1, 'Admin/Rank/userRankList', '用户头衔列表', 1, ''),
(233, 'admin', 1, 'Admin/Rank/userAddRank', '关联新头衔', 1, ''),
(234, 'admin', 1, 'Admin/Rank/userChangeRank', '编辑头衔关联', 1, ''),
(235, 'admin', 1, 'Admin/Issue/add', '编辑专辑', 1, ''),
(236, 'admin', 1, 'Admin/Issue/issue', '专辑管理', 1, ''),
(237, 'admin', 1, 'Admin/Issue/operate', '专辑操作', 1, ''),
(238, 'admin', 1, 'Admin/Weibo/weibo', '微博管理', 1, ''),
(239, 'admin', 1, 'Admin/Rank/index', '头衔列表', 1, ''),
(240, 'admin', 1, 'Admin/Forum/forumTrash', '板块回收站', 1, ''),
(241, 'admin', 1, 'Admin/Weibo/weiboTrash', '微博回收站', 1, ''),
(242, 'admin', 1, 'Admin/Rank/editRank', '添加头衔', 1, ''),
(243, 'admin', 1, 'Admin/Weibo/comment', '回复管理', 1, ''),
(244, 'admin', 1, 'Admin/Forum/postTrash', '帖子回收站', 1, ''),
(245, 'admin', 1, 'Admin/Weibo/commentTrash', '回复回收站', 1, ''),
(246, 'admin', 1, 'Admin/Issue/issueTrash', '专辑回收站', 1, ''),
(247, 'admin', 1, 'Admin//Admin/Forum/reply', '回复管理', -1, ''),
(248, 'admin', 1, 'Admin/Forum/replyTrash', '回复回收站', 1, ''),
(249, 'admin', 2, 'Admin/Forum/index', '论坛', 1, ''),
(250, 'admin', 2, 'Admin/Weibo/weibo', '微博', 1, ''),
(251, 'admin', 2, 'Admin/SEO/index', 'SEO', -1, ''),
(252, 'admin', 2, 'Admin/Rank/index', '头衔', -1, ''),
(253, 'admin', 2, 'Admin/Issue/issue', '专辑', 1, ''),
(254, 'admin', 1, 'Admin/Issue/contents', '内容管理', 1, ''),
(255, 'admin', 1, 'Admin/User/profile', '扩展资料', 1, ''),
(256, 'admin', 1, 'Admin/User/editProfile', '添加、编辑分组', 1, ''),
(257, 'admin', 1, 'Admin/User/sortProfile', '分组排序', 1, ''),
(258, 'admin', 1, 'Admin/User/field', '字段列表', 1, ''),
(259, 'admin', 1, 'Admin/User/editFieldSetting', '添加、编辑字段', 1, ''),
(260, 'admin', 1, 'Admin/User/sortField', '字段排序', 1, ''),
(261, 'admin', 1, 'Admin/Update/quick', '全部补丁', 1, ''),
(262, 'admin', 1, 'Admin/Update/addpack', '新增补丁', 1, ''),
(263, 'admin', 1, 'Admin/User/expandinfo_select', '用户扩展资料列表', 1, ''),
(264, 'admin', 1, 'Admin/User/expandinfo_details', '扩展资料详情', 1, ''),
(265, 'admin', 1, 'Admin/Shop/shopLog', '商城信息记录', -1, ''),
(266, 'admin', 1, 'Admin/Shop/setStatus', '商品分类状态设置', -1, ''),
(267, 'admin', 1, 'Admin/Shop/setGoodsStatus', '商品状态设置', -1, ''),
(268, 'admin', 1, 'Admin/Shop/operate', '商品分类操作', -1, ''),
(269, 'admin', 1, 'Admin/Shop/add', '商品分类添加', -1, ''),
(270, 'admin', 1, 'Admin/Shop/goodsEdit', '添加、编辑商品', -1, ''),
(271, 'admin', 1, 'Admin/Shop/hotSellConfig', '热销商品阀值配置', -1, ''),
(272, 'admin', 1, 'Admin/Shop/setNew', '设置新品', -1, ''),
(273, 'admin', 1, 'Admin/EventType/index', '活动分类管理', -1, ''),
(274, 'admin', 1, 'Admin/Event/event', '内容管理', -1, ''),
(275, 'admin', 1, 'Admin/EventType/eventTypeTrash', '活动分类回收站', -1, ''),
(276, 'admin', 1, 'Admin/Event/verify', '内容审核', -1, ''),
(277, 'admin', 1, 'Admin/Event/contentTrash', '内容回收站', -1, ''),
(278, 'admin', 1, 'Admin/Rank/rankVerify', '待审核用户头衔', 1, ''),
(279, 'admin', 1, 'Admin/Rank/rankVerifyFailure', '被驳回的头衔申请', 1, ''),
(280, 'admin', 1, 'Admin/Weibo/config', '微博设置', 1, ''),
(281, 'admin', 1, 'Admin/Issue/verify', '内容审核', 1, ''),
(282, 'admin', 1, 'Admin/Shop/goodsList', '商品列表', -1, ''),
(283, 'admin', 1, 'Admin/Shop/shopCategory', '商品分类配置', -1, ''),
(284, 'admin', 1, 'Admin/Shop/categoryTrash', '商品分类回收站', -1, ''),
(285, 'admin', 1, 'Admin/Shop/verify', '待发货交易', -1, ''),
(286, 'admin', 1, 'Admin/Issue/contentTrash', '内容回收站', 1, ''),
(287, 'admin', 1, 'Admin/Shop/goodsBuySuccess', '交易成功记录', -1, ''),
(288, 'admin', 1, 'Admin/Shop/goodsTrash', '商品回收站', -1, ''),
(289, 'admin', 1, 'Admin/Shop/toxMoneyConfig', '货币配置', -1, ''),
(290, 'admin', 2, 'Admin/Shop/shopCategory', '商城', -1, ''),
(291, 'admin', 2, 'Admin/EventType/index', '活动', -1, ''),
(341, 'Weibo', 1, 'beTopicAdmin', '抢先成为话题主持人', 1, ''),
(340, 'Weibo', 1, 'setWeiboTop', '微博置顶', 1, ''),
(339, 'Weibo', 1, 'deleteWeibo', '删除微博', 1, ''),
(338, 'Weibo', 1, 'sendWeibo', '发微博', 1, ''),
(297, 'Home', 1, 'deleteLocalComment', '删除本地评论', 1, ''),
(343, 'Issue', 1, 'addIssueContent', '专辑投稿权限', 1, ''),
(342, 'Weibo', 1, 'manageTopic', '管理话题', 1, ''),
(300, 'admin', 1, 'Admin/Issue/config', '专辑设置', 1, ''),
(301, 'admin', 1, 'Admin/Event/config', '活动设置', -1, ''),
(302, 'admin', 1, 'Admin/User/config', '基础设置', 1, ''),
(303, 'admin', 1, 'admin/group/group', '群组管理', -1, ''),
(304, 'admin', 1, 'Admin/group/groupType', '分类管理', -1, ''),
(305, 'admin', 1, 'Admin/group/postType', '文章分类', -1, ''),
(306, 'admin', 1, 'Admin/group/editPostCate', '修改分类', -1, ''),
(307, 'admin', 1, 'Admin/group/sortPostCate', '类型排序', -1, ''),
(308, 'admin', 1, 'Admin/group/editGroupType', '修改群组分类', -1, ''),
(309, 'admin', 1, 'Admin/group/sortGroupType', '群组类型排序', -1, ''),
(310, 'admin', 1, 'Admin/group/editLzlReply', '编辑楼中楼回复', -1, ''),
(311, 'admin', 1, 'Admin/group/lzlreply', '楼中楼回复', -1, ''),
(312, 'admin', 1, 'Admin/group/lzlreplyTrash', '楼中楼回复回收站', -1, ''),
(313, 'admin', 1, 'Admin/group/editReply', '编辑回复', -1, ''),
(314, 'admin', 1, 'Admin/group/groupTrash', '群组回收站', -1, ''),
(315, 'admin', 1, 'Admin/group/post', '文章管理', -1, ''),
(316, 'admin', 1, 'Admin/group/postTrash', '文章回收站', -1, ''),
(317, 'admin', 1, 'Admin/group/reply', '回复管理', -1, ''),
(318, 'admin', 1, 'Admin/group/replyTrash', '回复回收站', -1, ''),
(319, 'admin', 1, 'Admin/group/config', '群组设置', -1, ''),
(320, 'admin', 1, 'Admin/group/unverify', '未审核群组', -1, ''),
(321, 'admin', 1, 'Admin/module/uninstall', '卸载模块', 1, ''),
(322, 'admin', 1, 'Admin/module/lists', '模块管理', 1, ''),
(323, 'admin', 1, 'Admin/AuthManager/addNode', '新增权限节点', 1, ''),
(324, 'admin', 1, 'Admin/AuthManager/accessUser', '前台权限管理', 1, ''),
(325, 'admin', 1, 'Admin/User/changeGroup', '转移用户组', 1, ''),
(326, 'admin', 1, 'Admin/AuthManager/deleteNode', '删除权限节点', 1, ''),
(327, 'admin', 1, 'admin/expression/expressionList', '表情列表', 1, ''),
(328, 'admin', 1, 'Admin/adimn/expression/add', '新增表情包', 1, ''),
(329, 'admin', 1, 'Admin/Forum/addType', '新增/编辑分类', 1, ''),
(330, 'admin', 1, 'Admin/Forum/setTypeStatus', '设置分类状态', 1, ''),
(331, 'admin', 1, 'Admin/Forum/type', '分类管理', 1, ''),
(332, 'admin', 1, 'Admin/module/install', '模块安装', 1, ''),
(333, 'admin', 1, 'Admin/Forum/reply', '回复管理', 1, ''),
(334, 'admin', 1, 'Admin/Forum/config', '论坛设置', 1, ''),
(335, 'admin', 2, 'admin/group/index', '群组', -1, ''),
(336, 'admin', 1, 'admin/expression/index', '表情管理', 1, ''),
(337, 'admin', 2, 'Admin/module/lists', '云平台', 1, ''),
(344, 'Issue', 1, 'editIssueContent', '编辑专辑内容（管理）', 1, ''),
(345, 'admin', 1, 'Admin/Issue/setIssueContentStatus', '设置专辑状态', 1, '');

DROP TABLE IF EXISTS `opensns_avatar`;
CREATE TABLE IF NOT EXISTS `opensns_avatar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_temp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `opensns_category`;
CREATE TABLE IF NOT EXISTS `opensns_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '关联模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text NOT NULL COMMENT '扩展设置',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  `icon` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类表' AUTO_INCREMENT=45 ;

INSERT INTO `opensns_category` (`id`, `name`, `title`, `pid`, `sort`, `list_row`, `meta_title`, `keywords`, `description`, `template_index`, `template_lists`, `template_detail`, `template_edit`, `model`, `type`, `link_id`, `allow_publish`, `display`, `reply`, `check`, `reply_model`, `extend`, `create_time`, `update_time`, `status`, `icon`) VALUES
(1, 'blog', '资讯', 0, 0, 10, '', '', '', '', '', '', '', '2', '2,1', 0, 0, 1, 0, 0, '1', '', 1379474947, 1406206595, 1, 0),
(2, 'default_blog', 'APP', 1, 1, 10, '', '', '', '', '', '', '', '2', '2,1,3', 0, 1, 1, 0, 1, '1', '', 1379475028, 1406171627, 1, 31),
(40, 'b3', '大数据', 1, 0, 10, '', '', '', '', '', '', '', '2', '2', 0, 1, 1, 1, 0, '', '', 1406169194, 1406183525, 1, 0),
(41, 'php', 'PHP', 1, 0, 10, '', '', '', '', '', '', '', '2', '2', 0, 1, 1, 1, 0, '', '', 1406171638, 1406171693, 1, 0),
(42, 'create_yeah', '创业', 0, 0, 10, '', '', '', '', '', '', '', '2', '', 0, 1, 1, 1, 0, '', '', 1406172024, 1406172024, 1, 0);

DROP TABLE IF EXISTS `opensns_channel`;
CREATE TABLE IF NOT EXISTS `opensns_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  `color` varchar(30) NOT NULL,
  `band_color` varchar(30) NOT NULL,
  `band_text` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

INSERT INTO `opensns_channel` (`id`, `pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`, `color`, `band_color`, `band_text`) VALUES
(1, 0, '首页', 'Weibo/Index/index', 1, 1379475111, 1401628204, 1, 0, '', '', ''),
(2, 0, '论坛', 'Forum/Index/index', 2, 1379475131, 1409662841, 1, 0, '', '', ''),
(4, 0, '专辑', 'Issue/Index/index', 123, 1399022492, 1406255246, 1, 0, '', '', ''),
(5, 0, '会员', 'People/Index/index', 5, 1399784340, 1406256451, 1, 0, '', '', ''),
(11, 0, '商城', 'Shop/Index/index', 5, 1403056971, 1403085891, 1, 0, '', '', ''),
(14, 0, '资讯', 'blog/index/index', 4, 1406084715, 1406084910, 1, 0, '', '', ''),
(13, 0, '活动', '/Event/index', 3, 1403797042, 1403797164, 1, 0, '', '', ''),
(15, 0, '群组', 'group/index/index', 5, 1410771228, 1410771228, 1, 0, '', '', '');

DROP TABLE IF EXISTS `opensns_check_info`;
CREATE TABLE IF NOT EXISTS `opensns_check_info` (
  `uid` int(11) DEFAULT NULL,
  `con_num` int(11) DEFAULT '1',
  `total_num` int(11) DEFAULT '1',
  `ctime` int(11) DEFAULT '0',
  `total_score` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `opensns_config`;
CREATE TABLE IF NOT EXISTS `opensns_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

INSERT INTO `opensns_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(4, 'WEB_SITE_CLOSE', 4, '关闭站点', 1, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1379235296, 1, '1', 1),
(9, 'CONFIG_TYPE_LIST', 3, '配置类型列表', 4, '', '主要用于数据解析和页面表单的生成', 1378898976, 1379235348, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', 8),
(10, 'WEB_SITE_ICP', 1, '网站备案号', 1, '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '浙ICP备XX号', 12),
(11, 'DOCUMENT_POSITION', 3, '文档推荐位', 2, '', '文档推荐位，推荐到多个位置KEY值相加即可', 1379053380, 1379235329, 1, '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', 11),
(12, 'DOCUMENT_DISPLAY', 3, '文档可见性', 2, '', '文章可见性仅影响前台显示，后台不收影响', 1379056370, 1379235322, 1, '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', 14),
(13, 'COLOR_STYLE', 4, '后台色系', 1, 'default_color:默认\r\nblue_color:紫罗兰\r\namaze:Amazu', '后台颜色风格', 1379122533, 1409279514, 1, 'amaze', 2),
(20, 'CONFIG_GROUP_LIST', 3, '配置分组', 4, '', '配置分组', 1379228036, 1384418383, 1, '1:基本\r\n2:内容\r\n3:用户\r\n4:系统\r\n5:邮件', 15),
(21, 'HOOKS_TYPE', 3, '钩子的类型', 4, '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', 1379313397, 1379313407, 1, '1:视图\r\n2:控制器', 17),
(22, 'AUTH_CONFIG', 3, 'Auth配置', 4, '', '自定义Auth.class.php类配置', 1379409310, 1379409564, 1, 'AUTH_ON:1\r\nAUTH_TYPE:2', 20),
(23, 'OPEN_DRAFTBOX', 4, '是否开启草稿功能', 2, '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', 1379484332, 1379484591, 1, '1', 10),
(24, 'DRAFT_AOTOSAVE_INTERVAL', 0, '自动保存草稿时间', 2, '', '自动保存草稿的时间间隔，单位：秒', 1379484574, 1386143323, 1, '60', 9),
(25, 'LIST_ROWS', 0, '后台每页记录数', 2, '', '后台数据每页显示记录数', 1379503896, 1380427745, 1, '10', 24),
(26, 'USER_ALLOW_REGISTER', 4, '是否允许用户注册', 3, '0:关闭注册\r\n1:允许注册', '是否开放用户注册', 1379504487, 1379504580, 1, '1', 12),
(27, 'CODEMIRROR_THEME', 4, '预览插件的CodeMirror主题', 4, '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', 1379814385, 1384740813, 1, 'ambiance', 13),
(28, 'DATA_BACKUP_PATH', 1, '数据库备份根路径', 4, '', '路径必须以 / 结尾', 1381482411, 1381482411, 1, './Data/', 16),
(29, 'DATA_BACKUP_PART_SIZE', 0, '数据库备份卷大小', 4, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1381729564, 1, '20971520', 18),
(30, 'DATA_BACKUP_COMPRESS', 4, '数据库备份文件是否启用压缩', 4, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1381729544, 1, '1', 22),
(31, 'DATA_BACKUP_COMPRESS_LEVEL', 4, '数据库备份文件压缩级别', 4, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1381713408, 1, '9', 25),
(32, 'DEVELOP_MODE', 4, '开启开发者模式', 4, '0:关闭\r\n1:开启', '是否开启开发者模式', 1383105995, 1383291877, 1, '1', 26),
(33, 'ALLOW_VISIT', 3, '不受限控制器方法', 0, '', '', 1386644047, 1386644741, 1, '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture', 2),
(34, 'DENY_VISIT', 3, '超管专限控制器方法', 0, '', '仅超级管理员可访问的控制器方法', 1386644141, 1386644659, 1, '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', 3),
(35, 'REPLY_LIST_ROWS', 0, '回复列表每页条数', 2, '', '', 1386645376, 1387178083, 1, '10', 4),
(36, 'ADMIN_ALLOW_IP', 2, '后台允许访问IP', 4, '', '多个用逗号分隔，如果不配置表示不限制IP访问', 1387165454, 1387165553, 1, '', 27),
(37, 'SHOW_PAGE_TRACE', 4, '是否显示页面Trace', 4, '0:关闭\r\n1:开启', '是否显示页面Trace信息', 1387165685, 1387165685, 1, '0', 7),
(38, 'WEB_SITE', 1, '网站名称', 1, '', '用于邮件,短信,站内信显示', 1388332311, 1388501500, 1, 'OpenSNS', 3),
(39, 'MAIL_TYPE', 4, '邮件类型', 5, '1:SMTP 模块发送\r\n2:mail() 函数发送', '如果您选择了采用服务器内置的 Mail 服务，您不需要填写下面的内容', 1388332882, 1388931416, 1, '1', 0),
(40, 'MAIL_SMTP_HOST', 1, 'SMTP 服务器', 5, '', 'SMTP服务器', 1388332932, 1388332932, 1, '', 0),
(41, 'MAIL_SMTP_PORT', 0, 'SMTP服务器端口', 5, '', '默认25', 1388332975, 1388332975, 1, '25', 0),
(42, 'MAIL_SMTP_USER', 1, 'SMTP服务器用户名', 5, '', '填写完整用户名', 1388333010, 1388333010, 1, '', 0),
(43, 'MAIL_SMTP_PASS', 6, 'SMTP服务器密码', 5, '', '填写您的密码', 1388333057, 1389187088, 1, '', 0),
(50, 'MAIL_USER_PASS', 5, '密码找回模板', 0, '', '支持HTML代码', 1388583989, 1388672614, 1, '密码找回111223333555111', 0),
(51, 'PIC_FILE_PATH', 1, '图片文件保存根目录', 4, '', '图片文件保存根目录./目录/', 1388673255, 1388673255, 1, './Uploads/', 0),
(46, 'MAIL_SMTP_CE', 1, '邮件发送测试', 5, '', '填写测试邮件地址', 1388334529, 1388584028, 1, '', 11),
(47, 'MAIL_USER_REG', 5, '注册邮件模板', 3, '', '支持HTML代码', 1388337307, 1389532335, 1, '<a href="http://3spp.cn" target="_blank">点击进入</a><span style="color:#E53333;">当您收到这封邮件，表明您已注册成功，以上为您的用户名和密码。。。。祝您生活愉快····</span>', 55),
(52, 'USER_NAME_BAOLIU', 1, '保留用户名', 3, '', '禁止注册用户名,用" , "号隔开', 1388845937, 1388845937, 1, '管理员,测试,admin,垃圾', 0),
(53, 'USER_REG_TIME', 0, '注册时间限制', 3, '', '同一IP注册时间限制，防恶意注册，格式分钟', 1388847715, 1388847715, 1, '2', 0),
(48, 'VERIFY_OPEN', 4, '验证码配置', 4, '0:全部关闭\r\n1:全部显示\r\n2:注册显示\r\n3:登陆显示', '验证码配置', 1388500332, 1405561711, 1, '2', 0),
(49, 'VERIFY_TYPE', 4, '验证码类型', 4, '1:中文\r\n2:英文\r\n3:数字\r\n4:算数', '验证码类型', 1388500873, 1405561731, 1, '1', 0),
(54, 'NO_BODY_TLE', 2, '空白说明', 2, '', '空白说明', 1392216444, 1392981305, 1, '呵呵，暂时没有内容哦！！', 0),
(55, 'USER_RESPASS', 5, '密码找回模板', 3, '', '密码找回文本', 1396191234, 1396191234, 1, '<span style="color:#009900;">请点击以下链接找回密码，如无反应，请将链接地址复制到浏览器中打开(下次登录前有效)</span>', 0),
(56, 'COUNT_CODE', 2, '统计代码', 1, '', '用于统计网站访问量的第三方代码，推荐CNZZ统计', 1403058890, 1403058890, 1, '', 4),
(57, 'SHARE_WEIBO_ID', 0, '分享来源微博ID', 1, '', '来源的微博ID，不配置则隐藏顶部微博分享按钮。', 1403091490, 1403091490, 1, '', 5),
(60, 'AFTER_LOGIN_JUMP_URL', 2, '登陆后跳转的Url', 1, '', '支持形如weibo/index/index的ThinkPhp路由写法，支持普通的url写法', 1407145718, 1407154887, 1, 'weibo/index/index', 7),
(58, 'USER_REG_WEIBO_CONTENT', 1, '用户注册微博提示内容', 3, '', '留空则表示不发新微博，支持face', 1404965285, 1404965445, 1, '', 0),
(59, 'WEIBO_WORDS_COUNT', 0, '微博字数限制', 1, '', '最大允许的微博字数长度', 1405330568, 1405330622, 1, '200', 6),
(61, 'FOOTER_RIGHT', 2, '底部右侧链接部分', 1, '', '链接部分', 1408008354, 1408008354, 1, '  <p><h4><strong>联系</strong></h4></p>\r\n                    <p><a href="http://www.ourstu.com/index.php?s=/home/index/about.html" target="_blank">关于我们</a>\r\n                    <p>\r\n                    <p><a href="http://tox.ourstu.com/tox/index.php?s=/Forum/Index/forum/id/3.html" target="_blank">问题反馈</a></p>\r\n                    <p><a href="http://www.ourstu.com/index.php?s=/home/index/contact.html" target="_blank">联系我们</a></p>', 10),
(62, 'FOOTER_SUMMARY', 2, '底部简介部分代码', 1, '', '', 1408008496, 1408008496, 1, '嘉兴想天信息科技有限公司是一家专注于为客户提供专业的社交方案。公司秉持简洁、高效、创新，不断为客户创造奇迹。', 9),
(63, 'FOOTER_TITLE', 2, '底部介绍标题', 1, '', '公司标题', 1408008573, 1408008573, 1, '<a target="_blank" href="http://www.ourstu.com/">嘉兴想天信息科技有限公司</a>', 8),
(64, 'FOOTER_QCODE', 2, '底部二维码部分代码', 1, '', '', 1408008644, 1408008738, 1, ' <img style="width:150px" src="/Public/Core/images/qrcode.png"/>', 11),
(78, '_WEIBO_SHOW_TITLE', 0, '', 0, '', '', 1409670239, 1409670239, 1, '1', 0),
(75, '_WEIBO_SHOWTITLE', 0, '', 0, '', '', 1409670094, 1409670094, 1, '0', 0),
(80, '_FORUM_LIMIT_IMAGE', 0, '', 0, '', '', 1409711730, 1409711730, 1, '1', 0),
(82, '_ISSUE_NEED_VERIFY', 0, '', 0, '', '', 1409712596, 1409712596, 1, '0', 0),
(87, '_USER_LEVEL', 0, '', 0, '', '', 1426053581, 1426053581, 1, '0:Lv1 实习\r\n50:Lv2 试用\r\n100:Lv3 转正\r\n200:Lv4 助理\r\n400:Lv 5 经理\r\n800:Lv6 董事\r\n1600:Lv7 董事长', 0),
(85, '_EXPRESSION_EXPRESSION', 0, '', 0, '', '', 1417150390, 1417150390, 1, 'miniblog', 0),
(86, 'DEFUALT_HOME_URL', 1, '默认首页Url', 1, '', '支持形如weibo/index/index的ThinkPhp路由写法，支持普通的url写法，不填则显示默认聚合首页', 1417509438, 1417509501, 1, 'Weibo/index/index', 0),
(88, '_USER_USER_CHECKIN_SCORE', 0, '', 0, '', '', 1426053581, 1426053581, 1, '0', 0),
(89, '_USER_DEFAULT_GROUP', 0, '', 0, '', '', 1426053581, 1426053581, 1, '1', 0);

DROP TABLE IF EXISTS `opensns_document`;
CREATE TABLE IF NOT EXISTS `opensns_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `description` char(140) NOT NULL DEFAULT '' COMMENT '描述',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `deadline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `idx_category_status` (`category_id`,`status`),
  KEY `idx_status_type_pid` (`status`,`uid`,`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文档模型基础表' AUTO_INCREMENT=15 ;

DROP TABLE IF EXISTS `opensns_document_article`;
CREATE TABLE IF NOT EXISTS `opensns_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `zx` int(10) unsigned NOT NULL COMMENT '附近',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表';

DROP TABLE IF EXISTS `opensns_document_download`;
CREATE TABLE IF NOT EXISTS `opensns_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表';

DROP TABLE IF EXISTS `opensns_field`;
CREATE TABLE IF NOT EXISTS `opensns_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `field_data` varchar(1000) NOT NULL,
  `createTime` int(11) NOT NULL,
  `changeTime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

DROP TABLE IF EXISTS `opensns_field_group`;
CREATE TABLE IF NOT EXISTS `opensns_field_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `visiable` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

INSERT INTO `opensns_field_group` (`id`, `profile_name`, `status`, `createTime`, `sort`, `visiable`) VALUES
(13, '个人资料', 1, 1403847366, 0, 1);

DROP TABLE IF EXISTS `opensns_field_setting`;
CREATE TABLE IF NOT EXISTS `opensns_field_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(25) NOT NULL,
  `profile_group_id` int(11) NOT NULL,
  `visiable` tinyint(4) NOT NULL DEFAULT '1',
  `required` tinyint(4) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL,
  `form_type` varchar(25) NOT NULL,
  `form_default_value` varchar(200) NOT NULL,
  `validation` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` int(11) NOT NULL,
  `child_form_type` varchar(25) NOT NULL,
  `input_tips` varchar(100) NOT NULL COMMENT '输入提示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

INSERT INTO `opensns_field_setting` (`id`, `field_name`, `profile_group_id`, `visiable`, `required`, `sort`, `form_type`, `form_default_value`, `validation`, `status`, `createTime`, `child_form_type`, `input_tips`) VALUES
(36, 'qq', 13, 1, 1, 0, 'input', '', '', 1, 1409045825, 'string', '');

DROP TABLE IF EXISTS `opensns_file`;
CREATE TABLE IF NOT EXISTS `opensns_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `create_time` int(10) unsigned NOT NULL COMMENT '上传时间',
  `driver` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文件表' AUTO_INCREMENT=8 ;

DROP TABLE IF EXISTS `opensns_follow`;
CREATE TABLE IF NOT EXISTS `opensns_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `follow_who` int(11) NOT NULL COMMENT '关注谁',
  `who_follow` int(11) NOT NULL COMMENT '谁关注',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='关注表' AUTO_INCREMENT=72 ;

DROP TABLE IF EXISTS `opensns_forum`;
CREATE TABLE IF NOT EXISTS `opensns_forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `allow_user_group` text NOT NULL,
  `sort` int(11) NOT NULL,
  `logo` int(11) NOT NULL,
  `background` int(11) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `last_reply_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `opensns_forum` (`id`, `title`, `create_time`, `post_count`, `status`, `allow_user_group`, `sort`, `logo`, `background`, `description`, `admin`, `type_id`, `last_reply_time`) VALUES
(1, '默认版块', 1407114174, 12, 1, '1', 0, 133, 123, '1231', '', 1, 0),
(2, '官方公告', 1417424922, 0, 1, '1', 0, 134, 117, '官方公告发布区', '', 2, 0);

DROP TABLE IF EXISTS `opensns_forum_bookmark`;
CREATE TABLE IF NOT EXISTS `opensns_forum_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `opensns_forum_lzl_reply`;
CREATE TABLE IF NOT EXISTS `opensns_forum_lzl_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `to_f_reply_id` int(11) NOT NULL,
  `to_reply_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `is_del` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

DROP TABLE IF EXISTS `opensns_forum_post`;
CREATE TABLE IF NOT EXISTS `opensns_forum_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `parse` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `last_reply_time` int(11) NOT NULL,
  `view_count` int(11) NOT NULL,
  `reply_count` int(11) NOT NULL,
  `is_top` tinyint(4) NOT NULL COMMENT '是否置顶',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

DROP TABLE IF EXISTS `opensns_forum_post_reply`;
CREATE TABLE IF NOT EXISTS `opensns_forum_post_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parse` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS `opensns_forum_type`;
CREATE TABLE IF NOT EXISTS `opensns_forum_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '标题',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='论坛分类表' AUTO_INCREMENT=3 ;

INSERT INTO `opensns_forum_type` (`id`, `title`, `status`, `sort`, `pid`) VALUES
(1, '默认分类', 1, 0, 0),
(2, '官方版块', 1, 1, 0);

DROP TABLE IF EXISTS `opensns_hooks`;
CREATE TABLE IF NOT EXISTS `opensns_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

INSERT INTO `opensns_hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`) VALUES
(1, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, 0, 'ImageSlider'),
(2, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, 0, 'SuperLinks'),
(3, 'documentEditForm', '添加编辑表单的 扩展内容钩子', 1, 0, 'Attachment'),
(4, 'documentDetailAfter', '文档末尾显示', 1, 0, 'Attachment,SocialComment,Avatar'),
(5, 'documentDetailBefore', '页面内容前显示用钩子', 1, 0, ''),
(6, 'documentSaveComplete', '保存文档数据后的扩展钩子', 2, 0, 'Attachment'),
(7, 'documentEditFormContent', '添加编辑表单的内容显示钩子', 1, 0, 'Editor'),
(8, 'adminArticleEdit', '后台内容编辑页编辑器', 1, 1378982734, 'EditorForAdmin'),
(13, 'AdminIndex', '首页小格子个性化显示', 1, 1382596073, 'SiteStat,SystemInfo,SyncLogin,Advertising,DevTeam'),
(14, 'topicComment', '评论提交方式扩展钩子。', 1, 1380163518, 'Editor'),
(16, 'app_begin', '应用开始', 2, 1384481614, 'Iswaf'),
(17, 'checkin', '签到', 1, 1395371353, 'Checkin'),
(18, 'Rank', '签到排名钩子', 1, 1395387442, 'Rank_checkin'),
(20, 'support', '赞', 1, 1398264759, 'Support'),
(21, 'localComment', '本地评论插件', 1, 1399440321, 'LocalComment'),
(22, 'weiboType', '微博类型', 1, 1409121894, 'InsertImage'),
(23, 'repost', '转发钩子', 1, 1403668286, 'Repost'),
(24, 'syncLogin', '第三方登陆位置', 1, 1403700579, 'SyncLogin'),
(25, 'syncMeta', '第三方登陆meta接口', 1, 1403700633, 'SyncLogin'),
(26, 'J_China_City', '每个系统都需要的一个中国省市区三级联动插件。', 1, 1403841931, 'ChinaCity'),
(27, 'Advs', '广告位插件', 1, 1406687667, 'Advs'),
(28, 'imageSlider', '图片轮播钩子', 1, 1407144022, 'ImageSlider'),
(29, 'friendLink', '友情链接插件', 1, 1407156413, 'SuperLinks'),
(30, 'beforeSendWeibo', '在发微博之前预处理微博', 2, 1408084504, 'InsertFile'),
(31, 'beforeSendRepost', '转发微博前的预处理钩子', 2, 1408085689, ''),
(32, 'parseWeiboContent', '解析微博内容钩子', 2, 1409121261, ''),
(33, 'userConfig', '用户配置页面钩子', 1, 1417137557, 'SyncLogin'),
(34, 'weiboSide', '微博侧边钩子', 1, 1417063425, 'Retopic'),
(35, 'personalMenus', '顶部导航栏个人下拉菜单', 1, 1417146501, ''),
(36, 'dealPicture', '上传图片处理', 2, 1417139975, ''),
(37, 'ucenterSideMenu', '用户中心左侧菜单', 1, 1417161205, ''),
(38, 'userRegister', '用户注册钩子，参数uid为用户ID', 2, 1419563244, ''),
(39, 'homeIndex', '网站首页钩子', 2, 1420525722, '');

DROP TABLE IF EXISTS `opensns_issue`;
CREATE TABLE IF NOT EXISTS `opensns_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `allow_post` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

INSERT INTO `opensns_issue` (`id`, `title`, `create_time`, `update_time`, `status`, `allow_post`, `pid`, `sort`) VALUES
(13, '默认专辑', 1409712474, 1409712467, 1, 0, 0, 0),
(14, '默认二级', 1409712480, 1409712475, 1, 0, 13, 0);

DROP TABLE IF EXISTS `opensns_issue_content`;
CREATE TABLE IF NOT EXISTS `opensns_issue_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `view_count` int(11) NOT NULL COMMENT '阅读数量',
  `cover_id` int(11) NOT NULL COMMENT '封面图片id',
  `issue_id` int(11) NOT NULL COMMENT '所在专辑',
  `uid` int(11) NOT NULL COMMENT '发布者id',
  `reply_count` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='专辑内容表' AUTO_INCREMENT=29 ;

DROP TABLE IF EXISTS `opensns_local_comment`;
CREATE TABLE IF NOT EXISTS `opensns_local_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `app` text NOT NULL,
  `mod` text NOT NULL,
  `row_id` int(11) NOT NULL,
  `parse` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `create_time` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `opensns_member`;
CREATE TABLE IF NOT EXISTS `opensns_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  `signature` text NOT NULL,
  `tox_money` int(11) NOT NULL,
  `pos_province` int(11) NOT NULL,
  `pos_city` int(11) NOT NULL,
  `pos_district` int(11) NOT NULL,
  `pos_community` int(11) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `status` (`status`),
  KEY `name` (`nickname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员表' AUTO_INCREMENT=58 ;


DROP TABLE IF EXISTS `opensns_menu`;
CREATE TABLE IF NOT EXISTS `opensns_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2299 ;

INSERT INTO `opensns_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
(1, '首页', 0, 1, 'Index/index', 0, '', '', 0),
(2, '资讯', 0, 2, 'Article/mydocument', 0, '', '', 0),
(3, '文档列表', 2, 0, 'article/index', 1, '', '内容', 0),
(4, '新增', 3, 0, 'article/add', 0, '', '', 0),
(5, '编辑', 3, 0, 'article/edit', 0, '', '', 0),
(6, '改变状态', 3, 0, 'article/setStatus', 0, '', '', 0),
(7, '保存', 3, 0, 'article/update', 0, '', '', 0),
(8, '保存草稿', 3, 0, 'article/autoSave', 0, '', '', 0),
(9, '移动', 3, 0, 'article/move', 0, '', '', 0),
(10, '复制', 3, 0, 'article/copy', 0, '', '', 0),
(11, '粘贴', 3, 0, 'article/paste', 0, '', '', 0),
(12, '导入', 3, 0, 'article/batchOperate', 0, '', '', 0),
(13, '回收站', 2, 0, 'article/recycle', 1, '', '内容', 0),
(14, '还原', 13, 0, 'article/permit', 0, '', '', 0),
(15, '清空', 13, 0, 'article/clear', 0, '', '', 0),
(16, '用户', 0, 3, 'User/index', 0, '', '', 0),
(17, '用户信息', 16, 0, 'User/index', 0, '', '用户管理', 0),
(18, '新增用户', 17, 0, 'User/add', 0, '添加新用户', '', 0),
(19, '用户行为', 16, 0, 'User/action', 0, '', '行为管理', 0),
(20, '新增用户行为', 19, 0, 'User/addaction', 0, '', '', 0),
(21, '编辑用户行为', 19, 0, 'User/editaction', 0, '', '', 0),
(22, '保存用户行为', 19, 0, 'User/saveAction', 0, '"用户->用户行为"保存编辑和新增的用户行为', '', 0),
(23, '变更行为状态', 19, 0, 'User/setStatus', 0, '"用户->用户行为"中的启用,禁用和删除权限', '', 0),
(24, '禁用会员', 19, 0, 'User/changeStatus?method=forbidUser', 0, '"用户->用户信息"中的禁用', '', 0),
(25, '启用会员', 19, 0, 'User/changeStatus?method=resumeUser', 0, '"用户->用户信息"中的启用', '', 0),
(26, '删除会员', 19, 0, 'User/changeStatus?method=deleteUser', 0, '"用户->用户信息"中的删除', '', 0),
(27, '权限管理', 16, 0, 'AuthManager/index', 0, '', '用户管理', 0),
(28, '删除', 27, 0, 'AuthManager/changeStatus?method=deleteGroup', 0, '删除用户组', '', 0),
(29, '禁用', 27, 0, 'AuthManager/changeStatus?method=forbidGroup', 0, '禁用用户组', '', 0),
(30, '恢复', 27, 0, 'AuthManager/changeStatus?method=resumeGroup', 0, '恢复已禁用的用户组', '', 0),
(31, '新增', 27, 0, 'AuthManager/createGroup', 0, '创建新的用户组', '', 0),
(32, '编辑', 27, 0, 'AuthManager/editGroup', 0, '编辑用户组名称和描述', '', 0),
(33, '保存用户组', 27, 0, 'AuthManager/writeGroup', 0, '新增和编辑用户组的"保存"按钮', '', 0),
(34, '授权', 27, 0, 'AuthManager/group', 0, '"后台 \\ 用户 \\ 用户信息"列表页的"授权"操作按钮,用于设置用户所属用户组', '', 0),
(35, '访问授权', 27, 0, 'AuthManager/access', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"访问授权"操作按钮', '', 0),
(36, '成员授权', 27, 0, 'AuthManager/user', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"成员授权"操作按钮', '', 0),
(37, '解除授权', 27, 0, 'AuthManager/removeFromGroup', 0, '"成员授权"列表页内的解除授权操作按钮', '', 0),
(38, '保存成员授权', 27, 0, 'AuthManager/addToGroup', 0, '"用户信息"列表页"授权"时的"保存"按钮和"成员授权"里右上角的"添加"按钮)', '', 0),
(39, '分类授权', 27, 0, 'AuthManager/category', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"分类授权"操作按钮', '', 0),
(40, '保存分类授权', 27, 0, 'AuthManager/addToCategory', 0, '"分类授权"页面的"保存"按钮', '', 0),
(41, '模型授权', 27, 0, 'AuthManager/modelauth', 0, '"后台 \\ 用户 \\ 权限管理"列表页的"模型授权"操作按钮', '', 0),
(42, '保存模型授权', 27, 0, 'AuthManager/addToModel', 0, '"分类授权"页面的"保存"按钮', '', 0),
(43, '扩展', 0, 998, 'Addons/index', 0, '', '', 0),
(44, '插件管理', 43, 1, 'Addons/index', 0, '', '扩展', 0),
(45, '创建', 44, 0, 'Addons/create', 0, '服务器上创建插件结构向导', '', 0),
(46, '检测创建', 44, 0, 'Addons/checkForm', 0, '检测插件是否可以创建', '', 0),
(47, '预览', 44, 0, 'Addons/preview', 0, '预览插件定义类文件', '', 0),
(48, '快速生成插件', 44, 0, 'Addons/build', 0, '开始生成插件结构', '', 0),
(49, '设置', 44, 0, 'Addons/config', 0, '设置插件配置', '', 0),
(50, '禁用', 44, 0, 'Addons/disable', 0, '禁用插件', '', 0),
(51, '启用', 44, 0, 'Addons/enable', 0, '启用插件', '', 0),
(52, '安装', 44, 0, 'Addons/install', 0, '安装插件', '', 0),
(53, '卸载', 44, 0, 'Addons/uninstall', 0, '卸载插件', '', 0),
(54, '更新配置', 44, 0, 'Addons/saveconfig', 0, '更新插件配置处理', '', 0),
(55, '插件后台列表', 44, 0, 'Addons/adminList', 0, '', '', 0),
(56, 'URL方式访问插件', 44, 0, 'Addons/execute', 0, '控制是否有权限通过url访问插件控制器方法', '', 0),
(57, '钩子管理', 43, 2, 'Addons/hooks', 0, '', '扩展', 0),
(68, '系统', 0, 9999, 'Config/group', 0, '', '', 0),
(69, '网站设置', 68, 1, 'Config/group', 0, '', '系统设置', 0),
(70, '配置管理', 68, 4, 'Config/index', 0, '', '系统设置', 0),
(71, '编辑', 70, 0, 'Config/edit', 0, '新增编辑和保存配置', '', 0),
(72, '删除', 70, 0, 'Config/del', 0, '删除配置', '', 0),
(73, '新增', 70, 0, 'Config/add', 0, '新增配置', '', 0),
(74, '保存', 70, 0, 'Config/save', 0, '保存配置', '', 0),
(75, '菜单管理', 68, 5, 'Menu/index', 0, '', '系统设置', 0),
(76, '导航管理', 68, 6, 'Channel/index', 0, '', '系统设置', 0),
(77, '新增', 76, 0, 'Channel/add', 0, '', '', 0),
(78, '编辑', 76, 0, 'Channel/edit', 0, '', '', 0),
(79, '删除', 76, 0, 'Channel/del', 0, '', '', 0),
(80, '分类管理', 2, 2, 'Category/index', 0, '', '系统设置', 0),
(81, '编辑', 80, 0, 'Category/edit', 0, '编辑和保存栏目分类', '', 0),
(82, '新增', 80, 0, 'Category/add', 0, '新增栏目分类', '', 0),
(83, '删除', 80, 0, 'Category/remove', 0, '删除栏目分类', '', 0),
(84, '移动', 80, 0, 'Category/operate/type/move', 0, '移动栏目分类', '', 0),
(85, '合并', 80, 0, 'Category/operate/type/merge', 0, '合并栏目分类', '', 0),
(86, '备份数据库', 68, 20, 'Database/index?type=export', 0, '', '数据备份', 0),
(87, '备份', 86, 0, 'Database/export', 0, '备份数据库', '', 0),
(88, '优化表', 86, 0, 'Database/optimize', 0, '优化数据表', '', 0),
(89, '修复表', 86, 0, 'Database/repair', 0, '修复数据表', '', 0),
(90, '还原数据库', 68, 0, 'Database/index?type=import', 0, '', '数据备份', 0),
(91, '恢复', 90, 0, 'Database/import', 0, '数据库恢复', '', 0),
(92, '删除', 90, 0, 'Database/del', 0, '删除备份文件', '', 0),
(93, '其他', 0, 5, 'other', 1, '', '', 0),
(96, '新增', 75, 0, 'Menu/add', 0, '', '系统设置', 0),
(98, '编辑', 75, 0, 'Menu/edit', 0, '', '', 0),
(104, '下载管理', 102, 0, 'Think/lists?model=download', 0, '', '', 0),
(105, '配置管理', 102, 0, 'Think/lists?model=config', 0, '', '', 0),
(106, '行为日志', 16, 0, 'Action/actionlog', 0, '', '行为管理', 0),
(108, '修改密码', 16, 0, 'User/updatePassword', 1, '', '', 0),
(109, '修改昵称', 16, 0, 'User/updateNickname', 1, '', '', 0),
(110, '查看行为日志', 106, 0, 'action/edit', 1, '', '', 0),
(112, '新增数据', 58, 0, 'think/add', 1, '', '', 0),
(113, '编辑数据', 58, 0, 'think/edit', 1, '', '', 0),
(114, '导入', 75, 0, 'Menu/import', 0, '', '', 0),
(115, '生成', 58, 0, 'Model/generate', 0, '', '', 0),
(116, '新增钩子', 57, 0, 'Addons/addHook', 0, '', '', 0),
(117, '编辑钩子', 57, 0, 'Addons/edithook', 0, '', '', 0),
(118, '文档排序', 3, 0, 'Article/sort', 1, '', '', 0),
(119, '排序', 70, 0, 'Config/sort', 1, '', '', 0),
(120, '排序', 75, 0, 'Menu/sort', 1, '', '', 0),
(121, '排序', 76, 0, 'Channel/sort', 1, '', '', 0),
(2292, '专辑回收站', 2289, 4, 'Issue/issueTrash', 0, '', '专辑', 0),
(2277, '论坛设置', 2266, 10, 'Forum/config', 0, '', '设置', 0),
(2278, '新增/编辑分类', 2266, 0, 'Forum/addType', 1, '', '', 0),
(2279, '设置分类状态', 2266, 0, 'Forum/setTypeStatus', 1, '', '', 0),
(130, '新增、编辑', 132, 0, 'SEO/editRule', 0, '', '', 0),
(131, '排序', 132, 0, 'SEO/sortRule', 0, '', '', 0),
(132, '规则管理', 68, 0, 'SEO/index', 0, '', 'SEO规则', 0),
(2291, '专辑管理', 2289, 0, 'Issue/issue', 0, '', '专辑', 0),
(2290, '编辑专辑', 2289, 0, 'Issue/add', 1, '', '专辑', 0),
(2273, '板块回收站', 2266, 2, 'Forum/forumTrash', 0, '', '板块', 0),
(2266, '论坛', 0, 22, 'Forum/index', 0, '', '', 0),
(2267, '板块管理', 2266, 1, 'Forum/forum', 0, '', '板块', 0),
(2268, '帖子管理', 2266, 3, 'Forum/post', 0, '', '帖子', 0),
(2269, '编辑／创建板块', 2266, 0, 'Forum/editForum', 1, '', '', 0),
(2270, '编辑帖子', 2266, 0, 'Forum/editPost', 1, '', '', 0),
(2271, '排序', 2266, 0, 'Forum/sortForum', 0, '', '板块', 0),
(2272, '新增/编辑回复', 2266, 0, 'Forum/editReply', 1, '', '', 0),
(146, '规则回收站', 68, 0, 'SEO/ruleTrash', 0, '', 'SEO规则', 0),
(147, '头衔列表', 16, 10, 'Rank/index', 0, '', '头衔管理', 0),
(149, '添加头衔', 16, 2, 'Rank/editRank', 0, '', '头衔管理', 0),
(150, '查看用户', 16, 0, 'Rank/userList', 0, '', '头衔管理', 0),
(151, '用户头衔列表', 150, 0, 'Rank/userRankList', 1, '', '', 0),
(152, '关联新头衔', 150, 0, 'Rank/userAddRank', 1, '', '', 0),
(153, '编辑头衔关联', 150, 0, 'Rank/userChangeRank', 1, '', '', 0),
(2286, '微博回收站', 2281, 2, 'Weibo/weiboTrash', 0, '', '微博', 0),
(2285, '编辑回复', 2281, 0, 'Weibo/editComment', 1, '', '', 0),
(2284, '编辑微博', 2281, 0, 'Weibo/editWeibo', 1, '', '', 0),
(2283, '回复管理', 2281, 3, 'Weibo/comment', 0, '', '回复', 0),
(2282, '微博管理', 2281, 1, 'Weibo/weibo', 0, '', '微博', 0),
(2281, '微博', 0, 8, 'Weibo/weibo', 0, '', '', 0),
(2280, '分类管理', 2266, 0, 'Forum/type', 0, '分类管理', '分类管理', 0),
(162, '扩展资料', 16, 0, 'Admin/User/profile', 0, '', '用户管理', 0),
(163, '添加、编辑分组', 162, 0, 'Admin/User/editProfile', 0, '', '', 0),
(164, '分组排序', 162, 0, 'Admin/User/sortProfile', 0, '', '', 0),
(165, '字段列表', 162, 0, 'Admin/User/field', 0, '', '', 0),
(166, '添加、编辑字段', 165, 0, 'Admin/User/editFieldSetting', 0, '', '', 0),
(167, '字段排序', 165, 0, 'Admin/User/sortField', 0, '', '', 0),
(2265, '新增补丁', 68, 0, 'Update/addpack', 1, '', '升级补丁', 0),
(2264, '全部补丁', 68, 0, 'Update/quick', 0, '', '升级补丁', 0),
(170, '用户扩展资料列表', 16, 0, 'Admin/User/expandinfo_select', 0, '', '用户管理', 0),
(171, '扩展资料详情', 170, 0, 'User/expandinfo_details', 0, '', '', 0),
(2296, '内容管理', 2289, 0, 'Issue/contents', 0, '', '内容管理', 0),
(2295, '内容回收站', 2289, 5, 'Issue/contentTrash', 0, '', '内容管理', 0),
(2294, '内容审核', 2289, 1, 'Issue/verify', 0, '', '内容管理', 0),
(2293, '专辑操作', 2289, 0, 'Issue/operate', 1, '', '专辑', 0),
(216, '待审核用户头衔', 16, 0, 'Rank/rankVerify', 0, '', '头衔管理', 0),
(217, '被驳回的头衔申请', 16, 0, 'Rank/rankVerifyFailure', 0, '', '头衔管理', 0),
(2274, '帖子回收站', 2266, 4, 'Forum/postTrash', 0, '', '帖子', 0),
(2275, '回复回收站', 2266, 6, 'Forum/replyTrash', 0, '', '回复', 0),
(2276, '回复管理', 2266, 6, 'Forum/reply', 0, '', '回复', 0),
(222, '基础设置', 16, 0, 'User/config', 0, '', '用户管理', 0),
(2298, '设置专辑状态', 2289, 0, 'Issue/setIssueContentStatus', 1, '', '', 0),
(2297, '专辑设置', 2289, 0, 'Issue/config', 0, '', '设置', 0),
(2262, '卸载模块', 2260, 0, 'module/uninstall', 1, '', '云平台', 0),
(2261, '模块管理', 2260, 0, 'module/lists', 0, '', '云平台', 0),
(2260, '云平台', 0, 999999, 'module/lists', 0, '', '', 0),
(2238, '新增权限节点', 27, 0, 'AuthManager/addNode', 1, '', '', 1),
(2239, '前台权限管理', 27, 0, 'AuthManager/accessUser', 1, '', '权限管理', 0),
(2240, '转移用户组', 16, 0, 'User/changeGroup', 1, '批量转移用户组', '', 0),
(2241, '删除权限节点', 27, 0, 'AuthManager/deleteNode', 1, '', '', 0),
(2242, '表情管理', 68, 30, 'admin/expression/index', 0, '', '表情配置', 0),
(2243, '表情列表', 68, 0, 'admin/expression/expressionList', 1, '', '表情配置', 0),
(2244, '新增表情包', 68, 0, 'adimn/expression/add', 1, '', '表情配置', 0),
(2289, '专辑', 0, 22, 'Issue/issue', 0, '', '', 0),
(2288, '微博设置', 2281, 0, 'Weibo/config', 0, '微博的基本配置', '设置', 0),
(2287, '回复回收站', 2281, 4, 'Weibo/commentTrash', 0, '', '回复', 0),
(2263, '模块安装', 2260, 0, 'module/install', 1, '', '云平台', 0);

DROP TABLE IF EXISTS `opensns_message`;
CREATE TABLE IF NOT EXISTS `opensns_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0系统消息,1用户消息,2应用消息',
  `is_read` tinyint(4) NOT NULL,
  `last_toast` int(11) NOT NULL,
  `url` varchar(400) NOT NULL,
  `talk_id` int(11) NOT NULL,
  `appname` varchar(30) NOT NULL,
  `apptype` varchar(30) NOT NULL,
  `source_id` int(11) NOT NULL,
  `find_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='opensns新增消息表' AUTO_INCREMENT=322 ;

DROP TABLE IF EXISTS `opensns_model`;
CREATE TABLE IF NOT EXISTS `opensns_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text NOT NULL COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text NOT NULL COMMENT '属性列表（表的字段）',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text NOT NULL COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `engine_type` varchar(25) NOT NULL DEFAULT 'MyISAM' COMMENT '数据库引擎',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文档模型表' AUTO_INCREMENT=4 ;

INSERT INTO `opensns_model` (`id`, `name`, `title`, `extend`, `relation`, `need_pk`, `field_sort`, `field_group`, `attribute_list`, `template_list`, `template_add`, `template_edit`, `list_grid`, `list_row`, `search_key`, `search_list`, `create_time`, `update_time`, `status`, `engine_type`) VALUES
(1, 'document', '基础文档', 0, '', 1, '{"1":["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22"]}', '1:基础', '', '', '', '', 'id:编号\r\ntitle:标题:article/index?cate_id=[category_id]&pid=[id]\r\ntype|get_document_type:类型\r\nlevel:优先级\r\nupdate_time|time_format:最后更新\r\nstatus_text:状态\r\nview:浏览\r\nid:操作:[EDIT]&cate_id=[category_id]|编辑,article/setstatus?status=-1&ids=[id]|删除', 0, '', '', 1383891233, 1384507827, 1, 'MyISAM'),
(2, 'article', '文章', 1, '', 1, '{"1":["3","24","2","5"],"2":["9","13","19","10","12","16","17","26","20","14","11","25"]}', '1:基础,2:扩展', '', '', '', '', 'id:编号\r\ntitle:标题:article/edit?cate_id=[category_id]&id=[id]\r\ncontent:内容', 0, '', '', 1383891243, 1387260622, 1, 'MyISAM'),
(3, 'download', '下载', 1, '', 1, '{"1":["3","28","30","32","2","5","31"],"2":["13","10","27","9","12","16","17","19","11","20","14","29"]}', '1:基础,2:扩展', '', '', '', '', 'id:编号\r\ntitle:标题', 0, '', '', 1383891252, 1387260449, 1, 'MyISAM');

DROP TABLE IF EXISTS `opensns_module`;
CREATE TABLE IF NOT EXISTS `opensns_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '模块名',
  `alias` varchar(30) NOT NULL COMMENT '中文名',
  `version` varchar(20) NOT NULL COMMENT '版本号',
  `is_com` tinyint(4) NOT NULL COMMENT '是否商业版',
  `show_nav` tinyint(4) NOT NULL COMMENT '是否显示在导航栏中',
  `summary` varchar(200) NOT NULL COMMENT '简介',
  `developer` varchar(50) NOT NULL COMMENT '开发者',
  `website` varchar(200) NOT NULL COMMENT '网址',
  `entry` varchar(50) NOT NULL COMMENT '前台入口',
  `is_setup` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否已安装',
  `sort` int(11) NOT NULL COMMENT '模块排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `name_2` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模块管理表' AUTO_INCREMENT=30 ;

INSERT INTO `opensns_module` (`id`, `name`, `alias`, `version`, `is_com`, `show_nav`, `summary`, `developer`, `website`, `entry`, `is_setup`, `sort`) VALUES
(21, 'Blog', '资讯', '1.0.0', 0, 1, '原OneThink的内容模块，OpenSNS实现了前台界面，传统的CMS模块', '上海顶想信息科技有限公司', 'http://www.topthink.net/', 'Blog/index/index', 1, 0),
(22, 'Event', '活动', '1.0.0', 0, 1, '活动模块，用户可以发起活动', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Event/index/index', 0, 0),
(23, 'Forum', '论坛', '1.0.0', 0, 1, '论坛模块，比较简单的论坛模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Forum/index/index', 1, 0),
(24, 'Group', '群组', '1.0.0', 0, 1, '群组模块，允许用户建立自己的圈子', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Group/index/index', 0, 0),
(25, 'Home', '网站主页模块', '1.0.0', 0, 1, '首页模块，主要用于展示网站内容', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Home/index/index', 1, 0),
(26, 'Issue', '专辑', '1.0.0', 0, 1, '专辑模块，适用于精品内容展示', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Issue/index/index', 1, 0),
(27, 'People', '会员展示', '1.0.0', 0, 1, '会员展示模块，可以用于会员的查找', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'People/index/index', 1, 0),
(28, 'Shop', '积分商城', '1.0.0', 0, 1, '积分商城模块，用户可以使用积分兑换商品', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Shop/index/index', 0, 0),
(29, 'Weibo', '微博', '1.0.0', 0, 1, '微博模块，用户可以发布微博', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Weibo/index/index', 1, 0);

DROP TABLE IF EXISTS `opensns_picture`;
CREATE TABLE IF NOT EXISTS `opensns_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `type` varchar(50) NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

DROP TABLE IF EXISTS `opensns_rank`;
CREATE TABLE IF NOT EXISTS `opensns_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '上传者id',
  `title` varchar(50) NOT NULL,
  `logo` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `types` tinyint(2) NOT NULL DEFAULT '1' COMMENT '前台是否可申请',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS `opensns_rank_user`;
CREATE TABLE IF NOT EXISTS `opensns_rank_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `is_show` tinyint(4) NOT NULL COMMENT '是否显示在昵称右侧（必须有图片才可）',
  `create_time` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS `opensns_seo_rule`;
CREATE TABLE IF NOT EXISTS `opensns_seo_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `app` varchar(40) NOT NULL,
  `controller` varchar(40) NOT NULL,
  `action` varchar(40) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `seo_keywords` text NOT NULL,
  `seo_description` text NOT NULL,
  `seo_title` text NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

INSERT INTO `opensns_seo_rule` (`id`, `title`, `app`, `controller`, `action`, `status`, `seo_keywords`, `seo_description`, `seo_title`, `sort`) VALUES
(4, '整站标题', '', '', '', 1, '', '', 'OpenSNS', 7),
(6, '论坛版块页', 'forum', 'index', 'forum', 1, '{$forum.title} ', '{$forum.title} ', '{$forum.title} —— OpenSNS论坛', 2),
(7, '微博首页', 'Weibo', 'Index', 'index', 1, '微博', '微博首页', 'OpenSNS轻量化社交框架', 5),
(8, '微博详情页', 'Weibo', 'Index', 'weiboDetail', 1, '{$weibo.title|op_t},OpenSNS,ox,微博', '{$weibo.content|op_t}\r\n', '{$weibo.content|op_t}——OpenSNS微博', 6),
(9, '用户中心', 'usercenter', 'index', 'index', 1, '{$user_info.nickname|op_t},OpenSNS', '{$user_info.username|op_t}的个人主页', '{$user_info.nickname|op_t}的个人主页', 3),
(10, '会员页面', 'people', 'index', 'index', 1, '会员', '会员', '会员', 4),
(11, '论坛帖子详情页', 'forum', 'index', 'detail', 1, '{$post.title|op_t},论坛,opensns', '{$post.title|op_t}', '{$post.title|op_t} —— OpenSNS论坛', 1),
(12, '商城首页', 'shop', 'index', 'index', 1, '商城,积分', '积分商城', '商城首页——OpenSNS', 0),
(13, '商城商品详情页', 'shop', 'index', 'goodsdetail', 1, '{$content.goods_name|op_t},商城', '{$content.goods_name|op_t}', '{$content.goods_name|op_t}——OpenSNS商城', 0),
(14, '资讯首页', 'blog', 'index', 'index', 1, '资讯首页', '资讯首页\r\n', '资讯——OpenSNS', 0),
(15, '资讯列表页', 'blog', 'article', 'lists', 1, '{$category.title|op_t}', '{$category.title|op_t}', '{$category.title|op_t}', 0),
(16, '资讯文章页', 'blog', 'article', 'detail', 1, '{$info.title|op_t}', '{$info.title|op_t}', '{$info.title|op_t}——OpenSNS', 0),
(17, '活动首页', 'event', 'index', 'index', 1, '活动', '活动首页', '活动首页——OpenSNS', 0),
(18, '活动详情页', 'event', 'index', 'detail', 1, '{$content.title|op_t}', '{$content.title|op_t}', '{$content.title|op_t}——OpenSNS', 0),
(19, '专辑首页', 'issue', 'index', 'index', 1, '专辑', '专辑首页', '专辑首页——OpenSNS', 0),
(20, '专辑详情页', 'issue', 'index', 'issuecontentdetail', 1, '{$content.title|op_t}', '{$content.title|op_t}', '{$content.title|op_t}——OpenSNS', 0);

DROP TABLE IF EXISTS `opensns_super_links`;
CREATE TABLE IF NOT EXISTS `opensns_super_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '类别（1：图片，2：普通）',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '站点名称',
  `cover_id` int(10) NOT NULL COMMENT '图片ID',
  `link` char(140) NOT NULL DEFAULT '' COMMENT '链接地址',
  `level` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='友情连接表' AUTO_INCREMENT=8 ;

INSERT INTO `opensns_super_links` (`id`, `type`, `title`, `cover_id`, `link`, `level`, `status`, `create_time`) VALUES
(5, 2, '想天科技', 0, 'http://www.ourstu.com', 0, 1, 1407156786),
(6, 2, 'Onethink', 0, 'http://www.onethink.cn', 0, 1, 1407156813),
(10, 2, 'OpenCenter', 0, 'http://www.ocenter.cn', 0, 1, 1407156813),
(7, 1, 'OpenSNS', 92, 'http://www.opensns.cn', 0, 1, 1407156830);

DROP TABLE IF EXISTS `opensns_support`;
CREATE TABLE IF NOT EXISTS `opensns_support` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appname` varchar(20) NOT NULL COMMENT '应用名',
  `row` int(11) NOT NULL COMMENT '应用标识',
  `uid` int(11) NOT NULL COMMENT '用户',
  `create_time` int(11) NOT NULL COMMENT '发布时间',
  `table` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支持的表' AUTO_INCREMENT=52 ;

DROP TABLE IF EXISTS `opensns_sync_login`;
CREATE TABLE IF NOT EXISTS `opensns_sync_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type_uid` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `oauth_token` varchar(255) NOT NULL,
  `oauth_token_secret` varchar(255) NOT NULL,
  `is_sync` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `opensns_talk`;
CREATE TABLE IF NOT EXISTS `opensns_talk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL,
  `uids` varchar(100) NOT NULL,
  `appname` varchar(30) NOT NULL,
  `apptype` varchar(30) NOT NULL,
  `source_id` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `source_title` varchar(100) NOT NULL,
  `source_content` text NOT NULL,
  `source_url` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `message_id` int(11) NOT NULL,
  `other_uid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会话表' AUTO_INCREMENT=164 ;

DROP TABLE IF EXISTS `opensns_talk_message`;
CREATE TABLE IF NOT EXISTS `opensns_talk_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(500) NOT NULL,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `talk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='聊天消息表' AUTO_INCREMENT=278 ;

DROP TABLE IF EXISTS `opensns_talk_message_push`;
CREATE TABLE IF NOT EXISTS `opensns_talk_message_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `source_id` int(11) NOT NULL COMMENT '来源消息id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL,
  `talk_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='状态，0为未提示，1为未点击，-1为已点击' AUTO_INCREMENT=111 ;

DROP TABLE IF EXISTS `opensns_talk_push`;
CREATE TABLE IF NOT EXISTS `opensns_talk_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '接收推送的用户id',
  `source_id` int(11) NOT NULL COMMENT '来源id',
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '状态，0为未提示，1为未点击，-1为已点击',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='对话推送表' AUTO_INCREMENT=98 ;

DROP TABLE IF EXISTS `opensns_ucenter_admin`;
CREATE TABLE IF NOT EXISTS `opensns_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=3 ;

DROP TABLE IF EXISTS `opensns_ucenter_member`;
CREATE TABLE IF NOT EXISTS `opensns_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=58 ;


DROP TABLE IF EXISTS `opensns_ucenter_setting`;
CREATE TABLE IF NOT EXISTS `opensns_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `opensns_url`;
CREATE TABLE IF NOT EXISTS `opensns_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表' AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `opensns_user_token`;
CREATE TABLE IF NOT EXISTS `opensns_user_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

DROP TABLE IF EXISTS `opensns_weibo`;
CREATE TABLE IF NOT EXISTS `opensns_weibo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_top` tinyint(4) NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `repost_count` int(11) NOT NULL,
  `from` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=405 ;

DROP TABLE IF EXISTS `opensns_weibo_comment`;
CREATE TABLE IF NOT EXISTS `opensns_weibo_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `weibo_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `to_comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

DROP TABLE IF EXISTS `opensns_weibo_top`;
CREATE TABLE IF NOT EXISTS `opensns_weibo_top` (
  `weibo_id` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`weibo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='置顶微博表';
