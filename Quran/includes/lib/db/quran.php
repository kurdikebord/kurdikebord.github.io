<?php
namespace lib\db;


class quran
{
	public static function get($_where, $_option = [])
	{
		$_option['db_name'] = \lib\db\db_data_name::get();

		if(!isset($_option['order']))
		{
			$_option['order'] = ' ORDER BY `1_quran_ayat`.`index` ASC ';
		}
		return \dash\db\config::public_get('1_quran_ayat', $_where, $_option);
	}


	public static function search_detail($_string, $_option = [])
	{
		$_option['db_name'] = \lib\db\db_data_name::get();
		return \dash\db\config::public_search('1_detail', $_string, $_option);
	}


	public static function get_day_aya_random($_loaded_before)
	{
		$not_in = null;
		if(is_array($_loaded_before) && $_loaded_before)
		{
			$not_in = implode(',', $_loaded_before);
			$not_in = " AND 1_quran_ayat.index NOT IN ($not_in)";
		}

		$query  = "SELECT * FROM 1_quran_ayat WHERE 1_quran_ayat.word > 10 $not_in ORDER BY RAND() LIMIT 1";
		$result = \dash\db::get($query, null, true, \lib\db\db_data_name::get());
		return $result;
	}

	public static function get_by_index($_index)
	{
		$query  = "SELECT * FROM 1_quran_ayat WHERE 1_quran_ayat.index = '$_index' LIMIT 1";
		$result = \dash\db::get($query, null, true, \lib\db\db_data_name::get());
		return $result;
	}


	public static function search($_string)
	{

		if(isset($_string))
		{
			$_string = \dash\db\safe::value($_string);
		}

		$pagination_query =
		"
			SELECT
				COUNT(*) AS `count`
			FROM
				1_quran_ayat
			WHERE
				1_quran_ayat.simple LIKE ('%$_string%')
		";

		$limit = \dash\db::pagination_query($pagination_query, 10, false, \lib\db\db_data_name::get());

		$query =
		"
			SELECT
				*
			FROM
				1_quran_ayat
			WHERE
				1_quran_ayat.simple LIKE ('%$_string%')
			$limit
		";
		$result = \dash\db::get($query, null, false, \lib\db\db_data_name::get());

		return $result;
	}

}
?>
