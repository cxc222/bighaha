-- -----------------------------
-- 表结构 `ocenter_group`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group` (
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
  `member_count` int(11) NOT NULL,
  `member_alias` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_bookmark`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_dynamic`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_dynamic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `row_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_lzl_reply`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_lzl_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `to_f_reply_id` int(11) NOT NULL,
  `to_reply_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_member`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `last_view` int(11) NOT NULL,
  `position` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1为普通成员，2为管理员，3为创建者',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_notice`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_post`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_post` (
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
  `summary` varchar(250) NOT NULL,
  `cover` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_post_category`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_post_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_post_reply`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_post_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parse` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- -----------------------------
-- 表结构 `ocenter_group_type`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_group_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `status` tinyint(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='圈子的分类表';

-- -----------------------------
-- 表内记录 `ocenter_group`
-- -----------------------------
INSERT INTO `ocenter_group` VALUES ('1', '1', 'OpenSNS', '1430295268', '1', '1', '', '0', '0', '0', '1', 'OpenSNS讨论组', '0', '3', '0', 'OSer');
-- -----------------------------
-- 表内记录 `ocenter_group_dynamic`
-- -----------------------------
INSERT INTO `ocenter_group_dynamic` VALUES ('1', '1', '1', 'attend', '0', '1431916580');
INSERT INTO `ocenter_group_dynamic` VALUES ('2', '1', '1', 'reply', '1', '1431916584');
INSERT INTO `ocenter_group_dynamic` VALUES ('3', '1', '1', 'reply', '2', '1431916606');
-- -----------------------------
-- 表内记录 `ocenter_group_member`
-- -----------------------------
INSERT INTO `ocenter_group_member` VALUES ('1', '1', '1', '1', '1431916580', '1431916580', '2', '0', '1');
-- -----------------------------
-- 表内记录 `ocenter_group_post`
-- -----------------------------
INSERT INTO `ocenter_group_post` VALUES ('1', '1', '1', '140到116只用两月,希望可以激励到你', '0', '<p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; word-wrap: break-word; overflow: hidden; color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\">从去年8月1日开始办健身卡开始健身,到2015年1月30日.我那时候办的是半年的合约卡.本早应该发帖子,可是太懒了,一直没有整理各种照片,帖子里所有的照片都是二月之前,绝对真实! <br/>最开始从一个第一次去健身房的小白到现在已经跟私教互相学习,这一路基本靠着自学看帖子,看书充实理论知识与动作.这个帖子里不想太去讨论学术性的东西,只是给自己半年健身一个交代,也给大家一些分享与激励.想看学术帖子可以加我微信,当然需要私密我.Huyang71 <br/><br/>去年夏天的时候7月某天站在镜子面前看着自己胖嘟嘟的脸跟肚囊,突然决心需要减肥,不足以控制体重的人何以控制人生.然后就在家附近的健身房办了卡. <br/>最开始各种器械不懂,各种理论不知,只知道需要把体重给减下来,数字就是最直观的事实(当时没有太考虑肌肉会耗损,导致后来泪奔苦逼增肌). <br/>我就是一个很倔的人,说到做到,开始进小组看各种帖子,看大神们如何吃.先上1张我最开始吃的食物的照片 <br/></p><p><img src=\"http://img3.douban.com/view/group_topic/large/public/p29075690.jpg\" alt=\"\" class=\"\" style=\"border: 0px; max-width: 500px;\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; word-wrap: break-word; overflow: hidden; color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"><br/>左边的是水煮牛肉跟胡萝卜,右边是水煮燕麦. <br/>对!你没看错你也没听错,楼主就这么无油无盐的吃了将近一个半月. <br/>刚开始吃还好,感觉挺新鲜的,后来中午牛肉晚上鸡胸肉,吃久了就没有知觉了,感觉食物在我眼里就是带着数字的卡路里,直到至今我晚餐都还是水煮鸡胸肉跟蔬菜加一点粗粮. </p><p><br/></p>', '1430377962', '1430377962', '1', '1430377962', '6', '0', '0', '0', '', '');
INSERT INTO `ocenter_group_post` VALUES ('2', '1', '1', '有谁像我脊椎侧弯的吗？姑娘们要注意坐姿啊！', '0', '<p><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">不知道是小时候坐姿不好还是翘二郎腿太多，大概两三年前就发觉自己脊椎侧弯了，那时候还不是很严重吧，然后那时候正好有去学瑜伽，老师上课的时候总是让我做拉伸的动作，说对脊椎好，可惜我学了半年就没再去学了。</span><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">然后这几年也没继续当回事，继续翘着二郎腿，直到有天去推拿，师傅跟我说我的脊椎错位非常严重，不注意的话年纪大了会非常辛苦。</span><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">然后我就去医院拍片了，真的已经是非常严重的脊椎侧弯了，那天正好骨科医院的院长还看了我的片，说是只能手术了，但是脊椎手术风险太大，一般也不建议做。</span><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">不知道是心理因素还是真的到报应的时候了，拍片回来的第二天，左腿全麻了，一直麻了两天，睡都睡不好，只能去找正骨师傅理疗，师傅帮我正骨了，左腿也不麻了，可是脊椎侧弯引起的后遗症还是很多，比如站久坐久了腰都会痛，然后背后的肌肉因为脊椎错位也会拉伤肿起来一块，每次去理疗师傅按过之后消一点，回来一不注意又会肿，而且因为肌肉本来就拉伤了，就不好去健身啊运动的了，还想着夏天到了要减肥呢，真的是好无奈……</span><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">对了！我还变成长短腿了！！！</span><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">姑娘们真的是少翘二郎腿了，坐办公室的也多运动运动，真的是太麻烦了，想哭的心都有了。</span><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">P.S. 脊椎侧弯的原因很多，楼里很多姑娘说二郎腿也不是最主要的，不过平常姑娘们能不翘就不翘吧，翘多了也o型腿，已经弯了的姑娘更不要翘了，我的医生说不要翘腿不要叠腿，因为要注意平衡。</span><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><br style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"/><span style=\"color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; -color: rgb(255, 255, 255);\">我是个喜欢背包旅行的人，去年年初还兴致冲冲地爬雨崩,今年就坐也疼站也疼膝盖也疼了，一想到还有那么多山想爬，就觉得心塞塞的……</span></p>', '1430378146', '1430378146', '1', '1430378146', '0', '0', '0', '0', '', '');
INSERT INTO `ocenter_group_post` VALUES ('3', '1', '1', '怎样才能让男朋友不离开自己（男生进）', '0', '<p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; word-wrap: break-word; overflow: hidden; color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\">什么样的女生会让你很爱她，舍不得离开她？前提是女朋友很爱你，很想粘你，但怕你会烦，还有什么时候该粘，什么时候不该粘～～～</p><p><img src=\"http://img5.douban.com/view/group_topic/large/public/p28691887.jpg\" alt=\"\" class=\"\" style=\"border: 0px; max-width: 500px;\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; word-wrap: break-word; overflow: hidden; color: rgb(17, 17, 17); font-family: Helvetica, Arial, sans-serif; font-size: 13px; line-height: 21.0599994659424px; white-space: normal; -color: rgb(255, 255, 255);\"><br/><br/><br/>总结一下大家的回复，很多还是很有道理的，供其他妹子参考： <br/><br/>首先你先确定他不是渣男 <br/><br/>体贴懂事，长相过得去，有胸微肉，愿意锻炼身体爱惜自己，愿意学习为梦想努力，能聪明点更好 <br/><br/>永远有共同话题 能互相了解对方的喜好并试着去了解， 保持身材 <br/><br/>做自己就好了 然后保持完美的内外在就好 <br/><br/>学会欲擒故纵 <br/>会撒娇。 <br/>知情识趣，知道什么时候该粘什么时候不该粘。最主要是长的好看 <br/><br/>时刻做真实的自己 吸引到适合自己的人 保持进步不要堕落 <br/>我自己来说就是一直身材好 一直有趣 一直爱读书 <br/>一直跟他有话说 在他面前有女友的自觉 不用时刻温柔 至少要让他习惯你的存在 逐渐依赖你 时常交换两个人有价值和没营养的信息 <br/><br/>外表就不说了 越漂亮越好这你们女的都知道 <br/>该粘人时候粘人 不该粘人时候别zuo <br/>别去挖苦你的男朋友 想办法去帮他 <br/>有点自己的事情 别成天让男的围着你 最后你又觉得男的这不行那不行的 <br/>最最主要的 也是大部分女生做不到的 <br/>要耐得住寂寞 <br/><br/>你如果是贤惠型的，请做菜，抓住男人的心就要抓住他的胃。如果你是妩媚型的，请尽量少穿或不穿，如果你是小鸟依人型，请尽情撒娇，不发嗲不打滚绝对是犯罪。 <br/><br/>会鼓励男友的女人是聪明的女人，懂得给对方空间的女人是智慧的女人，崇拜男朋友的女人是无可替代的女人，让他做你做不到的事情会让他有成就感。 <br/><br/>颜值高就可以！其他都是次要！ <br/><br/>黏人一点，有原则有底线 <br/><br/>给他洗脑…除了我没人会要你的啦“” <br/><br/>有话题 能够天南地北的聊 不然你和他说 今天天气好 他回答中午吃什么 真的很恼火 <br/>不难看 其实我觉得真要在一起 长相不是最重要的 <br/>性格协调 互相懂得谦让包容 信任 <br/><br/>体贴善解人意想法独立生活态度乐观，人有趣 <br/><br/>婚前不必对他太好 <br/>保持自己的生活状态 <br/>忙的时候各自忙，不打扰不作，休息时腻在一起 <br/>偶尔撒撒小娇耍耍小性 <br/>要舍得花他的钱，加大沉没成本 <br/><br/>不是所有男人都贪财好色，但大部分都喜欢貌美如花，好好打扮，有自己个性，保养好自己<br/><br/>比如闹情绪，适可而止不要上纲上线大家都下不来台，比如撒娇粘人，如果男票在忙工作连休息都不够了，下班累成狗，女票如果自己没人陪有情绪还能体贴点帮忙倒个水递个鞋是很奢侈的。 <br/><br/>情商高的哪有那么多。 <br/><br/>身体健康的哪有那么多。 <br/><br/>男人可能被妖艳的女人吸引，但是男人更会沉迷于女人味和女人的睿智。 <br/><br/>男人太优秀，女人太简单，也是问题。优秀选择就多了，退路也多，你不努力给自己投资，那么日子久了落差就会变大。 <br/>怎么说努力上进是不会错的，所以找妹子一定要上进，为了双方也为了自己，如果这个道理都懂，但是懒，那就没办法 <br/><br/>如果他不会总是冷落你，比如也会主动嘘寒问暖，问你怎么样了，今天干嘛了，心情好不好，聊聊今天发生了什么。即使这么简单的东西，我们也可以称之为上心了，因为它是上心这种内在驱动力产生的行为效果。 <br/><br/>他要什么都不要轻易满足他就行。 <br/><br/>彼此留有自己的空间，这点很重要， <br/>不要让他把你“一眼望穿”，保持各自的隐私，让他慢慢了解你，发现你的优点～～～ <br/><br/>提升自身的伴侣价值，降低自己的脾气。 <br/><br/>有独立的生活和独立的人格。 <br/>适度控制脾气 <br/>懂得退让 <br/>专一，学会为爱情放弃感觉 <br/>男生懂得幽默，女生懂得适度撒娇 <br/>懂得为平淡的感情增加情趣 <br/>充满正能量 <br/>拒绝爱情中的奴性 <br/>有自己的思想和内涵 <br/>又丰富的爱好，接受能力强，或者是专注的爱好，并为此付出。 <br/>不要太过坦荡，有一点神秘感。 <br/>不要太容易让对方得到自己的一切。不能太容易满足对方。 <br/>拒绝懒惰，不断提升自己 <br/>学会接受并享受寂寞！ <br/><br/>在任何时候，愿意放弃现在的自己，去成就最好的自己，都是一件伟大的事。 <br/><br/>楼下欢迎继续补充更新~~~~</p><p><br/></p>', '1430378172', '1430378172', '1', '1430378172', '10', '2', '1', '0', '', '');
-- -----------------------------
-- 表内记录 `ocenter_group_post_reply`
-- -----------------------------
INSERT INTO `ocenter_group_post_reply` VALUES ('1', '1', '3', '0', '<p>daadwaw</p>', '1431916584', '1431916584', '1');
INSERT INTO `ocenter_group_post_reply` VALUES ('2', '1', '3', '0', '<p>dawadw</p>', '1431916606', '1431916606', '1');
-- -----------------------------
-- 表内记录 `ocenter_group_type`
-- -----------------------------
INSERT INTO `ocenter_group_type` VALUES ('1', ' 分类一', '1', '0', '0', '0');
