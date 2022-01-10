<?php
namespace lib\db;


class lm_answer
{

	public static function insert()
	{
		\dash\db\config::public_insert('lm_answer', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('lm_answer', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('lm_answer', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('lm_answer', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('lm_answer', ...func_get_args());
	}


		public static function search($_string, $_args)
	{
		$default =
		[

			'search_field'       => " lm_answer.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('lm_answer', $_string, $_args);

		return $result;
	}

}
?>
