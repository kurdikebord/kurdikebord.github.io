<?php
namespace lib\db;


class lm_audiomistake
{

	public static function insert()
	{
		\dash\db\config::public_insert('lm_audiomistake', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('lm_audiomistake', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('lm_audiomistake', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('lm_audiomistake', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('lm_audiomistake', ...func_get_args());
	}

	public static function get_mistake($_audio_ids)
	{
		$query =
		"
			SELECT
				lm_audiomistake.*,
				lm_mistake.title
			FROM
				lm_audiomistake
			INNER JOIN lm_mistake ON lm_mistake.id = lm_audiomistake.lm_mistake_id
			WHERE
				lm_audiomistake.lm_audio_id IN ($_audio_ids)
		";
		$result = \dash\db::get($query);
		return $result;
	}


	public static function remove_all($_audio_id)
	{
		$query = "DELETE FROM lm_audiomistake WHERE lm_audiomistake.lm_audio_id = $_audio_id";
		$result = \dash\db::query($query);
		return $result;
	}


	public static function search($_string, $_args)
	{
		$default =
		[

			'search_field'       => " lm_audiomistake.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('lm_audiomistake', $_string, $_args);

		return $result;
	}

}
?>
