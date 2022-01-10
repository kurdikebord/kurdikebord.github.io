<?php
namespace content_lms\level\result;


class view
{
	public static function config()
	{
		\content_lms\level\main::view();

		$result = \lib\app\lm_level::result(\dash\request::get('id'));
		\dash\data::myResult($result);

		$userstar = \lib\app\lm_star::user_level_star(\dash\request::get('id'), true);
		\dash\data::userStar($userstar);
	}
}
?>