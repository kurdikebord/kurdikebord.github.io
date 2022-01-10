ALTER TABLE `quran_text` ADD `simple` text NULL;
UPDATE quran_text SET simple = (SELECT temp_quran_text.text FROM temp_quran_text WHERE temp_quran_text.sura = quran_text.sura AND temp_quran_text.aya = quran_text.aya);
DROP TABLE temp_quran_text;
