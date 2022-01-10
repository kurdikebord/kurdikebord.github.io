<?php
namespace lib\db;


class badgeusage
{
	public static function get_user_list($_user_id)
	{
		$query = "SELECT badgeusage.badge FROM badgeusage WHERE badgeusage.user_id = $_user_id ";
		$result = \dash\db::get($query, 'badge');
		return $result;
	}


	public static function get_group_by()
	{
		$query = "SELECT COUNT(*) AS `count`, badgeusage.badge FROM badgeusage GROUP BY badgeusage.badge ";
		$result = \dash\db::get($query, ['badge', 'count']);
		return $result;
	}
	public static function get_before($_caller, $_user_id)
	{
		$query = "SELECT * FROM badgeusage WHERE badgeusage.user_id = $_user_id AND badgeusage.badge = '$_caller' LIMIT 1 ";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function insert()
	{
		\dash\db\config::public_insert('badgeusage', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('badgeusage', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('badgeusage', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('badgeusage', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('badgeusage', ...func_get_args());
	}


	public static function search($_string, $_args)
	{
		$default = [];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('badgeusage', $_string, $_args);

		return $result;
	}

}
?>
