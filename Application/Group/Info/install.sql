CREATE TABLE IF NOT EXISTS `opensns_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `title` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `allow_user_group` text NOT NULL,
  `sort` int(11) NOT NULL,
  `logo` int(11) NOT NULL,
  `background` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `detail` text NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '圈子类型，0为公共的，1为私有的',
  `activity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `opensns_group_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `opensns_group_dynamic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `row_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `opensns_group_lzl_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `to_f_reply_id` int(11) NOT NULL,
  `to_reply_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `is_del` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `opensns_group_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `last_view` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `opensns_group_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `opensns_group_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
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
  `cate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `opensns_group_post_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `opensns_group_post_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parse` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `opensns_group_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `status` tinyint(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='圈子的分类表' AUTO_INCREMENT=2 ;

INSERT INTO `opensns_group_type` (`id`, `title`, `status`, `sort`, `create_time`) VALUES
(1, ' 分类一', 1, 0, 0);




/* menu 插入 */

INSERT INTO `opensns_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '群组', 0, 22, 'admin/group/index', 0, '', '', 0);

set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where title = '群组';

INSERT INTO `opensns_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '群组管理', @tmp_id, 0, 'admin/group/group', 0, '', '群组', 0),
( '分类管理', @tmp_id, 0, 'group/groupType', 0, '', '群组', 0),
( '文章分类', @tmp_id, 0, 'group/postType', 0, '', '文章', 0),
( '修改分类', @tmp_id, 0, 'group/editPostCate', 1, '', '文章', 0),
( '类型排序', @tmp_id, 0, 'group/sortPostCate', 1, '', '文章', 0),
( '修改群组分类', @tmp_id, 0, 'group/editGroupType', 1, '', '群组', 0),
( '群组类型排序', @tmp_id, 0, 'group/sortGroupType', 1, '', '群组', 0),
( '编辑楼中楼回复', @tmp_id, 0, 'group/editLzlReply', 1, '', '回复', 0),
( '楼中楼回复', @tmp_id, 0, 'group/lzlreply', 1, '', '回复', 0),
( '楼中楼回复回收站', @tmp_id, 0, 'group/lzlreplyTrash', 1, '', '回复', 0),
( '编辑回复', @tmp_id, 0, 'group/editReply', 1, '', '回复', 0),
( '群组回收站', @tmp_id, 0, 'group/groupTrash', 0, '', '群组', 0),
( '文章管理', @tmp_id, 0, 'group/post', 0, '', '文章', 0),
( '文章回收站', @tmp_id, 0, 'group/postTrash', 0, '', '文章', 0),
( '回复管理', @tmp_id, 0, 'group/reply', 0, '', '回复', 0),
( '回复回收站', @tmp_id, 0, 'group/replyTrash', 0, '', '回复', 0),
( '群组设置', @tmp_id, 0, 'group/config', 0, '', '设置', 0),
( '未审核群组', @tmp_id, 0, 'group/unverify', 0, '', '群组', 0);