<?php
namespace lib\db;


class lm_star
{

	public static function group_star($_group_id, $_user_id)
	{
		$query =
		"
			SELECT
				MAX(lm_star.star) AS `star`
			FROM
				lm_star
			WHERE
				lm_star.user_id = $_user_id AND
				lm_star.lm_group_id = $_group_id
			GROUP BY
				lm_star.lm_level_id
		";
		$result = \dash\db::get($query, 'star');

		return $result;
	}


	public static function insert()
	{
		\dash\db\config::public_insert('lm_star', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('lm_star', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('lm_star', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('lm_star', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('lm_star', ...func_get_args());
	}


	public static function search($_string, $_args)
	{
		$default =
		[

			'search_field'       => " lm_star.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('lm_star', $_string, $_args);

		return $result;
	}


	public static function get_user_star($_level_id, $_user_id)
	{
		$query =
		"
			SELECT
				MAX(lm_star.star) AS `star`
			FROM
				lm_star
			WHERE
				lm_star.user_id = $_user_id AND
				lm_star.lm_level_id = $_level_id
			GROUP BY
				lm_star.user_id
		";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function get_user_star_last($_level_id, $_user_id)
	{
		$query =
		"
			SELECT
				lm_star.star AS `star`
			FROM
				lm_star
			WHERE
				lm_star.user_id = $_user_id AND
				lm_star.lm_level_id = $_level_id
			ORDER BY lm_star.id DESC
			LIMIT 1
		";
		$result = \dash\db::get($query, null, true);

		return $result;
	}

}
?>
