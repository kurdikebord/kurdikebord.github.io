<?php
namespace lib\db;


class lm_question
{

	public static function insert()
	{
		\dash\db\config::public_insert('lm_question', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('lm_question', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('lm_question', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('lm_question', ...func_get_args());
	}

	public static function get_count()
	{
		return \dash\db\config::public_get_count('lm_question', ...func_get_args());
	}


	public static function search()
	{
		$result = \dash\db\config::public_search('lm_question', ...func_get_args());
		return $result;
	}


	public static function get_answer_user($_ids, $_user_id)
	{
		$query =
		"
			SELECT
				MAX(lm_answer.id) AS `answer_id`
			FROM
				lm_answer

			WHERE
				lm_answer.lm_question_id IN ($_ids) AND
				lm_answer.user_id = $_user_id
			GROUP BY lm_answer.lm_question_id
		";
		$ids = \dash\db::get($query, 'answer_id');
		if(!$ids)
		{
			return null;
		}

		$ids = implode(',', $ids);
		$query =
		"
			SELECT
				lm_answer.*,
				(SELECT lm_question.trueopt FROM lm_question WHERE lm_question.id = lm_answer.lm_question_id LIMIT 1) AS `trueopt`
			FROM
				lm_answer
			WHERE
				lm_answer.id IN ($ids)
		";
		$result = \dash\db::get($query);

		return $result;
	}


	public static function get_rand($_lm_level_id, $_limit)
	{
		$query =
		"
			SELECT * FROM lm_question
			WHERE
				lm_question.lm_level_id = $_lm_level_id AND
				lm_question.status = 'enable'
			ORDER BY RAND()
			LIMIT $_limit

		";
		$result = \dash\db::get($query);
		return $result;
	}


	public static function check_list($_ids, $_lm_level_id)
	{
		$query =
		"
			SELECT * FROM
				lm_question
			WHERE
				lm_question.id IN ($_ids) AND
				lm_question.lm_level_id = $_lm_level_id AND
				lm_question.status = 'enable'
		";
		$result = \dash\db::get($query);
		return $result;
	}

}
?>
