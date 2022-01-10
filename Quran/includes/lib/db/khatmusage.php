<?php
namespace lib\db;


class khatmusage
{

// 	request
// reading
// cancel
// autocancel
// done

	public static function find_last_page($_id)
	{
		$query =
		"
			SELECT
				khatmusage.page AS `page`
			FROM
				khatmusage
			WHERE
				khatmusage.khatm_id = $_id AND
				khatmusage.status IN ('cancel', 'autocancel') AND
				khatmusage.page NOT IN
				(
					SELECT
						khatmusage2.page
					FROM
						khatmusage AS `khatmusage2`
					WHERE
						khatmusage2.khatm_id = $_id AND
						khatmusage2.status IN  ('request', 'reading', 'done')
				)
			LIMIT 1
		";


		$result = \dash\db::get($query, 'page', true);

		if(!$result)
		{
			$query =
			"
				SELECT
					khatmusage.page AS `page`
				FROM
					khatmusage
				WHERE
					khatmusage.khatm_id = $_id AND
					khatmusage.status IN ('request', 'reading', 'done')
				ORDER BY khatmusage.page DESC
				LIMIT 1
			";
			$result = \dash\db::get($query, 'page', true);
			if($result)
			{
				$result = intval($result);
				if($result >= 604)
				{
					return null;
				}
				else
				{
					$result = intval($result) + 1;
				}
			}
			else
			{
				return 1;
			}
		}

		$result = intval($result);
		return $result;
	}


	public static function find_last_juz($_id)
	{
		$query =
		"
			SELECT
				khatmusage.juz AS `juz`
			FROM
				khatmusage
			WHERE
				khatmusage.khatm_id = $_id AND
				khatmusage.status IN ('cancel', 'autocancel') AND
				khatmusage.juz NOT IN
				(
					SELECT
						khatmusage2.juz
					FROM
						khatmusage AS `khatmusage2`
					WHERE
						khatmusage2.khatm_id = $_id AND
						khatmusage2.status IN  ('request', 'reading', 'done')
				)
			LIMIT 1
		";

		$result = \dash\db::get($query, 'juz', true);

		if(!$result)
		{
			$query =
			"
				SELECT
					khatmusage.juz AS `juz`
				FROM
					khatmusage
				WHERE
					khatmusage.khatm_id = $_id AND
					khatmusage.status IN ('request', 'reading', 'done')
				ORDER BY khatmusage.juz DESC
				LIMIT 1
			";
			$result = \dash\db::get($query, 'juz', true);
			if($result)
			{
				$result = intval($result);
				if($result >= 30)
				{
					return null;
				}
				else
				{
					$result = intval($result) + 1;
				}
			}
			else
			{
				return 1;
			}
		}
		$result = intval($result);
		return $result;
	}


	public static function get_count_reserved_quran($_id)
	{
		$query = "SELECT COUNT(*) AS `count` FROM khatmusage WHERE khatmusage.khatm_id = $_id AND khatmusage.status IN ('request', 'reading', 'done') ";
		$result = \dash\db::get($query, 'count', true);
		return $result;
	}


	public static function get_count_done_quran($_id)
	{
		$query = "SELECT COUNT(*) AS `count` FROM khatmusage WHERE khatmusage.khatm_id = $_id AND khatmusage.status = 'done' ";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function get_last_record($_user_id, $_khatm_id)
	{
		$query = "SELECT * FROM khatmusage WHERE khatmusage.khatm_id = $_khatm_id AND khatmusage.user_id = $_user_id ORDER BY khatmusage.id DESC LIMIT 1 ";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function user_have_running_khatm($_user_id)
	{
		$query = "SELECT * FROM khatmusage WHERE khatmusage.user_id = $_user_id AND khatmusage.status IN ('request', 'reading') LIMIT 1";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function in_use($_id)
	{
		$query = "SELECT * FROM khatmusage WHERE khatmusage.khatm_id = $_id AND khatmusage.status IN ('done', 'reading') ";
		$result = \dash\db::get($query);
		return $result;
	}


	public static function insert()
	{
		\dash\db\config::public_insert('khatmusage', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('khatmusage', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('khatmusage', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('khatmusage', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('khatmusage', ...func_get_args());
	}


		public static function search($_string, $_args)
	{
		$default =
		[

			'search_field'       => " khatmusage.title LIKE ('%__string__%')",
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default, $_args);

		$result = \dash\db\config::public_search('khatmusage', $_string, $_args);

		return $result;
	}

}
?>
