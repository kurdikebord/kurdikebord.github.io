RENAME TABLE `salamquran`.`sure` TO `salamquran_data`.`1_sura`;

DROP TABLE `salamquran`.`quran_word`;
DROP TABLE `salamquran`.`quran_text`;

ALTER TABLE `salamquran_data`.`1_quran_ayat` CHANGE `hezb` `hizb` SMALLINT(3) NULL DEFAULT NULL;
ALTER TABLE `salamquran_data`.`1_quran_ayat` CHANGE `sajde` `sajdah` SMALLINT(3) NULL DEFAULT NULL;
ALTER TABLE `salamquran_data`.`1_quran_ayat` CHANGE `sajde_number` `sajdah_number` SMALLINT(3) NULL DEFAULT NULL;

ALTER TABLE `salamquran_data`.`1_quran_word` CHANGE `hezb` `hizb` SMALLINT(3) NULL DEFAULT NULL;