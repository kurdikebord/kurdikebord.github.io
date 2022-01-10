CREATE TABLE `badgeusage` (
`id` bigint(20) UNSIGNED NOT NULL auto_increment,
`user_id` int(10) UNSIGNED NOT NULL,
`badge` varchar(100) NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `badgeusage_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

