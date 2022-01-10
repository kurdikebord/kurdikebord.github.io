CREATE TABLE `mags` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`post_id` bigint(20) UNSIGNED NOT NULL,
`page` smallint(4) UNSIGNED NULL DEFAULT NULL,
`sura` smallint(4) UNSIGNED NULL DEFAULT NULL,
`aya` smallint(5) UNSIGNED NULL DEFAULT NULL,
`sort` smallint(5) NULL DEFAULT NULL,
`creator` int(10) UNSIGNED NULL DEFAULT NULL,
`type` varchar(100)  NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'review', 'filter')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

