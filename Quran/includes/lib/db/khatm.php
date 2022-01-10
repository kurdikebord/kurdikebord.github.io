<?php
namespace lib\db;


class khatm
{

	public static function insert()
	{
		\dash\db\config::public_insert('khatm', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('khatm', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('khatm', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('khatm', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('khatm', ...func_get_args());
	}


	public static function check_user_active_record($_user_id, $_range, $_type)
	{
		$type = null;

		if($_type)
		{
			$type = " AND khatm.type = '$_type' ";
		}

		$query = "SELECT * FROM khatm WHERE khatm.user_id = $_user_id $type AND khatm.range = '$_range' AND khatm.status IN ('awaiting', 'running') LIMIT 1";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function get_by_id($_id)
	{
		$query = "SELECT * FROM khatm WHERE khatm.id = $_id LIMIT 1";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function search($_string, $_args)
	{
		$default =
		[
			'public_show_field' =>
			"
				khatm.*,
				(SELECT COUNT(*) FROM khatmusage WHERE khatmusage.khatm_id = khatm.id AND khatmusage.status = 'done') AS `count_complete`
			",
			'search_field'       => " khatm.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('khatm', $_string, $_args);

		return $result;
	}

}
?>
