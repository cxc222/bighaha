-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 11 月 18 日 13:45
-- 服务器版本: 5.5.38
-- PHP 版本: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `110`
--

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo`
--

CREATE TABLE IF NOT EXISTS `ocenter_weibo` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ocenter_weibo`
--

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_comment`
--

CREATE TABLE IF NOT EXISTS `ocenter_weibo_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `weibo_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `to_comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `ocenter_weibo_comment`
--


DROP TABLE IF EXISTS `ocenter_weibo_topic`;
CREATE TABLE IF NOT EXISTS `ocenter_weibo_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '话题名',
  `logo` int(11) NOT NULL DEFAULT '0' COMMENT '话题logo',
  `intro` varchar(255) NOT NULL COMMENT '导语',
  `qrcode` int(11) NOT NULL COMMENT '二维码',
  `uadmin` int(11) NOT NULL DEFAULT '0' COMMENT '话题管理   默认无',
  `read_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读',
  `is_top` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `weibo_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_top`
--

CREATE TABLE IF NOT EXISTS `ocenter_weibo_top` (
  `weibo_id` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`weibo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='置顶微博表';


--
-- 转存表中的数据 `ocenter_issue_content`
--
INSERT INTO `ocenter_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '微博', 0, 8, 'Weibo/weibo', 1, '', '', 0);

set @tmp_id=0;
select @tmp_id:= id from `ocenter_menu` where title = '微博';

INSERT INTO `ocenter_menu` ( `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '微博管理', @tmp_id, 1, 'Weibo/weibo', 0, '', '微博', 0),
( '回复管理', @tmp_id, 3, 'Weibo/comment', 0, '', '回复', 0),
( '编辑微博', @tmp_id, 0, 'Weibo/editWeibo', 1, '', '', 0),
( '编辑回复', @tmp_id, 0, 'Weibo/editComment', 1, '', '', 0),
( '微博回收站', @tmp_id, 2, 'Weibo/weiboTrash', 0, '', '微博', 0),
( '回复回收站', @tmp_id, 4, 'Weibo/commentTrash', 0, '', '回复', 0),
( '微博设置', @tmp_id, 0, 'Weibo/config', 0, '微博的基本配置', '设置', 0),
( '话题管理', @tmp_id, 0, 'Weibo/topic', 0, '微博的话题', '话题管理', 0);

delete from `ocenter_auth_rule` where  `module` = 'Weibo';
INSERT INTO `ocenter_auth_rule` ( `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
( 'Weibo', 1, 'Weibo/Index/doSend', '发微博', 1, ''),
( 'Weibo', 1, 'Weibo/Index/doDelWeibo', '删除微博(管理)', 1, ''),
( 'Weibo', 1, 'Weibo/Index/doSendRepost', '转发微博', 1, ''),
( 'Weibo', 1, 'Weibo/Index/doComment', '评论微博', 1, ''),
( 'Weibo', 1, 'Weibo/Index/doDelComment', '删除评论微博(管理)', 1, ''),
( 'Weibo', 1, 'Weibo/Index/setTop', '微博置顶(管理)', 1, ''),
( 'Weibo', 1, 'Weibo/Topic/beAdmin', '抢先成为话题主持人', 1, ''),
( 'Weibo', 1, 'Weibo/Topic/editTopic', '管理话题(管理)', 1, '');




INSERT INTO `ocenter_action` ( `name`, `title`, `remark`, `rule`, `log`, `type`, `status`, `update_time`, `module`) VALUES
( 'add_weibo', '发布微博', '新增微博', 'a:1:{i:0;a:5:{s:5:"table";s:6:"member";s:5:"field";s:1:"1";s:4:"rule";s:2:"+1";s:5:"cycle";s:2:"24";s:3:"max";s:1:"5";}}', '[user|get_nickname]在[time|time_format]发布了新微博：[record|intval]', 1, 1, 1428475575, 'Weibo'),
( 'add_weibo_comment', '添加微博评论', '添加微博评论', 'a:1:{i:0;a:5:{s:5:"table";s:6:"member";s:5:"field";s:1:"1";s:4:"rule";s:2:"+1";s:5:"cycle";s:2:"24";s:3:"max";s:1:"5";}}', '[user|get_nickname]在[time|time_format]添加了微博评论：[record|intval]', 1, 1, 1428475626, 'Weibo'),
( 'del_weibo_comment', '删除微博评论', '删除微博评论', '', '[user|get_nickname]在[time|time_format]删除了微博评论：[record|intval]', 1, 1, 1428399164, 'Weibo'),
( 'del_weibo', '删除微博', '删除微博', '', '[user|get_nickname]在[time|time_format]删除了微博：[record|intval]', 1, 1, 1428461334, 'Weibo'),
( 'set_weibo_top', '置顶微博', '置顶微博', '', '[user|get_nickname]在[time|time_format]置顶了微博：[record|intval]', 1, 1, 1428399164, 'Weibo'),
( 'set_weibo_down', '取消置顶微博', '取消置顶微博', '', '[user|get_nickname]在[time|time_format]取消置顶了微博：[record|intval]', 1, 1, 1428399164, 'Weibo');


INSERT INTO `ocenter_action_limit` ( `title`, `name`, `frequency`, `time_number`, `time_unit`, `punish`, `if_message`, `message_content`, `action_list`, `status`, `create_time`, `module`) VALUES
( 'add_weibo', '新增微博', 1, 10, 'second', 'warning', 0, '', '[add_weibo]', 1, 0, 'Weibo'),
( 'add_weibo_comment', '添加微博评论', 1, 10, 'second', 'warning', 0, '', '[add_weibo_comment]', 1, 0, 'Weibo');
