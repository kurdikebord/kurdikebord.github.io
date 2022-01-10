<?php
namespace content_lms\level;


class main
{
	public static function view()
	{

		$loadLevel = \lib\app\lm_level::public_load_level(\dash\request::get('id'));
		\dash\data::loadLevel($loadLevel);

		if(\dash\user::id())
		{
			$userstar = \lib\app\lm_star::user_level_star(\dash\request::get('id'));
			\dash\data::userStar($userstar);
		}

		if(isset($loadLevel['lm_group_id']))
		{
			$loadGroup = \lib\app\lm_group::get($loadLevel['lm_group_id']);
			\dash\data::loadGroup($loadGroup);
		}

		$title = '';
		if(isset($loadGroup['title']))
		{
			$title .= $loadGroup['title'];
		}

		if(isset($loadLevel['title']))
		{
			$title .=  ' | '.$loadLevel['title'];
		}


		\dash\data::page_title($title);

		if(isset($loadLevel['lm_group_id']))
		{
			\dash\data::badge_link(\dash\url::here(). '/group?id='. $loadLevel['lm_group_id']);
			\dash\data::badge_text(T_("Back to level list"));
		}

		\lib\badge::set('LmsStartLevel');

	}
}
?>