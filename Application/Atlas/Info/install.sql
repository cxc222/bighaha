-- -----------------------------
-- 表结构 `big_atlas`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '发布者uid',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `image_id` int(11) NOT NULL COMMENT '关联图片id',
  `tag` varchar(80) NOT NULL COMMENT '标签',
  `addtime` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态, 0=需要审核,1=通过',
  `like_count` int(11) NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `unlike_count` int(11) NOT NULL DEFAULT '0' COMMENT '不喜欢数',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8 COMMENT='搞笑图集';


-- -----------------------------
-- 表结构 `big_atlas_collection`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL COMMENT '采集名称',
  `url` varchar(128) NOT NULL COMMENT '采集地址',
  `page` int(11) NOT NULL DEFAULT '1' COMMENT '页码',
  `start_id` int(11) NOT NULL DEFAULT '0' COMMENT '开始采集Id',
  `end_id` int(11) NOT NULL DEFAULT '0' COMMENT '结束采集id',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='可以采集列表';


-- -----------------------------
-- 表结构 `big_atlas_config`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(126) NOT NULL COMMENT '名称',
  `value` varchar(255) NOT NULL COMMENT '配置值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图集配置';


-- -----------------------------
-- 表结构 `big_atlas_like`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `big_atlas_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atlas_id` int(11) NOT NULL COMMENT '图集id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `type` int(11) NOT NULL COMMENT '类型, 1=支持,2=不支持',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='喜欢关系表';

-- -----------------------------
-- 表内记录 `big_atlas`
-- -----------------------------
INSERT INTO `big_atlas` VALUES ('2', '1', '某来自岛国的平衡大师', '4', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('3', '1', '妹子，技术不错。', '5', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('4', '1', '这…', '6', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('5', '1', '人的一生放荡不羁！', '7', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('6', '1', '幽怨的眼神', '8', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('7', '1', '好有爱~么么哒~', '9', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('8', '1', '公车地铁，上下高峰期最难顶十种人，你有没有中招！', '10', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('9', '1', '一只小猫被狗狗捡到之后❤☺', '11', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('10', '1', '西班牙·巴伦西亚举行的国际风筝节，满场的“风筝”完全就是群魔乱舞的节奏啊！麻麻这和我认识的风筝长的完全不一样啊！', '12', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('11', '1', '以下是精神分裂的症状，中了5条以上说明你有精分倾向，全中的可以考虑住院了?', '13', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('12', '1', '关于异地恋的各种看法?', '14', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('13', '1', '恐怖GIF图，慎点', '15', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('14', '1', '【50年后，再听老师上堂课】毕业50年后，30位年逾7旬的老同学又坐在了母校沈阳市第一中学的课堂上。时隔半个世纪，曾经少年已白头，身边的人还是当年的同桌，79岁的班主任再一次为他们上课。当年全班55名同学，有8位已经故去。光阴飞逝，有空的话你也给老同学打个招呼：你还好吗？', '16', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('15', '1', '“感受下我练的肌肉！”“恩！胸肌练的不错！”', '17', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('16', '1', '这是一个5岁男孩的故事， 他也许还不明白什么是流浪汉，流浪汉平常吃什么，流浪汉住哪。。。 但是，他用实际行动打动了所有人', '18', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('17', '1', '阿三哥又逆天了', '19', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('18', '1', '爱情好比两个人推一块石头，不是努力就行，还要确定你们是同一个方向。', '20', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('19', '1', '这年头，梦想是一定要有的，万一见鬼就实现了呢', '21', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('20', '1', '有这狗狗在 把我牙都拔了 我都不疼', '22', '', '1432212966', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('21', '1', '‘发现’  全新电动汽车', '23', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('22', '1', '小贵港，大海景', '24', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('23', '1', '独自一人的娱乐方式', '25', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('24', '1', '美国队长你这是怎么了', '26', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('25', '1', '这样泡妹子好么？', '27', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('26', '1', '超级玛丽新关卡', '28', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('27', '1', '笑死我了。', '29', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('28', '1', '挡子弹算屁，诺基亚可以把子弹反弹回去把开枪的人打死。', '30', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('29', '1', '一定剪辑过', '31', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('30', '1', '累了，睡一觉！还是席梦思床垫舒服，软软的，很贴心', '32', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('31', '1', '所以说，少点去招惹鳄鱼……', '33', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('32', '1', '尼玛?，都去度假了', '34', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('33', '1', '城里人真会玩，话说电话号码呢！', '35', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('34', '1', '哥们，take it easy.', '36', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('35', '1', '我要给岛国人民送屎去！同意的点赞', '37', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('36', '1', '阿姨，两张儿童票~', '38', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('37', '1', '11区德岛县一位叫ケロヨン的麻麻每天都会给子女准备一份动漫便当。。。简直别人家的亲妈系列', '39', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('38', '1', '我朋友买的阿迪达斯…', '40', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('39', '1', '旅途比较颠簸，可以理解', '41', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('40', '1', '从小就好这一口╮(╯▽╰)╭', '42', '', '1432476700', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('41', '1', '大树：尼玛，这就是你说的啄虫子。', '43', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('42', '1', '这组照片就叫做：天外有天，人外有人', '44', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('43', '1', '‘发现’  全新电动汽车', '45', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('44', '1', '小贵港，大海景', '46', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('45', '1', '独自一人的娱乐方式', '47', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('46', '1', '美国队长你这是怎么了', '48', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('47', '1', '这样泡妹子好么？', '49', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('48', '1', '超级玛丽新关卡', '50', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('49', '1', '笑死我了。', '51', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('50', '1', '挡子弹算屁，诺基亚可以把子弹反弹回去把开枪的人打死。', '52', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('51', '1', '一定剪辑过', '53', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('52', '1', '累了，睡一觉！还是席梦思床垫舒服，软软的，很贴心', '54', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('53', '1', '所以说，少点去招惹鳄鱼……', '55', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('54', '1', '尼玛?，都去度假了', '56', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('55', '1', '城里人真会玩，话说电话号码呢！', '57', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('56', '1', '哥们，take it easy.', '58', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('57', '1', '我要给岛国人民送屎去！同意的点赞', '59', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('58', '1', '阿姨，两张儿童票~', '60', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('59', '1', '11区德岛县一位叫ケロヨン的麻麻每天都会给子女准备一份动漫便当。。。简直别人家的亲妈系列', '61', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('60', '1', '我朋友买的阿迪达斯…', '62', '', '1432479192', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('61', '1', '大树：尼玛，这就是你说的啄虫子。', '63', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('62', '1', '这组照片就叫做：天外有天，人外有人', '64', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('63', '1', '‘发现’  全新电动汽车', '65', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('64', '1', '小贵港，大海景', '66', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('65', '1', '独自一人的娱乐方式', '67', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('66', '1', '美国队长你这是怎么了', '68', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('67', '1', '这样泡妹子好么？', '69', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('68', '1', '超级玛丽新关卡', '70', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('69', '1', '笑死我了。', '71', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('70', '1', '挡子弹算屁，诺基亚可以把子弹反弹回去把开枪的人打死。', '72', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('71', '1', '一定剪辑过', '73', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('72', '1', '累了，睡一觉！还是席梦思床垫舒服，软软的，很贴心', '74', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('73', '1', '所以说，少点去招惹鳄鱼……', '75', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('74', '1', '尼玛?，都去度假了', '76', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('75', '1', '城里人真会玩，话说电话号码呢！', '77', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('76', '1', '哥们，take it easy.', '78', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('77', '1', '我要给岛国人民送屎去！同意的点赞', '79', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('78', '1', '阿姨，两张儿童票~', '80', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('79', '1', '11区德岛县一位叫ケロヨン的麻麻每天都会给子女准备一份动漫便当。。。简直别人家的亲妈系列', '81', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('80', '1', '我朋友买的阿迪达斯…', '82', '', '1432479203', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('81', '1', '大树：尼玛，这就是你说的啄虫子。', '83', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('82', '1', '这组照片就叫做：天外有天，人外有人', '84', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('83', '1', '‘发现’  全新电动汽车', '85', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('84', '1', '小贵港，大海景', '86', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('85', '1', '独自一人的娱乐方式', '87', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('86', '1', '美国队长你这是怎么了', '88', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('87', '1', '这样泡妹子好么？', '89', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('88', '1', '超级玛丽新关卡', '90', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('89', '1', '笑死我了。', '91', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('90', '1', '挡子弹算屁，诺基亚可以把子弹反弹回去把开枪的人打死。', '92', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('91', '1', '一定剪辑过', '93', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('92', '1', '累了，睡一觉！还是席梦思床垫舒服，软软的，很贴心', '94', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('93', '1', '所以说，少点去招惹鳄鱼……', '95', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('94', '1', '尼玛?，都去度假了', '96', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('95', '1', '城里人真会玩，话说电话号码呢！', '97', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('96', '1', '哥们，take it easy.', '98', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('97', '1', '我要给岛国人民送屎去！同意的点赞', '99', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('98', '1', '11区德岛县一位叫ケロヨン的麻麻每天都会给子女准备一份动漫便当。。。简直别人家的亲妈系列', '100', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('99', '1', '我朋友买的阿迪达斯…', '101', '', '1432479304', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('100', '1', '女孩子想清楚点好！', '102', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('101', '1', '大树：尼玛，这就是你说的啄虫子。', '103', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('102', '1', '这组照片就叫做：天外有天，人外有人', '104', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('103', '1', '‘发现’  全新电动汽车', '105', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('104', '1', '小贵港，大海景', '106', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('105', '1', '独自一人的娱乐方式', '107', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('106', '1', '美国队长你这是怎么了', '108', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('107', '1', '这样泡妹子好么？', '109', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('108', '1', '超级玛丽新关卡', '110', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('109', '1', '笑死我了。', '111', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('110', '1', '挡子弹算屁，诺基亚可以把子弹反弹回去把开枪的人打死。', '112', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('111', '1', '一定剪辑过', '113', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('112', '1', '累了，睡一觉！还是席梦思床垫舒服，软软的，很贴心', '114', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('113', '1', '所以说，少点去招惹鳄鱼……', '115', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('114', '1', '尼玛?，都去度假了', '116', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('115', '1', '城里人真会玩，话说电话号码呢！', '117', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('116', '1', '哥们，take it easy.', '118', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('117', '1', '我要给岛国人民送屎去！同意的点赞', '119', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('118', '1', '阿姨，两张儿童票~', '120', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('119', '1', '11区德岛县一位叫ケロヨン的麻麻每天都会给子女准备一份动漫便当。。。简直别人家的亲妈系列', '121', '', '1432479701', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('120', '1', '女孩子想清楚点好！', '122', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('121', '1', '大树：尼玛，这就是你说的啄虫子。', '123', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('122', '1', '这组照片就叫做：天外有天，人外有人', '124', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('123', '1', '‘发现’  全新电动汽车', '125', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('124', '1', '小贵港，大海景', '126', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('125', '1', '独自一人的娱乐方式', '127', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('126', '1', '美国队长你这是怎么了', '128', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('127', '1', '这样泡妹子好么？', '129', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('128', '1', '超级玛丽新关卡', '130', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('129', '1', '笑死我了。', '131', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('130', '1', '挡子弹算屁，诺基亚可以把子弹反弹回去把开枪的人打死。', '132', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('131', '1', '一定剪辑过', '133', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('132', '1', '累了，睡一觉！还是席梦思床垫舒服，软软的，很贴心', '134', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('133', '1', '所以说，少点去招惹鳄鱼……', '135', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('134', '1', '尼玛?，都去度假了', '136', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('135', '1', '城里人真会玩，话说电话号码呢！', '137', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('136', '1', '哥们，take it easy.', '138', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('137', '1', '我要给岛国人民送屎去！同意的点赞', '139', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('138', '1', '阿姨，两张儿童票~', '140', '', '1432479751', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('139', '1', '女孩子想清楚点好！', '141', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('140', '1', '大树：尼玛，这就是你说的啄虫子。', '142', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('141', '1', '这组照片就叫做：天外有天，人外有人', '143', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('142', '1', '‘发现’  全新电动汽车', '144', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('143', '1', '小贵港，大海景', '145', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('144', '1', '独自一人的娱乐方式', '146', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('145', '1', '美国队长你这是怎么了', '147', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('146', '1', '这样泡妹子好么？', '148', '', '1432479863', '1', '0', '0', '0');
INSERT INTO `big_atlas` VALUES ('147', '1', '女孩子想清楚点好！', '149', '', '1432480090', '1', '0', '0', '0');
-- -----------------------------
-- 表内记录 `big_atlas_collection`
-- -----------------------------
INSERT INTO `big_atlas_collection` VALUES ('1', '百思不得姐', 'http://www.budejie.com/', '1', '14362431', '14318532', '1432473113');
-- -----------------------------
-- 表内记录 `big_atlas_like`
-- -----------------------------
INSERT INTO `big_atlas_like` VALUES ('1', '1', '100', '1432108632', '1');
