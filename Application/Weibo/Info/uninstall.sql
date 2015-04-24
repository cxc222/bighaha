DROP TABLE IF EXISTS `opensns_weibo`;
DROP TABLE IF EXISTS `opensns_weibo_comment`;
DROP TABLE IF EXISTS `opensns_weibo_top`;




/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where `title` = '微博' ;
delete from `opensns_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `opensns_menu` where  `title` = '微博' ;

delete from `opensns_menu` where  `url` like 'Weibo/%';

delete from `opensns_auth_rule` where  `module` = 'Weibo';