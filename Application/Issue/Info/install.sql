-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 11 月 18 日 13:30
-- 服务器版本: 5.5.38
-- PHP 版本: 5.3.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";




--
-- 数据库: `110`
--

-- --------------------------------------------------------

--
-- 表的结构 `opensns_issue`
--

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

--
-- 转存表中的数据 `opensns_issue`
--

INSERT INTO `opensns_issue` (`id`, `title`, `create_time`, `update_time`, `status`, `allow_post`, `pid`, `sort`) VALUES
(13, '默认专辑', 1409712474, 1409712467, 1, 0, 0, 0),
(14, '默认二级', 1409712480, 1409712475, 1, 0, 13, 0);

-- --------------------------------------------------------

--
-- 表的结构 `opensns_issue_content`
--

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

--
-- 转存表中的数据 `opensns_issue_content`
--
INSERT INTO `opensns_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '专辑', 0, 22, 'Issue/issue', 0, '', '', 0);

set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where title = '专辑';

INSERT INTO `opensns_menu` ( `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '编辑专辑', @tmp_id, 0, 'Issue/add', 1, '', '专辑', 0),
( '专辑管理', @tmp_id, 0, 'Issue/issue', 0, '', '专辑', 0),
( '专辑回收站', @tmp_id, 4, 'Issue/issueTrash', 0, '', '专辑', 0),
( '专辑操作', @tmp_id, 0, 'Issue/operate', 1, '', '专辑', 0),
( '内容审核', @tmp_id, 1, 'Issue/verify', 0, '', '内容管理', 0),
( '内容回收站', @tmp_id, 5, 'Issue/contentTrash', 0, '', '内容管理', 0),
( '内容管理', @tmp_id, 0, 'Issue/contents', 0, '', '内容管理', 0),
( '专辑设置', @tmp_id, 0, 'Issue/config', 0, '', '设置', 0),
( '设置专辑状态', @tmp_id, 0, 'Issue/setIssueContentStatus', 1, '', '', 0);

INSERT INTO `opensns_auth_rule` ( `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
( 'Issue', 1, 'addIssueContent', '专辑投稿权限', 1, ''),
( 'Issue', 1, 'editIssueContent', '编辑专辑内容（管理）', 1, '');
