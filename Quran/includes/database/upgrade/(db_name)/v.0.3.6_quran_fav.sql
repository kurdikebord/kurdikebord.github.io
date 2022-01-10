CREATE TABLE `fav` (
`id` bigint(20) UNSIGNED NOT NULL auto_increment,
`user_id` int(10) UNSIGNED NOT NULL,
`page` smallint(4) UNSIGNED NULL DEFAULT NULL,
`sura` smallint(4) UNSIGNED NULL DEFAULT NULL,
`aya` smallint(5) UNSIGNED NULL DEFAULT NULL,
`desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT `fav_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

