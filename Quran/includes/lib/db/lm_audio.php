<?php
namespace lib\db;


class lm_audio
{
	public static function get_last($_id, $_user_id)
	{
		$query = "SELECT * FROM lm_audio WHERE lm_audio.user_id = $_user_id AND lm_audio.lm_level_id = $_id AND lm_audio.status NOT IN ('deleted', 'archive') ORDER BY lm_audio.id DESC ";
		$result = \dash\db::get($query, null);
		return $result;
	}


	public static function insert()
	{
		\dash\db\config::public_insert('lm_audio', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('lm_audio', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('lm_audio', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('lm_audio', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('lm_audio', ...func_get_args());
	}


		public static function search($_string, $_args)
	{
		$default =
		[
			'public_show_field' =>
			"
				lm_audio.*,
				lm_group.title as `group_title`,
				lm_level.title as `level_title`,
				users.displayname AS `user_displayname`,
				tusers.displayname AS `teacher_displayname`

			",
			"master_join" =>
			"
				LEFT JOIN lm_group ON lm_group.id = lm_audio.lm_group_id
				LEFT JOIN lm_level ON lm_level.id = lm_audio.lm_level_id
				LEFT JOIN users ON users.id = lm_audio.user_id
				LEFT JOIN users AS `tusers` ON tusers.id = lm_audio.teacher
			",
			'search_field'       => " lm_audio.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('lm_audio', $_string, $_args);

		return $result;
	}

}
?>
