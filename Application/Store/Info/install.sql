-- -----------------------------
-- 表结构 `ocenter_store_category`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `ext` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `entity_id` int(11) NOT NULL COMMENT '绑定的属性模型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_com`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_com` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `content` text NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_data`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1101 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_entity`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `can_post_gid` varchar(50) NOT NULL,
  `can_read_gid` varchar(50) NOT NULL,
  `tpl3` text NOT NULL,
  `tpl1` text NOT NULL,
  `tpl2` text NOT NULL,
  `alias` varchar(20) NOT NULL,
  `tpl_detail` text NOT NULL,
  `tpl_list` text NOT NULL,
  `use_detail` int(11) NOT NULL,
  `use_list` int(11) NOT NULL,
  `des1` text NOT NULL,
  `des2` text NOT NULL,
  `des3` text NOT NULL,
  `can_over` int(11) NOT NULL COMMENT '允许设置截止日期',
  `show_nav` int(11) NOT NULL,
  `show_post` int(11) NOT NULL,
  `show_index` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `can_rec` tinyint(4) NOT NULL,
  `rec_entity` varchar(50) NOT NULL,
  `need_active` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_fav`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_fav` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_field`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `input_type` int(11) NOT NULL,
  `option` text NOT NULL,
  `limit1` varchar(500) NOT NULL,
  `limit2` varchar(500) NOT NULL,
  `limit3` varchar(500) NOT NULL,
  `limit4` varchar(500) NOT NULL,
  `can_search` int(11) NOT NULL,
  `alias` varchar(30) NOT NULL,
  `name` varchar(20) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `can_empty` int(11) NOT NULL,
  `over_hidden` int(11) NOT NULL COMMENT '到期后自动隐藏',
  `default_value` text NOT NULL,
  `tip` text NOT NULL,
  `args` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_goods`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `read` int(11) NOT NULL,
  `sub` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL COMMENT '扩展属性模型ID',
  `over_time` int(11) NOT NULL COMMENT '截止时间',
  `rate` float NOT NULL,
  `sell` int(11) NOT NULL COMMENT '总销量',
  `has` int(11) NOT NULL COMMENT '库存',
  `shop_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `update_time` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `cat1` int(11) NOT NULL COMMENT '一级分类',
  `cat2` int(11) NOT NULL COMMENT '二级分类',
  `cat3` int(11) NOT NULL COMMENT '三级分类',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `trans_fee` tinyint(4) NOT NULL COMMENT '运费形式，0买家承担运费，1卖家承担运费',
  `des` text NOT NULL COMMENT '商品描述',
  `cover_id` int(11) NOT NULL COMMENT '封面',
  `gallary` varchar(300) NOT NULL COMMENT '商品相册',
  `trans_fee_des` text NOT NULL COMMENT '运费描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_item`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `good_id` int(11) NOT NULL,
  `h_price` float NOT NULL,
  `cTime` int(11) NOT NULL,
  `h_name` varchar(50) NOT NULL,
  `order_id` int(11) NOT NULL,
  `h_price_bit` float NOT NULL,
  `count` int(11) NOT NULL,
  `h_pic` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_order`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `response` tinyint(4) NOT NULL COMMENT '评分 0好评 1中评 2差评',
  `content` varchar(400) NOT NULL COMMENT '评价内容',
  `r_pos` varchar(100) NOT NULL COMMENT '收货人地址',
  `r_code` varchar(6) NOT NULL COMMENT '收货人邮编',
  `r_phone` varchar(15) NOT NULL COMMENT '收货人电话号码',
  `condition` tinyint(4) NOT NULL COMMENT '状态 0未付款 1已付款 2已发货 3已完成',
  `trans_code` varchar(40) NOT NULL,
  `trans_name` varchar(20) NOT NULL COMMENT '快递名称',
  `r_name` varchar(20) NOT NULL,
  `s_uid` int(11) NOT NULL COMMENT '卖家uid',
  `total_cny` float NOT NULL,
  `total_count` int(11) NOT NULL,
  `adj_cny` float NOT NULL COMMENT '调整的价钱',
  `trans_time` int(11) NOT NULL,
  `response_time` int(11) NOT NULL COMMENT '评论时间',
  `attach` varchar(200) NOT NULL,
  `pay_time` int(11) NOT NULL COMMENT '付款时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8 COMMENT='订单表';


-- -----------------------------
-- 表结构 `ocenter_store_rate`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_rate` (
  `rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  `score` float NOT NULL,
  PRIMARY KEY (`rate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_read`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_read` (
  `read_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`read_id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_send`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_send` (
  `send_id` int(11) NOT NULL AUTO_INCREMENT,
  `send_uid` int(11) NOT NULL,
  `rec_uid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `s_info_id` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  `readed` tinyint(4) NOT NULL,
  PRIMARY KEY (`send_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_store_shop`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_store_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `summary` varchar(500) NOT NULL,
  `logo` int(11) NOT NULL,
  `position` varchar(20) NOT NULL,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `order_count` int(11) NOT NULL COMMENT '订单数',
  `visit_count` int(11) NOT NULL,
  `sell` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='商店表';

-- -----------------------------
-- 表内记录 `ocenter_store_category`
-- -----------------------------
INSERT INTO `ocenter_store_category` VALUES ('57', '冰箱', '1', '37', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('56', '电脑配件', '1', '40', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('55', '电脑整机', '1', '40', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('54', '小说', '1', '39', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('53', '经济管理', '1', '39', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('52', '工业计算机', '1', '39', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('51', '人文社科', '1', '39', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('50', '女装', '1', '38', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('49', '男装', '1', '38', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('48', '包包', '1', '42', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('47', '女鞋', '1', '42', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('46', '男鞋', '1', '42', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('45', '手机配件', '1', '41', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('44', '手机', '1', '41', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('43', '母婴用品', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('42', '鞋包', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('41', '手机数码', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('40', '电脑办公', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('39', '图书', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('38', '服装', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('37', '家用电器', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('58', '奶粉', '1', '43', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('59', '汽车户外', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('60', '汽车用品', '1', '59', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('61', '旅行野营', '1', '59', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('62', '食品', '1', '0', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('63', '进口食品', '1', '62', '', '1', '0');
INSERT INTO `ocenter_store_category` VALUES ('64', '油炸食品', '1', '62', '', '1', '0');
-- -----------------------------
-- 表内记录 `ocenter_store_entity`
-- -----------------------------
INSERT INTO `ocenter_store_entity` VALUES ('8', 'good', '', '', '', '', '', '商品', '', '', '-1', '-1', '请仔细填写你的商品信息。确保商品的信息真实可靠。否则我们随时可能会将其下架。', '', '', '0', '1', '0', '0', '50', '0', '', '0', '1');
