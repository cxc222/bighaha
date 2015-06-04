ALTER TABLE  `ocenter_ucenter_member` ADD  `type` TINYINT NOT NULL COMMENT  '1为用户名注册，2为邮箱注册，3为手机注册';
UPDATE  `ocenter_ucenter_member` SET  `type` =  '1' WHERE  `ocenter_ucenter_member`.`type` =0;