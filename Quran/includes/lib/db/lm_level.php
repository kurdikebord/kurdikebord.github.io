<?php
namespace lib\db;


class lm_level
{
	public static function get_by_type($_type)
	{
		$query =
		"
			SELECT
				*
			FROM
				lm_level
			WHERE
				lm_level.status = 'enable' AND
				lm_level.type = '$_type'
			ORDER BY lm_level.sort ASC, lm_level.id ASC

		";
		$result = \dash\db::get($query);
		return $result;
	}


	public static function find_next_level($_id)
	{
		$query =
		"
			SELECT
				*
			FROM
				lm_level
			WHERE
				(lm_level.sort IS NULL OR lm_level.sort > (SELECT lm_level.sort FROM lm_level WHERE lm_level.id = $_id LIMIT 1)) AND
				lm_level.id != $_id AND
				lm_level.status = 'enable' AND
				lm_level.lm_group_id = (SELECT lm_level.lm_group_id FROM lm_level WHERE lm_level.id = $_id LIMIT 1)
			ORDER BY lm_level.sort ASC, lm_level.id ASC
			LIMIT 1
		";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function get_count_group()
	{
		$query =
		"
			SELECT
				COUNT(*) AS `count`,
				lm_group.title,
				lm_group.id

			FROM
				lm_level
			INNER JOIN lm_group ON lm_group.id = lm_level.lm_group_id
			GROUP BY lm_level.lm_group_id;
		";
		$result = \dash\db::get($query);
		return $result;
	}

	public static function insert()
	{
		\dash\db\config::public_insert('lm_level', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('lm_level', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('lm_level', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('lm_level', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('lm_level', ...func_get_args());
	}


	public static function search($_string, $_args)
	{
		$default =
		[
			'public_show_field' => '
				lm_level.*,
				lm_group.title as `group_title`,
				(SELECT COUNT(*) FROM lm_question WHERE lm_question.lm_level_id = lm_level.id AND lm_question.status = \'enable\') AS `question_count`
			',
			'master_join'       => ' LEFT JOIN lm_group ON lm_group.id = lm_level.lm_group_id',
			'search_field'       =>
			"
				(
					lm_group.title LIKE ('%__string__%') OR
					lm_level.title LIKE ('%__string__%')
				)


			",

		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('lm_level', $_string, $_args);

		return $result;
	}

	public static function public_level_list($_group_id, $_user_id)
	{
		$user = null;
		if($_user_id)
		{
			$user =
			"
			,(
				SELECT
					MAX(lm_star.star) AS `star`
				FROM
					lm_star
				WHERE
					lm_star.user_id = $_user_id AND
					lm_star.lm_level_id = lm_level.id
				GROUP BY
					lm_star.user_id

				)
				AS `userstar`
			";
		}
		$query =
		"
			SELECT
				lm_level.*
				$user
			FROM
				lm_level
			WHERE
				lm_level.lm_group_id = $_group_id AND
				lm_level.status = 'enable'
			ORDER BY lm_level.sort ASC


		";
		$result = \dash\db::get($query);

		return $result;
	}

}
?>
