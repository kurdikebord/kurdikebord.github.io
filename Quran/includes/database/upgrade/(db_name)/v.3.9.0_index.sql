ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD INDEX `quran_ayat_index_index` (`index`);
ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD INDEX `quran_ayat_index_sura` (`sura`);
ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD INDEX `quran_ayat_index_aya` (`aya`);
ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD INDEX `quran_ayat_index_juz` (`juz`);
ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD INDEX `quran_ayat_index_hezb` (`hezb`);
ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD INDEX `quran_ayat_index_page` (`page`);
ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD INDEX `quran_ayat_index_rub` (`rub`);


ALTER TABLE `salamquran_data`.`1_quran_word` ADD INDEX `quran_word_index_id` (`id`);
ALTER TABLE `salamquran_data`.`1_quran_word` ADD INDEX `quran_word_index_aya` (`aya`);
ALTER TABLE `salamquran_data`.`1_quran_word` ADD INDEX `quran_word_index_sura` (`sura`);
ALTER TABLE `salamquran_data`.`1_quran_word` ADD INDEX `quran_word_index_page` (`page`);
