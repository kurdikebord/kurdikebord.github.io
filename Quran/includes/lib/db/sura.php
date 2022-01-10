<?php
namespace lib\db;


class sura
{
	public static function get($_where, $_option = [])
	{
		$_option['db_name'] = \lib\db\db_data_name::get();
		return \dash\db\config::public_get('1_sura', $_where, $_option);
	}


	public static function search($_string, $_option = [])
	{
		$_option['db_name'] = \lib\db\db_data_name::get();
		return \dash\db\config::public_search('1_sura', $_string, $_option);
	}


	public static function get_id($_id)
	{
		if(is_numeric($_id) && intval($_id) >= 1 && intval($_id) <= 114 )
		{
			$_id = intval($_id);
			return self::get(['sura' => $_id, 'limit' => 1]);
		}

		return false;
	}

}
?>
