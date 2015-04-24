SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `opensns_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(25) NOT NULL COMMENT '商品名称',
  `goods_ico` int(11) NOT NULL COMMENT '商品图标',
  `goods_introduct` varchar(100) NOT NULL COMMENT '商品简介',
  `goods_detail` varchar(1000) NOT NULL COMMENT '商品详情',
  `tox_money_need` int(11) NOT NULL COMMENT '需要金币数',
  `goods_num` int(11) NOT NULL COMMENT '商品余量',
  `changetime` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '状态，-1：删除；0：禁用；1：启用',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `category_id` int(11) NOT NULL,
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为新品',
  `sell_num` int(11) NOT NULL DEFAULT '0' COMMENT '已出售量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品信息' AUTO_INCREMENT=14 ;



CREATE TABLE IF NOT EXISTS `opensns_shop_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `address` varchar(200) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `change_time` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `phone` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `opensns_shop_buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `goods_num` int(11) NOT NULL COMMENT '购买数量',
  `status` tinyint(4) NOT NULL COMMENT '状态，-1：未领取；0：申请领取；1：已领取',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `createtime` int(11) NOT NULL COMMENT '购买时间',
  `gettime` int(11) NOT NULL COMMENT '交易结束时间',
  `address_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='购买商品信息表' AUTO_INCREMENT=55 ;

CREATE TABLE IF NOT EXISTS `opensns_shop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `opensns_shop_category` (`id`, `title`, `create_time`, `update_time`, `status`, `pid`, `sort`) VALUES
(1, '奖品', 1403507725, 1403507717, 1, 0, 0);

CREATE TABLE IF NOT EXISTS `opensns_shop_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ename` varchar(25) NOT NULL COMMENT '标识',
  `cname` varchar(25) NOT NULL COMMENT '中文名称',
  `changetime` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商店配置' AUTO_INCREMENT=3 ;

INSERT INTO `opensns_shop_config` (`id`, `ename`, `cname`, `changetime`) VALUES
(1, 'tox_money', '金币', 1403507688),
(2, 'min_sell_num', '10', 1403489181);

CREATE TABLE IF NOT EXISTS `opensns_shop_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `opensns_shop_see` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;


--
-- 转存表中的数据 `opensns_issue_content`
--
INSERT INTO `opensns_menu` (`title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '积分商城', 0, 22, 'Shop/shopCategory', 0, '', '', 0);

set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where title = '积分商城';


INSERT INTO `opensns_menu` ( `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '货币配置', @tmp_id, 8, 'Shop/toxMoneyConfig', 0, '', '商城配置', 0),
( '商品列表', @tmp_id, 1, 'Shop/goodsList', 0, '', '商品管理', 0),
( '添加、编辑商品', @tmp_id, 0, 'Shop/goodsEdit', 1, '', '', 0),
( '商品分类配置', @tmp_id, 2, 'Shop/shopCategory', 0, '', '商城配置', 0),
( '商品分类添加', @tmp_id, 0, 'Shop/add', 1, '', '', 0),
( '商品分类操作', @tmp_id, 0, 'Shop/operate', 1, '', '', 0),
( '商品分类回收站', @tmp_id, 3, 'Shop/categoryTrash', 0, '', '商城配置', 0),
( '商品回收站', @tmp_id, 7, 'Shop/goodsTrash', 0, '', '商品管理', 0),
( '商品状态设置', @tmp_id, 0, 'Shop/setGoodsStatus', 1, '', '', 0),
( '商品分类状态设置', @tmp_id, 0, 'Shop/setStatus', 1, '', '', 0),
( '交易成功记录', @tmp_id, 5, 'Shop/goodsBuySuccess', 0, '', '交易管理', 0),
( '待发货交易', @tmp_id, 4, 'Shop/verify', 0, '', '交易管理', 0),
( '商城信息记录', @tmp_id, 0, 'Shop/shopLog', 0, '', '商城记录', 0),
( '热销商品阀值配置', @tmp_id, 0, 'Shop/hotSellConfig', 0, '', '商城配置', 0),
( '设置新品', @tmp_id, 0, 'Shop/setNew', 1, '', '', 0);