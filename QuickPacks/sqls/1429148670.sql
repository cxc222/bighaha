REPLACE INTO `ocenter_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`) VALUES
(400, '标签列表', 2, 0, 'UserTag/userTag', 0, '', '用户标签管理', 0, ''),
(401, '添加分类、标签', 400, 0, 'UserTag/add', 1, '', '', 0, ''),
(402, '设置分类、标签状态', 400, 0, 'UserTag/setStatus', 1, '', '', 0, ''),
(403, '分类、标签回收站', 400, 0, 'UserTag/tagTrash', 1, '', '', 0, ''),
(404, '可拥有标签配置', 116, 0, 'role/configusertag', 1, '', '', 0, ''),
(405, '测底删除回收站内容', 400, 0, 'UserTag/userTagClear', 1, '', '', 0, '');