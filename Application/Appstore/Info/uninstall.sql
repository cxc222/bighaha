DROP TABLE IF EXISTS `ocenter_appstore_developer`;
DROP TABLE IF EXISTS `ocenter_appstore_goods`;
DROP TABLE IF EXISTS `ocenter_appstore_module`;
DROP TABLE IF EXISTS `ocenter_appstore_plugin`;
DROP TABLE IF EXISTS `ocenter_appstore_resource`;
DROP TABLE IF EXISTS `ocenter_appstore_theme`;
DROP TABLE IF EXISTS `ocenter_appstore_type`;
DROP TABLE IF EXISTS `ocenter_appstore_version`;


/*删除menu相关数据*/
set @tmp_id=0;
select @tmp_id:= id from `ocenter_menu` where `title` = '云市场服务端';
delete from `ocenter_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);
delete from `ocenter_menu` where  `title` = '云市场服务端';
delete from `ocenter_menu` where  `url` like 'Appstore/%';
delete from `ocenter_auth_rule` where  `module` = 'Appstore';
delete from `ocenter_action` where  `module` = 'Appstore';
delete from `ocenter_action_limit` where  `module` = 'Appstore';