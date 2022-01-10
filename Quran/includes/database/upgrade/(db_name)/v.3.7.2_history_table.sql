CREATE TABLE `history` (
`id` bigint(20) UNSIGNED NOT NULL auto_increment,
`user_id` int(10) UNSIGNED NULL,
`sura` smallint(3) UNSIGNED NULL DEFAULT NULL,
`aya` smallint(4) UNSIGNED NULL DEFAULT NULL,
`page` smallint(3) UNSIGNED NULL DEFAULT NULL,
`juz` smallint(2) UNSIGNED NULL DEFAULT NULL,
`rub` smallint(3) UNSIGNED NULL DEFAULT NULL,
`nim` smallint(3) UNSIGNED NULL DEFAULT NULL,
`hizb` smallint(3) UNSIGNED NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `history_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;





CREATE TABLE `badge` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`title` varchar(500) NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
