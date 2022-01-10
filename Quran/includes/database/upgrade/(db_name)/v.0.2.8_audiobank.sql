CREATE TABLE `audiobank` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`qari` varchar(200) NULL DEFAULT NULL,
`avatar` varchar(2000) NULL DEFAULT NULL,
`type` varchar(200) NULL DEFAULT NULL,
`readtype` varchar(200) NULL DEFAULT NULL,
`filetype` varchar(200) NULL DEFAULT NULL,
`country` varchar(50) NULL DEFAULT NULL,
`quality` smallint(4) UNSIGNED NULL DEFAULT NULL,
`time` int(10) UNSIGNED NULL DEFAULT NULL,
`size` int(10) UNSIGNED NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'review', 'filter')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

