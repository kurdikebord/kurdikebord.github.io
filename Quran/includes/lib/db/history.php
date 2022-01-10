<?php
namespace lib\db;


class history
{

	public static function get_chart($_user_id)
	{
		$query =
		"
			SELECT

				COUNT(*) AS `value`,
				WEEKDAY(history.datecreated) AS `weekday`,
				HOUR(history.datecreated) AS `hour`
			FROM
				history
			WHERE
				history.user_id = $_user_id
			GROUP BY
				WEEKDAY(history.datecreated),
				HOUR(history.datecreated)
		";

		$result = \dash\db::get($query);
		return $result;
	}

	public static function insert()
	{
		return \dash\db\config::public_insert('history', ...func_get_args());
	}

	public static function search()
	{
		return \dash\db\config::public_search('history', ...func_get_args());
	}


	public static function get_count_listen($_user_id, $_time, $_sura, $_start_aya, $_end_aya)
	{
		$query =
		"
			SELECT
				history.sura,
				history.aya,
				COUNT(*) AS `count`
			FROM
				history
			WHERE
				history.user_id = $_user_id AND
				history.sura = $_sura AND
				history.time > $_time AND
				history.aya >= $_start_aya AND
				history.aya <= $_end_aya
			GROUP BY
				history.sura, history.aya
		";

		$result = \dash\db::get($query);

		return $result;
	}
}
?>