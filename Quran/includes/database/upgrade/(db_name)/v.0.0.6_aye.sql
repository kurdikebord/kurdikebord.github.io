ALTER TABLE `quran_text` ADD `joze` smallint(2) NULL;
ALTER TABLE `quran_text` ADD `hezb` smallint(3) NULL;
ALTER TABLE `quran_text` ADD `page` smallint(3) NULL;
ALTER TABLE `quran_text` ADD `word` smallint(3) NULL;
ALTER TABLE `quran_text` ADD `theletter` smallint(4) NULL;
ALTER TABLE `quran_text` ADD `sortnozol` smallint(4) NULL;
ALTER TABLE `quran_text` ADD `sortalphabet` smallint(4) NULL;
ALTER TABLE `quran_text` ADD `meccamedinan` enum('mecca', 'medinan') NULL;