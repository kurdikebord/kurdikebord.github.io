<?php
namespace content_lms\all\home;


class view
{
	public static function config()
	{
		$level = \lib\app\lm_level::all_level_type(\dash\url::child());

		\dash\data::levelList($level);

		\dash\data::badge_link(\dash\url::here());
		\dash\data::badge_text(T_("Back to dashboard"));

	}
}
?>