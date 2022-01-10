

ALTER TABLE `history` ADD `index` smallint(4) UNSIGNED NULL DEFAULT NULL AFTER `user_id`;
ALTER TABLE `history` ADD `time` int(10) UNSIGNED NULL DEFAULT NULL AFTER `datecreated`;

ALTER TABLE `history` ADD INDEX `index_search_time` (`time`);
ALTER TABLE `history` ADD INDEX `index_search_user_id` (`user_id`);
