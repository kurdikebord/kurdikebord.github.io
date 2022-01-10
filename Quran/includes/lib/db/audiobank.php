<?php
namespace lib\db;


class audiobank
{

	public static function insert()
	{
		\dash\db\config::public_insert('audiobank', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('audiobank', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('audiobank', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('audiobank', ...func_get_args());
	}


	public static function delete_all()
	{
		return \dash\db::query("DELETE FROM audiobank WHERE 1");
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('audiobank', ...func_get_args());
	}


	public static function search($_string = null, $_option = [])
	{
		$default =
		[
			'search_field' =>
			"
				audiobank.qari LIKE ('%__string__%') OR
				audiobank.type LIKE ('%__string__%') OR
				audiobank.readtype LIKE ('%__string__%')
			",
		];

		if(!is_array($_option))
		{
			$_option = [];
		}

		$_option = array_merge($default, $_option);

		$result = \dash\db\config::public_search('audiobank', $_string, $_option);
		return $result;
	}

}
?>
