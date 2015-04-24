DROP TABLE IF EXISTS `opensns_shop`;
DROP TABLE IF EXISTS `opensns_shop_address`;
DROP TABLE IF EXISTS `opensns_shop_buy`;
DROP TABLE IF EXISTS `opensns_shop_category`;
DROP TABLE IF EXISTS `opensns_shop_config`;
DROP TABLE IF EXISTS `opensns_shop_log`;
DROP TABLE IF EXISTS `opensns_shop_see`;

/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where `title` = '商城' or  `title` = '积分商城';
delete from `opensns_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `opensns_menu` where  `title` = '商城' or `title` = '积分商城';
delete from `opensns_menu` where  `url` like 'Shop/%';
