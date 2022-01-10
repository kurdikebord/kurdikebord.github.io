UPDATE `salamquran_data`.`1_quran_word` SET `salamquran_data`.`1_quran_word`.`juz` = ( SELECT `salamquran_data`.`1_quran_ayat`.`juz` FROM `salamquran_data`.`1_quran_ayat` WHERE `salamquran_data`.`1_quran_ayat`.`sura` = `salamquran_data`.`1_quran_word`.`sura` AND `salamquran_data`.`1_quran_ayat`.`aya` = `salamquran_data`.`1_quran_word`.`aya` LIMIT 1 );

UPDATE `salamquran_data`.`1_quran_word` SET `salamquran_data`.`1_quran_word`.`hizb` = ( SELECT `salamquran_data`.`1_quran_ayat`.`hizb` FROM `salamquran_data`.`1_quran_ayat` WHERE `salamquran_data`.`1_quran_ayat`.`sura` = `salamquran_data`.`1_quran_word`.`sura` AND `salamquran_data`.`1_quran_ayat`.`aya` = `salamquran_data`.`1_quran_word`.`aya` LIMIT 1 );

UPDATE `salamquran_data`.`1_quran_word` SET `salamquran_data`.`1_quran_word`.`rub` = ( SELECT `salamquran_data`.`1_quran_ayat`.`rub` FROM `salamquran_data`.`1_quran_ayat` WHERE `salamquran_data`.`1_quran_ayat`.`sura` = `salamquran_data`.`1_quran_word`.`sura` AND `salamquran_data`.`1_quran_ayat`.`aya` = `salamquran_data`.`1_quran_word`.`aya` LIMIT 1 );


ALTER TABLE `salamquran_data`.`1_sura` CHANGE `juz` `startjuz` smallint(3) DEFAULT NULL;
ALTER TABLE `salamquran_data`.`1_sura` CHANGE `alljuz` `endjuz` smallint(3) DEFAULT NULL;

UPDATE `salamquran_data`.`1_sura` SET `salamquran_data`.`1_sura`.`startjuz` = ( SELECT `salamquran_data`.`1_quran_ayat`.`juz` FROM `salamquran_data`.`1_quran_ayat` WHERE `salamquran_data`.`1_quran_ayat`.`sura` = `salamquran_data`.`1_sura`.`index` ORDER BY `salamquran_data`.`1_quran_ayat`.`index` ASC LIMIT 1);
UPDATE `salamquran_data`.`1_sura` SET `salamquran_data`.`1_sura`.`endjuz` = ( SELECT `salamquran_data`.`1_quran_ayat`.`juz` FROM `salamquran_data`.`1_quran_ayat` WHERE `salamquran_data`.`1_quran_ayat`.`sura` = `salamquran_data`.`1_sura`.`index` ORDER BY `salamquran_data`.`1_quran_ayat`.`index` DESC LIMIT 1);
