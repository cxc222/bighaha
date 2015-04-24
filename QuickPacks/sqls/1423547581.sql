set @tmp_id=0;
select @tmp_id:= id from `opensns_menu` where title = '专辑';
INSERT INTO `opensns_menu` ( `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`) VALUES
( '设置专辑状态', @tmp_id, 0, 'Issue/setIssueContentStatus', 1, '', '', 0);
