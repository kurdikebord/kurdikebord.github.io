CREATE TABLE `khatm` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`user_id` int(10) UNSIGNED NOT NULL,
`title` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`niyat` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`type` enum('page', 'juz') NULL DEFAULT NULL,
`range` enum('quran', 'sura') NULL DEFAULT NULL,
`privacy` enum('public', 'private') NULL DEFAULT NULL,
`repeat` int(10) UNSIGNED NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire', 'done')  NULL DEFAULT NULL,
`sura` smallint(3) NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `khatm_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `khatmusage` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`user_id` int(10) UNSIGNED NOT NULL,
`khatm_id` int(10) UNSIGNED NOT NULL ,
`sura` smallint(3) NULL DEFAULT NULL,
`page` smallint(3) NULL DEFAULT NULL,
`juz` smallint(3) NULL DEFAULT NULL,
`status` enum('request', 'reading', 'cancel', 'autocancel', 'done')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `khatmusage_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
CONSTRAINT `khatmusage_khatm_id` FOREIGN KEY (`khatm_id`) REFERENCES `khatm` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

