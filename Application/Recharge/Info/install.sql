-- -----------------------------
-- 表结构 `ocenter_recharge_order`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_recharge_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '订单号',
  `field` int(11) NOT NULL COMMENT '充值字段',
  `amount` decimal(10,2) NOT NULL COMMENT '充值数额',
  `method` varchar(50) NOT NULL COMMENT '支付方式',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `record_id` int(11) NOT NULL,
  `payok` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14316809064739 DEFAULT CHARSET=utf8 COMMENT='充值订单表';


-- -----------------------------
-- 表结构 `ocenter_recharge_record_alipay`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_recharge_record_alipay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `out_trade_no` varchar(20) NOT NULL,
  `buyer_email` varchar(500) NOT NULL,
  `buyer_id` varchar(100) NOT NULL,
  `seller_email` varchar(50) NOT NULL,
  `seller_id` varchar(30) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `total_fee` decimal(10,2) NOT NULL,
  `trade_no` varchar(100) NOT NULL,
  `trade_status` varchar(20) NOT NULL,
  `sign` varchar(100) NOT NULL,
  `sign_type` varchar(100) NOT NULL,
  `body` varchar(500) NOT NULL,
  `exterface` varchar(50) NOT NULL,
  `is_success` varchar(50) NOT NULL,
  `notify_id` varchar(500) NOT NULL,
  `notify_time` int(11) NOT NULL,
  `notify_type` varchar(50) NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trade_no_2` (`trade_no`),
  KEY `trade_no` (`trade_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付宝付款记录表';


-- -----------------------------
-- 表结构 `ocenter_recharge_withdraw`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_recharge_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` int(11) NOT NULL COMMENT '提现字段',
  `amount` decimal(10,2) NOT NULL COMMENT '提现金额',
  `method` varchar(50) NOT NULL COMMENT '提现方式',
  `uid` int(11) NOT NULL COMMENT '提现用户',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `payok` tinyint(4) NOT NULL COMMENT '支付状态',
  `pay_uid` int(11) NOT NULL COMMENT '支付者',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `frozen_amount` decimal(10,2) NOT NULL COMMENT '冻结积分，用于防止兑率更改后取消订单返回积分不同',
  `account_info` varchar(400) NOT NULL COMMENT '账号信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提现订单表';

