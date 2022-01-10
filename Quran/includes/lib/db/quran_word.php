<?php
namespace lib\db;


class quran_word
{
	public static function get($_where, $_option = [])
	{
		$_option['db_name'] = \lib\db\db_data_name::get();
		$_option['order'] = ' ORDER BY `1_quran_word`.`id` ASC ';
		return \dash\db\config::public_get('1_quran_word', $_where, $_option);
	}


	public static function sura_first_word($_id)
	{
		if(is_numeric($_id) && intval($_id) >= 1 && intval($_id) <= 114 )
		{
			$_id = intval($_id);
			return self::get(['sura' => $_id, 'limit' => 1]);
		}

		return false;
	}

	public static function get_first_word($_where, $_order = 'ASC')
	{
		$_where = \dash\db\config::make_where($_where);
		$query  = "SELECT * FROM 1_quran_word WHERE $_where ORDER BY 1_quran_word.id $_order LIMIT 1";
		$result = \dash\db::get($query, null, true, \lib\db\db_data_name::get());
		return $result;
	}

	public static function get_by_id($_id)
	{
		$query  = "SELECT * FROM 1_quran_word WHERE 1_quran_word.id = '$_id' LIMIT 1";
		$result = \dash\db::get($query, null, true, \lib\db\db_data_name::get());
		return $result;
	}


	public static function load_from_to($_from, $_to)
	{
		$query  = "SELECT * FROM 1_quran_word WHERE 1_quran_word.id >= '$_from' AND 1_quran_word.id <= $_to";
		$result = \dash\db::get($query, null, false, \lib\db\db_data_name::get());
		return $result;
	}
}
?>
