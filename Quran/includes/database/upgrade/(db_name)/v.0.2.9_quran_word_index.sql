
ALTER TABLE `salamquran_data`.`1_quran_word` ADD `index` smallint(5) DEFAULT NULL AFTER `id`;


UPDATE `salamquran_data`.`1_quran_word` SET `salamquran_data`.`1_quran_word`.`index` = (SELECT `salamquran_data`.`1_quran_ayat`.`index` FROM `salamquran_data`.`1_quran_ayat` WHERE `salamquran_data`.`1_quran_ayat`.`sura` = `salamquran_data`.`1_quran_word`.`sura` AND `salamquran_data`.`1_quran_ayat`.`aya` = `salamquran_data`.`1_quran_word`.`aya` LIMIT 1);
