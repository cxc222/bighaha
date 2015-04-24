SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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

CREATE TABLE IF NOT EXISTS `opensns_forum_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
(2, '官方板块', 1, 1, 0);




/* menu 插入 */

INSERT INTO `opensns_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '论坛', 0, 22, 'Forum/index', 0, '', '', 0);

set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where title = '论坛';

INSERT INTO `opensns_menu` ( `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '板块管理', @tmp_id, 1, 'Forum/forum', 0, '', '板块', 0),
( '帖子管理', @tmp_id, 3, 'Forum/post', 0, '', '帖子', 0),
( '编辑／创建板块', @tmp_id, 0, 'Forum/editForum', 1, '', '', 0),
( '编辑帖子', @tmp_id, 0, 'Forum/editPost', 1, '', '', 0),
( '排序', @tmp_id, 0, 'Forum/sortForum', 0, '', '板块', 0),
( '新增/编辑回复', @tmp_id, 0, 'Forum/editReply',1, '', '', 0),
( '板块回收站', @tmp_id, 2, 'Forum/forumTrash', 0, '', '板块', 0),
( '帖子回收站', @tmp_id, 4, 'Forum/postTrash', 0, '', '帖子', 0),
( '回复回收站', @tmp_id, 6, 'Forum/replyTrash', 0, '', '回复', 0),
( '回复管理', @tmp_id, 6, 'Forum/reply', 0, '', '回复', 0),
( '论坛设置', @tmp_id, 10, 'Forum/config', 0, '', '设置', 0),
( '新增/编辑分类', @tmp_id, 0, 'Forum/addType', 1, '', '', 0),
( '设置分类状态', @tmp_id, 0, 'Forum/setTypeStatus', 1, '', '', 0),
( '分类管理', @tmp_id, 0, 'Forum/type', 0, '分类管理', '分类管理', 0);


