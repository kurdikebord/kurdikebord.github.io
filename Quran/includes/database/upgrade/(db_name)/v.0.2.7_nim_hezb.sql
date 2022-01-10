ALTER TABLE `salamquran_data`.`1_quran_word` ADD `nim` smallint(3) DEFAULT NULL;
ALTER TABLE `salamquran_data`.`1_quran_ayat` ADD `nim` smallint(3) DEFAULT NULL;

UPDATE `salamquran_data`.`1_quran_ayat` SET `salamquran_data`.`1_quran_ayat`.`nim` = CEIL(`salamquran_data`.`1_quran_ayat`.`rub` / 2);
UPDATE `salamquran_data`.`1_quran_word` SET `salamquran_data`.`1_quran_word`.`nim` = CEIL(`salamquran_data`.`1_quran_word`.`rub` / 2);