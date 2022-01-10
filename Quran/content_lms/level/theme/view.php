<?php
namespace content_lms\level\theme;


class view
{
	public static function config()
	{
		\content_lms\level\main::view();

		$question = \lib\app\lm_question::public_load('learn', \dash\request::get('id'));

		\dash\data::questionList($question);


	}
}
?>