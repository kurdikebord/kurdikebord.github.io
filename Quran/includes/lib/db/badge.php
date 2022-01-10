<?php
namespace lib\db;


class badge
{

	public static function insert()
	{
		\dash\db\config::public_insert('badge', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('badge', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('badge', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('badge', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('badge', ...func_get_args());
	}


		public static function search($_string, $_args)
	{
		$default =
		[

			'search_field'       => " badge.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('badge', $_string, $_args);

		return $result;
	}

}
?>
