CREATE TABLE `sure` (
`index` smallint(3) UNSIGNED NOT NULL auto_increment,
`ayas` smallint(3) UNSIGNED NULL DEFAULT NULL,
`start` smallint(4) UNSIGNED NULL DEFAULT NULL,
`end` smallint(4) UNSIGNED NULL DEFAULT NULL,
`name` varchar(100) NULL DEFAULT NULL,
`tname` varchar(100) NULL DEFAULT NULL,
`ename` varchar(100) NULL DEFAULT NULL,
`type` enum('meccan','medinan') NULL DEFAULT NULL,
`order` smallint(4) UNSIGNED NULL DEFAULT NULL,
`orderalphabet` smallint(4) UNSIGNED NULL DEFAULT NULL,
`orderquran` smallint(4) UNSIGNED NULL DEFAULT NULL,
`word` int(10) UNSIGNED NULL DEFAULT NULL,
`theletter` int(10) UNSIGNED NULL DEFAULT NULL,
`joze` smallint(2) UNSIGNED NULL DEFAULT NULL,
`alljoze` varchar(100)  NULL DEFAULT NULL,
PRIMARY KEY (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO sure
(`index`)
SELECT quran_text.sura FROM quran_text GROUP BY quran_text.sura;