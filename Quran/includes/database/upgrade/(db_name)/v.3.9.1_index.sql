
ALTER TABLE `salamquran_data`.`1_quran_word` ADD INDEX `quran_word_index_index` (`index`);


ALTER TABLE `salamquran_data`.`1_quran_word` DROP INDEX `quran_word_index_id`;
ALTER TABLE `salamquran_data`.`1_quran_word` DROP INDEX `quran_word_index_aya`;
ALTER TABLE `salamquran_data`.`1_quran_word` DROP INDEX `quran_word_index_sura`;




ALTER TABLE `salamquran_data`.`1_quran_ayat` DROP INDEX `quran_ayat_index_sura`;
ALTER TABLE `salamquran_data`.`1_quran_ayat` DROP INDEX `quran_ayat_index_aya`;
ALTER TABLE `salamquran_data`.`1_quran_ayat` DROP INDEX `quran_ayat_index_juz`;
ALTER TABLE `salamquran_data`.`1_quran_ayat` DROP INDEX `quran_ayat_index_page`;
ALTER TABLE `salamquran_data`.`1_quran_ayat` DROP INDEX `quran_ayat_index_rub`;
