-- -----------------------------
-- 表结构 `ocenter_cat_data`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`data_id`)
) ENGINE=MyISAM AUTO_INCREMENT=555 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_cat_entity`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_entity` (
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
-- 表结构 `ocenter_cat_fav`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_fav` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_cat_field`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_field` (
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
  `search_show` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_cat_info`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `read` int(11) NOT NULL,
  `sub` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `over_time` int(11) NOT NULL COMMENT '截止时间',
  `rate` float NOT NULL,
  `top` tinyint(4) NOT NULL,
  `recom` tinyint(4) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `cover` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_cat_rate`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_rate` (
  `rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  `score` float NOT NULL,
  PRIMARY KEY (`rate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_cat_read`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_read` (
  `read_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cTime` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  PRIMARY KEY (`read_id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_cat_send`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_cat_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_uid` int(11) NOT NULL,
  `rec_uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `s_info_id` int(11) NOT NULL,
  `info_id` int(11) NOT NULL,
  `readed` tinyint(4) NOT NULL,
  `content` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- -----------------------------
-- 表内记录 `ocenter_cat_entity`
-- -----------------------------
INSERT INTO `ocenter_cat_entity` VALUES ('1', 'Job', '3,1', '3,1', '', '', '<a href=\"{$[url]}\">{$name}</a>', '岗位', '<div id=\"col5\" class=\"left cat_yunmai_d_left\">\n<div class=\"pd20\">\n<h2 class=\"cat_yunme_title\">\n{$j_name}\n</h2>\n<div class=\"c999 mt5\">\n{$[cTime]}\n</div>\n<div class=\"br5 cat_yunmai_det\">\n<ul>\n<li class=\"cat_yunmai_co\">公司名称：{$company}</li>\n<li>工作地点：{$place}</li>\n<li>薪资待遇：{$reward}</li>\n<li>招聘人数：{$num}</li>\n<li>EMail:{$email}</li>\n</ul>\n<div class=\"clearfix\"></div>\n</div>\n<h3 class=\"cat_yunmai_title\">\n职位描述：\n</h3>\n<div class=\"cat_yunmai_des\">\n{$des}\n</div>\n<div class=\"clearfix underline mb10 mt10\"></div>\n{$[fav_btn]}\n</div>\n</div>\n<div id=\"col3\" class=\"left \">\n<div class=\"pd20\">\n<div class=\"\"><a target=\"_blank\" class=\"left mr10\" href=\"{$[user_space_url]}\" event-node=\"face_card\" uid=\"{$[user_uid]}\"><img class=\"br3\" src=\"{$[user_avatar_small]}\"><br/>\n<div class=\"cat_uname\" style=\"text-align:left\">{$[user_uname]}</a>\n<br/>\n<span class=\"c333\">{$[user_location]}</span>\n</div>\n\n</div>\n</div></div>\n<div class=\"clearfix\"></div>', '<div class=\"mb10 pd10 underline\">\r\n<div class=\"mb10\"><a href=\"{$[url]}\" class=\"f16px cblue\" style=\"margin-right:10px\">{$[title]}</a> <span class=\"cgrey f14px\">待遇：{$reward}  \r\n工作地点： {$place}</span>\r\n  {$[fav_btn]}\r\n</div>\r\n<div class=\"cat_ul_tm mb10\">{$[cTime]}</div>\r\n<div class=\"cat_des \">{$des}\r\n\r\n</div>\r\n\r\n<div class=\"cat_head_pic\"><a target=\"_blank\" href=\"{$[user_space_url]}\" ucard=\"{$[user_uid]}\" style=\"float:left;\"><img class=\"cat_head_size\" src=\"{$[user_avatar32]}\" style=\"border-radius:100%;\"><br/>\r\n<div class=\"cat_uname\"  style=\"line-height:32px;margin-left:40px;\">{$[user_nickname]}</a></div>\r\n</div>\r\n<div class=\"clearfix\"/>\r\n\r\n\r\n</div>', '0', '0', '<div class=\"right_box\">\r\n	<div class=\"boxInvite br5\">\r\n		<h3>\r\n			小提示\r\n		</h3>\r\n宗旨是更开放，更分享，更合理，如何更好的发布职位动态，云招聘来给您支几招：<br />\r\n<br />\r\n1，尽量详细的介绍关于即将发布职位的信息，让应聘者在第一时间了解更多关于企业的信息<br />\r\n<br />\r\n2，在职位描述中填写关于职位的就职要求，公司其他待遇之类，可以吸引应聘者的信息。为了展示更多信息，在职位描述中可以附图，图文并茂最佳。<br />\r\n	</div>\r\n</div>', '0', '', '0', '1', '1', '1', '0', '0', '', '0', '1');
INSERT INTO `ocenter_cat_entity` VALUES ('2', 'House', '1', '1', '', '<div class=\"fe_main\">\r\n<div class=\"left mg10\"><img src=\"{$zhaopian1}\" class=\"pic1\"></div>\r\n        <div class=\"left mg10 fe_detail\">\r\n            <div class=\"fe_title mb10\"><a href=\"{$[url]}\"  >{$[title]}</a></div>\r\n            <div class=\"fe_p\">\r\n                {$yijuhua}<br/>{$daxiao}平米\r\n\r\n            </div>\r\n\r\n        </div>\r\n        <div class=\"left mg10 fe_m\">\r\n            <div class=\"fe_money\">\r\n                {$zujin}\r\n            </div>\r\n            <div class=\"fe_det\">\r\n                {$shi}室{$ting}厅{$wei}卫\r\n            </div>\r\n        </div>\r\n        <div class=\"left mg10 fe_time\">\r\n            {$[cTime]}\r\n        </div>\r\n<div class=\"clearfix\"></div>\r\n</div>', '', '房产', '', '<div class=\"mb10 pd10 underline\">\r\n<div class=\"mb10\"><a href=\"{$[url]}\" class=\"f16px cblue\" style=\"margin-right:10px\">{$[title]}</a> <span class=\"cgrey f14px\">租金：{$zujin}  \r\n形式： {$zhifu}</span></div>\r\n<div class=\"cat_ul_tm mb10\">{$[cTime]}</div>\r\n<div class=\"cat_des\">{$des}</div><div class=\"cat_head_pic\"><a target=\"_blank\" href=\"{$[user_space_url]}\" ucard=\"{$[user_uid]}\" style=\"float:left;\"><img class=\"cat_head_size\" src=\"{$[user_avatar32]}\" style=\"border-radius:100%;\"><br/>\r\n<div class=\"cat_uname\"  style=\"line-height:32px;margin-left:40px;\">{$[user_nickname]}</a></div>\r\n</div>\r\n<div class=\"clearfix\"/>\r\n\r\n</div>', '0', '0', '', '', '', '1', '1', '1', '1', '500', '0', '', '0', '1');
INSERT INTO `ocenter_cat_entity` VALUES ('3', 'PTJob', '0', '0', '', '', '', '兼职', '', '', '0', '-1', '', '', '', '1', '1', '1', '1', '0', '0', '', '0', '1');
INSERT INTO `ocenter_cat_entity` VALUES ('5', 'jianli', '0', '0', '', '', '', '简历', '', '<div>a{$xingbie}</div>\r\n<a href=\"{$[url]}\">{$xingming}</a>\r\n空闲的上午{$shangwukongxian}', '0', '-1', '<p>\r\n	<br />\r\n</p>', '', '', '0', '1', '1', '1', '0', '0', '', '0', '1');
INSERT INTO `ocenter_cat_entity` VALUES ('6', 'good', '1', '1', '', '', '', '商品', '', '', '0', '-1', '', '', '', '0', '1', '1', '1', '1', '0', '', '0', '-1');
INSERT INTO `ocenter_cat_entity` VALUES ('7', 'food', '1', '1', '', '', '', '餐厅', '', '', '0', '-1', '', '', '', '0', '1', '1', '1', '66', '0', '5', '1', '-1');
INSERT INTO `ocenter_cat_entity` VALUES ('8', 'test', '1', '1', '', '', '', '测试模型', '', '', '0', '-1', '', '', '', '0', '1', '1', '1', '0', '0', '2,3,5', '0', '-1');
-- -----------------------------
-- 表内记录 `ocenter_cat_field`
-- -----------------------------
INSERT INTO `ocenter_cat_field` VALUES ('1', '0', '', '', '', '', '', '1', '岗位名', 'j_name', '1', '1000', '0', '0', '', '岗位名称', 'min=2&max=20&error=必须输入2-20个汉字', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('2', '2', '1000-2000元\r\n2001-5000元\r\n5001-20000元\r\n面议', '', '', '', '', '1', '待遇', 'reward', '1', '0', '0', '0', '', '', 'need=1&error=必须选择待遇', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('3', '2', '上海\r\n杭州\r\n北京\r\n广州\r\n嘉兴', '', '', '', '', '1', '地点', 'place', '1', '0', '0', '0', '', '', 'need=1&error=必须选择地点', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('4', '6', '', '', '', '', '', '1', '描述', 'des', '1', '-4', '1', '0', '', '', '', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('5', '0', '', '', '', '', '', '1', '公司名称', 'company', '1', '2', '0', '0', '', '', 'min=4&max=40&error=只能输入4-40个汉字', '1', '1');
INSERT INTO `ocenter_cat_field` VALUES ('6', '0', '', '', '', '', '', '0', '联系方式', 'phone', '1', '0', '0', '0', '', '', 'min=1&error=必须填写联系方式', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('7', '0', '', '', '', '', '', '0', 'Email', 'email', '1', '0', '0', '0', '', '', 'min=1&error=必须填写电子邮箱', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('8', '0', '', '', '', '', '', '1', '招聘人数', 'num', '1', '0', '0', '0', '', '', 'min=1&error=必须填写招聘人数', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('9', '0', '', '', '', '', '', '1', '室', 'shi', '2', '997', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('10', '0', '', '', '', '', '', '1', '卫', 'wei', '2', '996', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('11', '0', '', '', '', '', '', '0', '大小', 'daxiao', '2', '995', '0', '0', '', '平米', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('12', '0', '', '', '', '', '', '1', '楼层', 'louceng', '2', '992', '0', '0', '第  层，共  层', '写清楚第几层，共几层', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('13', '6', '', '', '', '', '', '0', '描述', 'des', '2', '0', '1', '0', '', '', '', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('14', '0', '', '', '', '', '', '1', '标题', 'title', '3', '1000', '0', '0', '', '请输入标题', 'min=1&error=请输入内容', '0', '-1');
INSERT INTO `ocenter_cat_field` VALUES ('15', '6', '', '', '', '', '', '0', '内容', 'des', '5', '0', '0', '0', '', '', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('16', '6', '', '', '', '', '', '1', '介绍', 'jieshao', '3', '-999', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('17', '0', '', '', '', '', '', '1', '工作地点', 'pos', '3', '0', '0', '0', '', '', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('18', '0', '', '', '', '', '', '0', '联系方式', 'contact', '3', '0', '0', '1', '', '', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('19', '0', '', '', '', '', '', '0', '姓名', 'xingming', '6', '100', '0', '0', '', '2-10个汉字', 'min=2&max=10&error=请输入姓名,2-10个汉字', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('20', '3', '男\r\n女', '', '', '', '', '1', '性别', 'xingbie', '6', '99', '0', '0', '', '', 'need=1&error=请选择性别', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('21', '5', '', '', '', '', '', '0', '加入日期', 'jiaruriqi', '6', '99', '0', '0', '', '', 'need=1&error=请选择加入日期', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('22', '1', '', '', '', '', '', '0', '兼职经历', 'jianzhijingli', '6', '98', '0', '0', '', '请把自己的兼职经历填写一下', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('23', '0', '', '', '', '', '', '0', '户籍地址', 'hujidizhi', '6', '95', '0', '0', '', '', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('24', '0', '', '', '', '', '', '0', '身高', 'shengao', '6', '91', '0', '0', '', 'cm', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('25', '0', '', '', '', '', '', '0', '体重', 'tizhong', '6', '90', '0', '0', '', 'kg', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('26', '0', '', '', '', '', '', '0', '手机号码', 'shoujihaoma', '6', '90', '0', '0', '', '', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('27', '0', '', '', '', '', '', '0', 'QQ号码', 'qqhaoma', '6', '89', '0', '0', '', '', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('28', '0', '', '', '', '', '', '0', '身份证号', 'shenfenzhenghao', '6', '87', '0', '0', '', '', 'min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('29', '3', '有\r\n无', '', '', '', '', '0', '有无电脑', 'youwudiannao', '6', '85', '0', '0', '', '', 'need=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('30', '6', '', '', '', '', '', '0', '自我介绍', 'ziwojieshao', '6', '84', '0', '0', '', '用一句话介绍自己', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('31', '5', '', '', '', '', '', '0', '生日', 'shengri', '6', '84', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('32', '4', '周一\r\n周二\r\n周三\r\n周四\r\n周五\r\n周六\r\n周日', '', '', '', '', '1', '空闲上午', 'shangwukongxian', '6', '70', '0', '0', '', '选择有空的时间', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('33', '4', '{\"1\":\"参数1\",\"2\":\"参数2\",\"3\":\"参数3\"}', '', '', '', '', '1', '多选', 'duoxuan', '7', '0', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '-1');
INSERT INTO `ocenter_cat_field` VALUES ('34', '7', '', '', '', '', '', '0', '个人照片', 'zhaopian', '6', '99', '0', '0', '', '个人照片，清晰免冠照', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('35', '4', '周一\r\n周二\r\n周三\r\n周四\r\n周五\r\n周六\r\n周日', '', '', '', '', '1', '下午有空', 'xiawuyoukong', '6', '0', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('36', '4', '发单\r\n派发\r\n促销\r\n市调\r\n礼仪\r\n模特\r\n舞蹈\r\n歌手\r\n乐器\r\n校园代理\r\n服从调配', '', '', '', '', '1', '兼职意向', 'jianzhiyixiang', '6', '0', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('37', '0', '', '', '', '', '', '1', '期望待遇', 'qiwangdaiyu', '6', '0', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('38', '0', '', '', '', '', '', '0', '未来发展城市', 'weilaifazhan', '6', '0', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('39', '3', '个人\r\n中介', '', '', '', '', '1', '身份', 'shenfen', '2', '1000', '0', '0', '', '', 'need=1&min=1&error=请选择身份', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('40', '2', '整套出租\r\n单间出租', '', '', '', '', '1', '出租方式', 'fangshi', '2', '999', '0', '0', '', '', 'need=1&min=1&error=请选择出租方式', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('41', '0', '', '', '', '', '', '1', '小区名称', 'xiaoqu', '2', '998', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('42', '0', '', '', '', '', '', '1', '厅', 'ting', '2', '997', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('43', '2', '普通住宅\r\n商住两用\r\n公寓\r\n平房\r\n别墅\r\n其他', '', '', '', '', '1', '类型', 'leixing', '2', '991', '1', '0', '', '', 'need=1', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('44', '2', '毛坯\r\n简单装修\r\n中等装修\r\n精装修\r\n豪华装修', '', '', '', '', '1', '装修情况', 'zhuangxiu', '2', '988', '1', '0', '', '', '', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('45', '0', '', '', '', '', '', '1', '朝向', 'chaoxiang', '2', '977', '0', '0', '', '', 'need=1&min=1&error=请选择朝向', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('46', '0', '', '', '', '', '', '1', '租金', 'zujin', '2', '955', '0', '0', '面议', '最好写清楚价格', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('47', '0', '', '', '', '', '', '0', '支付方式', 'zhifu', '2', '944', '0', '0', '', '写清楚押几付几', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('48', '0', '', '', '', '', '', '1', '标题', 'biaoti', '2', '99999', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '-1');
INSERT INTO `ocenter_cat_field` VALUES ('49', '0', '', '', '', '', '', '0', '一句话广告', 'yijuhua', '2', '901', '0', '0', '', '', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('50', '7', '', '', '', '', '', '0', '照片1', 'zhaopian1', '2', '889', '1', '0', '', '', 'need=1', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('51', '7', '', '', '', '', '', '0', '照片2', 'zhaopian2', '2', '888', '1', '0', '', '', '', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('52', '7', '', '', '', '', '', '0', '照片3', 'zhaopian3', '2', '887', '1', '0', '', '', '', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('53', '7', '', '', '', '', '', '0', '照片4', 'zhaopian4', '2', '886', '1', '0', '', '', '', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('54', '7', '', '', '', '', '', '0', '照片5', 'zhaopian5', '2', '885', '1', '0', '', '', '', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('55', '0', '', '', '', '', '', '0', '联系电话', 'lianxidianhua', '2', '884', '0', '1', '', '输入手机或者座机号码', 'need=1&min=1&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('56', '0', '', '', '', '', '', '0', '联系人', 'lianxiren', '2', '883', '0', '0', '', '联系人的称呼', 'need=1&min=1&error=请输入', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('57', '1', '', '', '', '', '', '1', '内容', 'content', '4', '0', '0', '0', '', '', 'need=1&min=1&max=2000&error=请输入内容', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('58', '0', '132213', '', '', '', '', '0', '面积', 'size', '1', '0', '1', '0', '', '213', '123', '0', '0');
INSERT INTO `ocenter_cat_field` VALUES ('59', '4', '张三\r\n李四\r\n王五', '', '', '', '', '0', '尺寸', 'size', '7', '0', '1', '0', '213', '213', '31', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('60', '4', 'A\r\nB\r\nC', '', '', '', '', '0', 'checkbox', 'check', '8', '0', '0', '0', '', '选择abc', 'need=1&error=必选', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('61', '6', '', '', '', '', '', '0', '图片', 'pic', '8', '0', '0', '0', '', '图片上传', 'need=1&error=测试', '0', '1');
INSERT INTO `ocenter_cat_field` VALUES ('62', '1', '', '', '', '', '', '0', 'ta', 'textarea', '8', '0', '0', '0', '', '请输入内容', 'min=1&max=20&error=请填写', '0', '1');
-- -----------------------------
-- 表内记录 `ocenter_cat_rate`
-- -----------------------------
INSERT INTO `ocenter_cat_rate` VALUES ('1', '1', '1410251035', '21', '4');
INSERT INTO `ocenter_cat_rate` VALUES ('2', '1', '1410251114', '16', '4.5');
INSERT INTO `ocenter_cat_rate` VALUES ('3', '1', '1410255396', '11', '2.5');
INSERT INTO `ocenter_cat_rate` VALUES ('4', '1', '1410333905', '13', '5');
INSERT INTO `ocenter_cat_rate` VALUES ('5', '1', '1410415756', '30', '4.5');
INSERT INTO `ocenter_cat_rate` VALUES ('6', '1', '1411367705', '75', '3.5');
