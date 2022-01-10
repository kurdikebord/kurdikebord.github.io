ALTER TABLE `mags` ADD `word`  int(10) unsigned  NULL DEFAULT NULL ;
ALTER TABLE `mags` ADD `wordtitle`  varchar(100)   NULL DEFAULT NULL ;
ALTER TABLE `mags` ADD `subtype`  varchar(500)   NULL DEFAULT NULL ;

ALTER TABLE `transactions` ADD `url`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci    NULL DEFAULT NULL ;