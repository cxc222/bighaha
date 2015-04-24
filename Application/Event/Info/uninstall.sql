DROP TABLE IF EXISTS `opensns_event`;
DROP TABLE IF EXISTS `opensns_event_attend`;
DROP TABLE IF EXISTS `opensns_event_type`;
/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where `title` = '活动';
delete from `opensns_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `opensns_menu` where  `title` = '活动';

delete from `opensns_menu` where  `url` like 'Event/%';