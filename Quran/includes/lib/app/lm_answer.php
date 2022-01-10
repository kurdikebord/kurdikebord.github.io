<?php
namespace lib\app;

/**
 * Class for lm_answer.
 */

class lm_answer
{

	public static function save_array($_answer, $_level_id)
	{
		if(!is_array($_answer))
		{
			\dash\notif::error(T_("Answer must be array"), 'answer');
			return false;
		}

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"), 'user');
			return false;
		}

		$star = 0;
		$load_level = \lib\app\lm_level::get($_level_id);

		if(!$load_level || !isset($load_level['lm_group_id']))
		{
			return false;
		}


		$level_id = \dash\coding::decode($_level_id);

		$question_ids = array_map(['\\dash\\coding', 'decode'], array_keys($_answer));
		$answer       = array_combine($question_ids, $_answer);
		$question_ids = array_filter($question_ids);
		$question_ids = array_unique($question_ids);

		if(!$question_ids)
		{
			\dash\notif::error(T_("Please choose option"));
			return false;
		}

		$check_true   = \lib\db\lm_question::check_list(implode(',', $question_ids), $level_id);
		if(count($check_true) !== count($_answer))
		{
			\dash\notfi::error(T_("Count answer not match by count question"));
			return false;
		}

		$multi_insert = [];
		$trueanswer = 0;
		foreach ($check_true as $key => $value)
		{

			if(isset($answer[$value['id']]))
			{
				$insert                   = [];
				$insert['lm_question_id'] = $value['id'];
				$insert['user_id']        = \dash\user::id();
				if(intval($answer[$value['id']]))
				{
					$opt = $answer[$value['id']];
					if(!is_numeric($opt))
					{
						\dash\notif::error(T_("Invalid option"));
						return false;
					}

					$opt = intval($opt);
					if(!in_array($opt, [1,2,3,4]))
					{
						\dash\notif::error(T_("Invalid option number"));
						return false;
					}

					if($opt === 3 && !$value['opt3'] && $value['opt3'] !== 0)
					{
						\dash\notif::error(T_("Can not choose this option"));
						return false;
					}

					if($opt === 4 && !$value['opt4'] && $value['opt4'] !== 0)
					{
						\dash\notif::error(T_("Can not choose this option"));
						return false;
					}

					if(intval($value['trueopt']) === $opt)
					{
						$trueanswer++;
					}

					$insert['opt'] = $opt;
				}
				else
				{
					\dash\notif::error(T_("Invalid question id"));
					return false;
				}

				$multi_insert[] = $insert;
			}
		}

		if(empty($multi_insert))
		{
			\dash\notif::error(T_("Empty data"));
			return false;
		}

		$lm_answer_id = \lib\db\lm_answer::multi_insert($multi_insert);

		if(!$lm_answer_id)
		{
			\dash\notif::error(T_("No way to insert data"), 'db');
			return false;
		}

		if($load_level['type'] === 'learn')
		{
			if($trueanswer >= 2)
			{
				$star = 3;
			}
			elseif($trueanswer >= 1)
			{
				$star = 2;
			}
		}
		else
		{
			$all_question = count($multi_insert);

			if($trueanswer >= $all_question)
			{
				$star = 3;
			}
			elseif($trueanswer >= floor((70 * $all_question) / 100))
			{
				$star = 2;
			}
			elseif($trueanswer >= floor((50 * $all_question) / 100))
			{
				$star = 1;
			}
			else
			{
				$star = 0;
			}
		}

		\lib\app\lm_star::level_learn('exam', $_level_id, $star);

		if(\dash\engine\process::status())
		{
			\dash\notif::ok(T_("Your answer saved"));
		}

		return true;
	}
}
?>