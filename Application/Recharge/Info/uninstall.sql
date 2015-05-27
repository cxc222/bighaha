DROP TABLE IF EXISTS `ocenter_recharge_order`;
DROP TABLE IF EXISTS `ocenter_recharge_record_alipay`;
DROP TABLE IF EXISTS `ocenter_recharge_withdraw`;




/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `ocenter_menu` where `title` = '充值' ;
delete from `ocenter_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `ocenter_menu` where  `title` = '充值' ;
delete from `ocenter_menu` where  `url` like 'Recharge/%';
delete from `ocenter_auth_rule` where  `module` = 'Recharge';
delete from `ocenter_action` where  `module` = 'Recharge';
delete from `ocenter_action_limit` where  `module` = 'Recharge';