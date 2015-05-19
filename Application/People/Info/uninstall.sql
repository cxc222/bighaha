set @tmp_id=0;
select @tmp_id:= id from `ocenter_menu` where `title` = '会员展示';
delete from `ocenter_menu` where  `id` = @tmp_id or (`pid` = @tmp_id  and `pid` !=0);