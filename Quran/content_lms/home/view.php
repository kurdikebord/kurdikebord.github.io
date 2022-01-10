<?php
namespace content_lms\home;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Learning Mechanism System"));
		\dash\data::page_desc(T_("We can help you learn Quran and stay with Quran with our learn mechanism system"));

		\dash\data::page_pictogram('info');

		$groupList = \lib\app\lm_group::public_list();
		\dash\data::groupList($groupList);
	}
}
?>