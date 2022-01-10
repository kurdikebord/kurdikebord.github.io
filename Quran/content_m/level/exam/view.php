<?php
namespace content_m\level\exam;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Exam"));
		\dash\data::page_desc(' ');
		\dash\data::page_pictogram('info');

		\content_m\level\main::view();

		$question_list = \lib\app\lm_question::site_list(\dash\request::get('id'));
		\dash\data::questionList($question_list);


		$qid = \dash\request::get('qid');
		if($qid)
		{
			$questionDataRow = \lib\app\lm_question::get($qid);
			if($questionDataRow)
			{
				\dash\data::questionDataRow($questionDataRow);
				\dash\data::editMode(true);
			}
			else
			{
				\dash\header::status(404, T_("Invalid id"));
			}
		}
	}
}
?>