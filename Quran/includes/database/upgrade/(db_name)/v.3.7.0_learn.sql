CREATE TABLE `lm_group` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`title` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`sort` int(10) NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
`file` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE `lm_level` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`lm_group_id` int(10) UNSIGNED NOT NULL,
`title` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`quranfrom` int(10) NULL DEFAULT NULL,
`quranto` int(10) NULL DEFAULT NULL,
`besmellah` bit(1) NULL DEFAULT NULL,
`file` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`setting` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`sort` int(10) NULL DEFAULT NULL,
`ratio` int(10) NULL DEFAULT NULL,
`unlockscore` int(10) NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `lm_level_group_id` FOREIGN KEY (`lm_group_id`) REFERENCES `lm_group` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `lm_question` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`lm_level_id` int(10) UNSIGNED NOT NULL,
`title` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`model` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt1` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt1file` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt2` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt2file` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt3` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt3file` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt4` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`opt4file` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
`trueopt` smallint(3) NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `lm_question_level_id` FOREIGN KEY (`lm_level_id`) REFERENCES `lm_level` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `lm_answer` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`user_id` int(10) UNSIGNED NOT NULL,
`lm_question_id` int(10) UNSIGNED NOT NULL,
`opt` smallint(3) NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `lm_answer_question_id` FOREIGN KEY (`lm_question_id`) REFERENCES `lm_question` (`id`) ON UPDATE CASCADE,
CONSTRAINT `lm_answer_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `lm_star` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`lm_group_id` int(10) UNSIGNED NOT NULL,
`lm_level_id` int(10) UNSIGNED NOT NULL,
`user_id` int(10) UNSIGNED NOT NULL,
`star` smallint(3) NULL DEFAULT NULL,
`score` int(10) NULL DEFAULT NULL,
`status` enum('enable', 'disable', 'awaiting', 'deleted', 'publish', 'expire')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `lm_star_group_id` FOREIGN KEY (`lm_group_id`) REFERENCES `lm_group` (`id`) ON UPDATE CASCADE,
CONSTRAINT `lm_star_level_id` FOREIGN KEY (`lm_level_id`) REFERENCES `lm_level` (`id`) ON UPDATE CASCADE,
CONSTRAINT `lm_star_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `lm_audio` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`lm_group_id` int(10) UNSIGNED NOT NULL,
`lm_level_id` int(10) UNSIGNED NOT NULL,
`user_id` int(10) UNSIGNED NOT NULL,
`teacher` int(10) UNSIGNED NOT NULL,
`audio` varchar(500) NULL DEFAULT NULL,
`teachertxt` varchar(500) NULL DEFAULT NULL,
`teacheraudio` varchar(500) NULL DEFAULT NULL,
`quality` smallint(3) NULL DEFAULT NULL,
`status` enum('awaiting', 'spam', 'deleted', 'admindelete', 'approved', 'reject')  NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `lm_audio_group_id` FOREIGN KEY (`lm_group_id`) REFERENCES `lm_group` (`id`) ON UPDATE CASCADE,
CONSTRAINT `lm_audio_level_id` FOREIGN KEY (`lm_level_id`) REFERENCES `lm_level` (`id`) ON UPDATE CASCADE,
CONSTRAINT `lm_audio_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
CONSTRAINT `lm_audio_teacher_id` FOREIGN KEY (`teacher`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `lm_mistake` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`title` varchar(500) NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `lm_audiomistake` (
`id` int(10) UNSIGNED NOT NULL auto_increment,
`lm_mistake_id` int(10) UNSIGNED NOT NULL,
`lm_audio_id` int(10) UNSIGNED NOT NULL,
`teacher` int(10) UNSIGNED NOT NULL,
`desc` varchar(500) NULL DEFAULT NULL,
`datecreated` datetime  NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `lm_audiomistake_group_id` FOREIGN KEY (`lm_mistake_id`) REFERENCES `lm_mistake` (`id`) ON UPDATE CASCADE,
CONSTRAINT `lm_audiomistake_audio_id` FOREIGN KEY (`lm_audio_id`) REFERENCES `lm_audio` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
