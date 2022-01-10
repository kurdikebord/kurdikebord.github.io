<?php
namespace content_lms\level\exam;


class view
{
	public static function config()
	{
		\content_lms\level\main::view();


		$question = \lib\app\lm_question::public_load('exam', \dash\request::get('id'));

		\dash\data::questionList($question);


	}
}
?>