<?php
namespace lib\db;


class lm_mistake
{

	public static function insert()
	{
		\dash\db\config::public_insert('lm_mistake', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('lm_mistake', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('lm_mistake', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('lm_mistake', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('lm_mistake', ...func_get_args());
	}


	public static function get_by_ids($_ids)
	{
		$query = "SELECT * FROM lm_mistake WHERE lm_mistake.id IN ($_ids)";
		$result = \dash\db::get($query);
		return $result;
	}


	public static function search($_string, $_args)
	{
		$default =
		[

			'search_field'       => " lm_mistake.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('lm_mistake', $_string, $_args);

		return $result;
	}

}
?>
