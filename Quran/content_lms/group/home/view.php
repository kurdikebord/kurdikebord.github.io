<?php
namespace content_lms\group\home;


class view
{
	public static function config()
	{
		\content_lms\group\main::group();

		$level = \lib\app\lm_level::public_level_list(\dash\request::get('id'));
		\dash\data::levelList($level);

		\dash\data::badge_link(\dash\url::here());
		\dash\data::badge_text(T_("Back to dashboard"));

		$group_score = \lib\app\lm_star::get_user_group_score(\dash\request::get('id'));
		\dash\data::groupScore($group_score);

	}
}
?>