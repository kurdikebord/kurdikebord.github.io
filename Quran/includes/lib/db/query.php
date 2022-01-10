<?php
namespace lib\db;


class query
{
	public static function user_score_list()
	{
		$query =
		"
			SELECT
				COUNT(*) AS `count`,
				users.id,
				users.displayname,
				users.avatar
			FROM
				history
			INNER JOIN users ON users.id = history.user_id
			WHERE
				history.user_id IS NOT NULL
			GROUP BY history.user_id
			ORDER BY COUNT(*) DESC
			LIMIT 100
		";
		$result = \dash\db::get($query);
		return $result;
	}
}
?>