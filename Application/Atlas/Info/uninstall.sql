DROP TABLE IF EXISTS `opensns_atlas`;
DROP TABLE IF EXISTS `opensns_atlas_like`;
DROP TABLE IF EXISTS `opensns_atlas_config`;

/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where `title` = '图集';
delete from `opensns_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `opensns_menu` where  `title` = '图集';

delete from `opensns_menu` where  `url` like 'Atlas/%';